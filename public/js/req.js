$(".pesquisar").one("click", function (e) {
        e.preventDefault();

    let dataini = $("input[name='data_ini']").val();
    let datafim = $("input[name='data_fim']").val();

    
  $.ajax({
        method: "GET",
        url: `http://127.0.0.1:8000/api/get_msg`,
        contentType: "application/json",
        data:{
            "data_ini": dataini,
            "data_fim": datafim,
        },
        dataType: "json",
       success: function(data)
        {
            //let teste = JSON.parse(data);
             data.forEach(function(value, index)
             {
                 $("#corpo").prepend( make_Lines(value))
             })
        }
     })
})

function make_Lines(data)
{
    let lines = `
     <tr>
        <td>${data.data_envio}</td>
        <td>${data.Template_DIA}</td>
        <td>${data.Quantidade_Total_DIA}</td>
        <td>${data.Quantidade_Total_inbound}</td>
     </tr>
    `
    return lines;
}
