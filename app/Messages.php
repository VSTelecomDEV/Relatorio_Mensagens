<?php

namespace App;
use App\Models;
use Twilio\Rest\Client;
class Messages extends Model
{
    public function contagemMsgH()
    {

        $sql = new Sql();

                $dateIni = $this->getDataIni();
                $dateFim = $this->getDataFim();

        

                if(empty($dateIni) == false && empty($dateFim) == false)
                {
                    //$query = "SELECT COUNT(m.body) as Quantidade_H FROM dia d JOIN msgs m ON m.fk_dia_msg = d.id WHERE m.horario BETWEEN '".$horaIni."' AND '".$horaFim."' AND STR_TO_DATE(d.data_envio, '%Y-%m-%d') = ".$dateIni."";
                    $query = "SELECT d.data_envio, d.qtde_dia AS Template_DIA, d.qtde_dia_outbound
                     AS Quantidade_Total_DIA, d.qtde_dia_inbound AS Quantidade_Total_inbound 
                     FROM dia d 
                     WHERE STR_TO_DATE(d.data_envio, '%Y-%m-%d') 
                     BETWEEN STR_TO_DATE(:dateini, '%Y-%m-%d') AND STR_TO_DATE(:datefim, '%Y-%m-%d')";
                    $response = $sql->select($query, [
                        ":dateini" =>$dateIni,
                        ":datefim" =>$dateFim
                    ]);
                   return json_encode($response);
                }
    }
    public function __construct($data_hj)
    {
        $this->set_Data_Atual($data_hj);
    }

    public function get_Messages()
    {
        $result = array();

        $twilio = new Client(ACCOUNT_SID, AUTH_TOKEN);

        $data_Atual = $this->get_Data_Atual();

        $messages = $twilio->messages
        ->read([
            "dateSentAfter" => new \DateTime($data_Atual." 00:00:00 UTC-3"),
            "dateSentBefore" => new \DateTime($data_Atual." 23:59:59 UTC-3")
        ], 90000);
        

        foreach ($messages as $key => $value)
        {
            $this->setBody($value->body);
            $this->setDate($value->dateCreated);
            $this->setStat($value->status);
            $this->setDir($value->direction);
            $this->setSid($value->sid);

            if(stripos($this->getBody(), "Olá, o(a)") !== false)
            {
                $date = (array)$value->dateCreated;
                $data = new \DateTime($date["date"]);
                $intervalo = new \DateInterval("PT3H");
                $sub = $data->sub($intervalo);
                $dia = $data->format("d-m-Y");
                $hora = $sub->format("H:i:s");

                $result[] = [
                    "data" => $dia, 
                    "hora" => $hora,
                    "sid" => $this->getSid(),
                    "body" => ["msg" => $this->getBody()]
                ];
            }
        }

        $this->insertToDatabase($result);
    }


    private function insertToDatabase($lista)
    {
        $sql = new Sql();

        $newList = array();
        $contagem = count($lista);

        $exists = $this->exists($this->get_Data_Atual());
        if(count($lista) > 0)
        {

            if($exists > 0)
            {
                return;
            }
            else if($exists == 0)
            {
                foreach ($lista as $key1 => $value1)
                {
                    $newList = $value1;
                }
                
                $response = $sql->query("INSERT INTO dia (data_envio, qtde_dia) VALUES('".$newList['data']."', $contagem)"); 
    
                if($response == true)
                {
                    $last_id = $sql->select("SELECT
                    max(d.id) AS id
                    FROM
                    dia d");
                    foreach($last_id as $key)
                    {
                        $last_insert_id = $key['id'];
                    }
    
                    $response2 = $sql->query("INSERT INTO infos (sid, horario, fk_infos_dia) VALUES('".$newList['sid']."', '".$newList['hora']."', $last_insert_id)");
                    
                    $this->get_Messages_Out_Bound_Api($last_insert_id);
                    
                    foreach($lista as $chave => $val)
                    {  
                        $ms1 = $val['body']['msg'];
                        $sid1 = $val['sid'];
                        $hora1 = $val['hora'];
                        $query = "INSERT INTO msgs (body, sid, horario, fk_dia_msg) VALUES('$ms1', '$sid1', '$hora1', $last_insert_id)";
                        
                        $sql->query("INSERT INTO msgs (body, sid, horario, fk_dia_msg) VALUES('$ms1', '$sid1', '$hora1', $last_insert_id)");
                    }
                    
                }
    
                return \json_encode(array(
                    "status" => "success",
                    "messages" => "Inserções realizadas com sucesso!"
                ));
            }
        }else
        {
            
            return \json_encode(array(
                "status" => "error",
                "messages" => "Erro Lista Vazia"
            ));
        }

            
    }

    private function exists($day)
    {
        $sql = new Sql();
        $qtde;

        $response = $sql->select("SELECT
        count(d.data_envio) as qtde
        FROM
        dia d
        WHERE d.data_envio = '$day'
        LIMIT 1;");

        foreach ($response as $row => $value)
        {
            $qtde = $value['qtde'];
        }

        return $qtde;
    }

    private function get_Messages_Out_Bound_Api($id)
    {
        $results = array();

        $data_Atual = $this->get_Data_Atual();
        $twilio = new Client(ACCOUNT_SID, AUTH_TOKEN);

        $messages = $twilio->messages
                            ->read([
                                "dateSentAfter" => new \DateTime($data_Atual." 00:00:00 UTC-3"),
                                "dateSentBefore" => new \DateTime($data_Atual." 23:59:59 UTC-3")
                            ], 300000);

        $sql = new Sql();
        
        
        foreach($messages as $key => $value)
        {
            $dir = $value->direction;

            if($dir == "outbound-api")
            {
                $results[] = ["dir" => $value->direction];
            }
        }

        $qtde = count($results);
        $sql->query("UPDATE dia d
        SET d.qtde_dia_outbound = :QTDE
        WHERE d.id = :ID
        ", array(
            ":QTDE" => $qtde,
            ":ID" => $id
        )); 
    }

    public function exportCSVDia($data)
    {
        $titulo = "";
        $arq_excel = "";
        $sql = new Sql();

        // dd($data);
        // die();
        $dados = $sql->select("SELECT * FROM dia d WHERE CAST(d.data_envio as DATE) >= :ini AND CAST(d.data_envio as DATE) <= :fim ORDER BY CAST(d.data_envio as DATE)",
        [
            ":ini" => $data["data_ini2"],
            ":fim" => $data["data_fim2"]
        ]);
        
        
        $var;

        $arquivo = 'relatorio_dia_mensagens_agosto.xls';

        $html = '';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td colspan="5">Mensagens Relatorio</tr>';
		$html .= '</tr>';
		
		
		$html .= '<tr>';
		$html .= '<td><b>Data Envio</b></td>';
		$html .= '<td><b>Mensagem(Template)</b></td>';
		$html .= '<td><b>Total Mensagens(OutBound)</b></td>';
		$html .= '<td><b>Total Mensagens(Inbound)</b></td>';
		$html .= '</tr>';

        foreach($dados as $key => $value)
        {
            $arq_excel .= "
                <tr>
                    <td>".$value['data_envio']."<td>
                    <td>".$value['qtde_dia']."<td>
                    <td>".$value['qtde_dia_outbound']."<td>
                    <td>".$value['qtde_dia_inbound']."<td>
                </tr>
            ";

            $html .= '<tr>';
			$html .= '<td>'.$value['data_envio'].'</td>';
			$html .= '<td>'.$value['qtde_dia'].'</td>';
			$html .= '<td>'.$value['qtde_dia_outbound'].'</td>';
			$html .= '<td>'.$value['qtde_dia_inbound'].'</td>';
			$html .= '</tr>';
			
        }

        // Configurações header para forçar o download
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/xls");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo
		echo $html;
        exit;


    }

    public function __destruct()
    {
        $this->set_Data_Atual("");
    }
}
