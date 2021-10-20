$(document).ready(function () {
    $("p.error2").hide();
    $(".pesquisar").one("click", function () {

        let dataini = $("input[name='data_ini']").val();
        let datafim = $("input[name='data_fim']").val();
        let horaini = $("input[name='hora_ini']").val();
        let horafim = $("input[name='hora_fim']").val();

        if ($(dataini).text("") || $(datafim).text("") || $(horaini).text("") || $(horafim).text("")) {

            var p = $(`<div class="alert1">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&#8727;</span>
            Verifique os campos obrigatórios
            </div>`);
            p.hide()
            $('.form').append(p);
            p.fadeIn(500, function () {
                window.setTimeout(function () {
                    p.fadeOut();
                }, 2000)
            });
        } else {

        }
    })
});