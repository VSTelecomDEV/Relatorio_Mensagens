<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Messages;
use App\Sql;
use Illuminate\Support\Facades\DB;
class Get_Msgs extends Controller
{
    public function contagem_Msg(Request $request)
    {
        $req= $request->all();

        $messages = new Messages(date("d-m-Y"));

        $messages->setDataIni($req['data_ini']);
        $messages->setDataFim($req['data_fim']);
        
        $response = $messages->contagemMsgH(); 

        return $response;
         
         
    }




    public function export_Xls(Request $request)
    {
        $datas = $request->all();
        
        
        $msg = new Messages(date("d-m-Y"));

        $msg->exportCSVDia($datas);
    }
}
