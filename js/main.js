// Quando a página estiver pronta, fazer:

$(document).ready(function(e){

    // Inicia selects do materialize
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems, {
      // specify options here
    });

    // Inicia datepickers do materialize
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {
        format: 'dd/mm/yyyy',
        i18n: {
            cancel: 'CANCELAR',
            clear: 'Limpar',
            done: 'APLICAR',
            close: 'Fechar',
            default: 'now',
            today: 'Hoje',
            closeOnSelect: false,
            selectMonths: true,
            previousMonth: '<',
            nextMonth: '>',
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            firstDay: true,
            weekdays: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
            weekdaysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S']
        }
    });

    // Inicia tooltips do materialize
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems, {
        // specify options here
    });

    // Inicia modais do materialize
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, {
        // specify options here
    });


    // Validação e Envio de Formulários
    $("body").submit(function(e){
        e.preventDefault();
        checkForm($(":focus", this).attr("form"), $(":focus", this).attr("id"));
    });

    // Inicia Richtext
    $('textarea[class="richText"]').richText({
        // Uploads
        fileUpload: false,
        videoEmbed: false,
        backgroundColor: false,
        fonts: false,
    });

});

// Verificação de Senha

function checkPassword(form) {

    var min6 = false;
    var iguais = false;

    // Senha é Menor que 6 dígitos

    if($("#"+form+"\\.senha").val().length < 6) {

        $("#"+form+"\\.senha").parent().addClass("error");
        $("#"+form+"\\.Submit").addClass("disabled");
        if($("#erroSenhasTamanho").length == 0) { 
            $("#"+form+"\\.senha").parent().append("<span id=\"erroSenhasTamanho\" class=\"supporting-text\">A senha deverá possuir ao menos 6 dígitos!</span>");
        }

    } else {

        $("#erroSenhasTamanho").remove();
        $("#"+form+"\\.senha").parent().removeClass("error");
        min6 = true;
        
    }

    // Se as senhas não conferirem

    if($("#"+form+"\\.senha_repetir").val() != $("#"+form+"\\.senha").val()) {

        $("#"+form+"\\.senha_repetir").parent().addClass("error");
        $("#"+form+"\\.Submit").addClass("disabled");
        if($("#erroSenhas").length == 0) { 
            $("#"+form+"\\.senha_repetir").parent().append("<span id=\"erroSenhas\" class=\"supporting-text\">As senhas não conferem!</span>");
        }

    } else {
        $("#erroSenhas").remove();
        $("#"+form+"\\.senha_repetir").parent().removeClass("error");
        iguais = true;
    }

    if(min6 && iguais) {

        $("#"+form+"\\.Submit").removeClass("disabled");

    }

}

function checkForm(formId, submitter) {

    // Verificar se os campos obrigatórios foram preenchidos

    var blankFields = 0;

    $('form#'+formId+' input[type=text]').each(function(){
        if($(this).attr('required') == "required" && $(this).val() == "") {
            var label = "[id='" + this.id + ".label']";
            M.toast({text: "O campo " + $(label).text() + " não pode permanecer em branco!", classes: 'rounded error'});
            blankFields++;
        }
    });

    if(blankFields > 0) {

        return;

    }

    // Tratamento dos Campos do Formulário

    const formData = {};

    $('form#'+formId+' input, form#'+formId+' select, form#'+formId+' textarea').each(function(){

        switch($(this).attr('type')) {
            case "file":
                break;
            case "checkbox":
                if($(this).is(':checked')) {
                    formData[$(this).attr('name')] = 1;
                } else {
                    formData[$(this).attr('name')] = 0;
                }
                break;
            default:
                formData[$(this).attr('name')] = $(this).val();
          }

    });

    // Executar o Captcha

    if($('form#'+formId+' input[name=captcha]').val() != undefined) {

        grecaptcha.execute($('form#'+formId+' input[name=captcha]').val(), {action: 'login'}).then(function(token) {
            formData['captcha_token'] = token;
            sendForm(submitter, formData);
        });

    } else {

        sendForm(submitter, formData);

    }
    
}

function sendForm(submitter, formData) {

    // Prossegue com o envio do Formulário

    $.ajax({
        headers: {'Cookie' : document.cookie },
        type: "POST",
        url: $('[id="' + submitter + '"]').attr("formaction"),
        data: formData,
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true,
        success: function (data) {

            if(is_json(data)) {

                // É JSON

                // Tratamento da Resposta
                var json = jQuery.parseJSON(data);

                if(json.resultado == true) {

                    // Sucesso

                    if(json.redirect == undefined) {

                        // Manter na Página
                        M.toast({text: "" + json.toast + "", classes: 'rounded success'});

                    } else {

                        // Redirecionar
                        $.redirect('?page=' + json.redirect, {'redirect_toast': json.redirect_toast});

                    }

                } else {

                    // Erro
                    M.toast({text: "" + json.toast + "", classes: 'rounded error'});
                    return;

                }

            } else {

                // É HTML

                if($('[id="' + submitter + '"]').attr('formtarget') == "_blank") {

                    var newWindow = window.open("");
                    newWindow.document.write(data);

                } else {

                    console.log(data);

                }

            }

        },
        error: function(){

            // Timeout
            M.toast({text: "O servidor demorou muito a responder.", classes: 'rounded error'});
            return;

        },
        timeout: 10000

    });


}

function is_json(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }  
    return true;
}

function tableSearch(tableId) {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById(tableId + ".search");
    filter = input.value.toUpperCase();
    table = document.getElementById(tableId);
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }

  function uploadFile(field) {

    var icon = "[id='"+ $(field).attr('id') + ".icon']";
    var path = "[id='"+ $(field).attr('id') + ".path']";
    var uploadSession = $('form#'+$(field).attr('form')+' input[name=uploadSession]').val();

    var formData = new FormData();
    formData.append('arquivo', field.files[0]);
    formData.append('nome', $(path).val());
    formData.append('uploadSession', uploadSession);
    formData.append('token', field.files[0]);

    // Altera ícone do campo do nome do arquivo.

    $(icon).text("sync");
    $(icon).addClass("spin");

    // Envia Arquivo
    
    $.ajax({
       url : 'api/uploadArquivo.php',
       type : 'POST',
       data : formData,
       processData: false,
       contentType: false,
       success : function(data) {

        if(is_json(data)) {

            // Tratamento da Resposta
            var json = jQuery.parseJSON(data);

            if(json.resultado == true) {

                // Manter na Página
                M.toast({text: "" + json.toast + "", classes: 'rounded success'});

                $(icon).removeClass("spin");
                $(icon).text("check_circle");
                $(icon).addClass("success");
                $(path).addClass("success");
                return;

            } else {

                // Erro
                M.toast({text: "" + json.toast + "", classes: 'rounded error'});

                $(icon).removeClass("spin");
                $(icon).text("warning");
                $(icon).addClass("error");
                $(path).addClass("error");
                return;

            }

        } else {

            console.log(data);

        }

           
       }

    });

  }