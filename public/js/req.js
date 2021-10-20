$(".pesquisar").one("click", function (e) {
        e.preventDefault();

    let dataini = $("input[name='data_ini']").val();
    let datafim = $("input[name='data_fim']").val();
    let horaini = $("input[name='hora_ini']").val();
    let horafim = $("input[name='hora_fim']").val();
    
  $.ajax({
        method: "GET",
        url: `http://127.0.0.1:8000/api/get_msg`,
        contentType: "application/json",
        data:{
            "data_ini": dataini,
            "hora_ini": horaini,
            "data_fim": datafim,
            "hora_fim": horafim
        },
        dataType: "json",
       success: function(data)
        {
            let teste = JSON.parse(data);
             console.log(teste);
        }
     })
})
