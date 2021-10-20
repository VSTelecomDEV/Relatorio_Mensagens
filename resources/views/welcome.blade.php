<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome/css/font-awesome.min.css')}}">
    <title>Document</title>
</head>
<body>
<div class="container_principal">
    <div class="header_twilio">
      <form class="form" action="" method="GET">
        <div class="formulario-interno">
          <div class="col-4 ">
            <label for="validationCustom03" class="form-label" id="">Data Início* </label>
            <input value="2021-08-01" type="date" class="form-control col-12" name="data_ini" id="date_ini" placeholder="Search"
              aria-label="Search" aria-describedby="inputGroupPrepend" required>
          </div>
          <div class=" " id="hora_ini">
            <label for="validationCustom03" class="form-label">Hora Início* </label>
            <input value="10:30" type="time" class="form-control mr-sm-2" name="hora_ini" id="hora_ini" placeholder="Search"
              aria-label="Search" aria-describedby="inputGroupPrepend" required>
            <div class="alert1">
            </div>
  
          </div>
          <div class="col-4">
            <label for="validationCustom03" class="form-label" id="">Data Fim* </label>
            <input value="2021-08-30" type="date" class="form-control col-12" name="data_fim" id="date_fim" placeholder="Search"
              aria-label="Search" aria-describedby="inputGroupPrepend" required>
          </div>
          <div class="col-md-2-p2">
            <label for="validationCustom03" class="form-label" id="hora_fim">Hora Fim* </label>
            <input value="13:30" type="time" class="form-control " name="hora_fim" id="hora_fim" placeholder="Search"
              aria-label="Search" aria-describedby="inputGroupPrepend" required>
          </div>
  
        </div>
        <div class="container-button">
        <button type="" name="pesquisar" id="pesquisar" class=" pesquisar btn btn-secondary ">Pesquisar</button>
        </div>
      </form>
  
    </div>
    <br>
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
      <table class="table table-bordered table-dark table-striped table-sm">
        <thead class="thead-fixed" >
          <tr>
            <th scope="">DATA</th>
            <th scope="">QTDE Template</th>
            <th scope="">QTDE Outbound</th>
            <th scope="">QTDE Inbound</th>
          </tr>
        </thead>
        <tbody id="corpo">

        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-around">
      <button class="btn btn-secondary" id="btn-mensagem">Exportar Relatório</button>
    </div>
  
  
  
   
  
  
    <footer class="footer_twilio">
      <!-- Copyright -->
      <div>
        © 2021 VS Telecom:
      </div>
      <!-- Copyright -->
    </footer>
  
    <div class="modal fade" id="modal-mensagem">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header table-dark">
            <h4 >Exportação de Relatório</h4>
            <button type="button" class="close" data-dismiss="modal"><span style="color: white;">×</span></button>
          </div>
  
          <div class="modal-body">
            <form class="" id="form2" action="{{route("api.relatorio")}}" method="GET" >
              <div class="">
                <div class=" ">
                  <label for="validationCustom03" class="form-label" id="">Data Início* </label>
                  <input type="date"  name="data_ini2" class="form-control col-12" id="date_ini" placeholder="Search" aria-label="Search"
                    aria-describedby="inputGroupPrepend">
                </div>
  
                </div>
                <div class="">
                  <label for="validationCustom03" class="form-label" id="">Data Fim* </label>
                  <input type="date"  name="data_fim2" class="form-control col-12" id="date_fim" placeholder="Search" aria-label="Search"
                    aria-describedby="inputGroupPrepend">
                </div>
            
  
              </div>
              <div class="container-button">
                <a  href="#" onClick="document.getElementById('form2').submit();" class=" btn btn-secondary " id="exportar">Exportar XLS</a>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  <script src="{{asset('js/jquery.js')}}"></script>
  <script src="{{asset('js/req.js')}}"></script>
  <script src="{{asset('js/modal.js')}}"></script> 
  <script src="{{asset('js/script.js')}}"></script> 
  <script src="{{asset('css/bootstrap/dist/js/bootstrap.min.js')}}"></script> 
  </body>
  </html>