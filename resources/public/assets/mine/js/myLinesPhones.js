var lineas = [];
var idCliente = null;
$(document).ready(function(){
    initState();
    
    $('.save-client').click(function(e){
        e.preventDefault();
        saveClient();
    });

    $('.save-service').click(function(e){
        e.preventDefault();
        saveService();
    });

    $('.save-ubication').click(function(e){
        e.preventDefault();
        saveUbication();
    });

    $(".js-data-cliente-ajax").on("change", function() {
        var id = $(this).val(); 
        idCliente = id;
        $('input#idCliente').val(idCliente);
    });

});

function initState() {
    $('#idLineaTelefonica,#inClienteHidden').val();
    $('#form-lines').hide();
    $('#lines,#services').html('<div class="text-center"><i class="fa fa-spin fa-circle-o-notch spinner-xl"></i></div>');
    loadLines();
    loadServices();
}

function loadLines() {
    $.ajax('linea-telefonica/todas', {
        type: 'get',
        success: function(result) { 
            $('#lines,#title-line').html('');
            $.each(result, function() {
                lineas.push({ 'id':this.id, 'nombre':this.nombre,'numero':this.numero });
                $('#lines').append('<button type="button" onClick="elige('+this.id+')" class="btn btn-warning btn-lg"> <i class="fa fa-phone-square"></i> '+this.nombre+' </button>');
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $message = '';
            if (XMLHttpRequest.status === 0) { 
                $message = 'Verifique su conexi&oacute;n a internet.';
            } else if (XMLHttpRequest.status == 404) {
                $message = 'Lineas telefonicas no encontradas.';
            } else if (XMLHttpRequest.status == 500) {
                $message = 'Error interno del servidor.';
            } else if (textStatus === 'parsererror') {
                $message = 'Respuesta JSON fallida.';
            } else if (textStatus === 'timeout') {
                $message = 'Tiempo de respuesta excedido.';
            } else if (textStatus === 'abort') {
                $message = 'Petici&oacute;n abortada.';
            } else {
                $message = 'Error desconocido: '+XMLHttpRequest.responseText+'.';
            }

            new PNotify({
                title: 'Oh No!',
                text: $message,
                type: 'error',
                styling: 'bootstrap3'
            });
        },
        data: { },
        async: true,
    });
}

function elige(id) {   // Linea telefónica
    $('#form-lines').hide('fast');
    var nombre = '';
    var numero = '';
    $('#idLineaTelefonica').val(id);
    $.each(lineas, function() {
        if(this.id==id)
            nombre = this.nombre;
            numero = this.numero;
    });
    $('#title-line').empty().append('<h3><i class="fa fa-phone-square btn-success" style="padding:2px;"></i> '+nombre+' <small>('+numero+')</small></h3>');
    $('#form-lines').show('fast');
}

function saveClient() {
    var form = $("#clients");
    var url_post = form.attr('action');
    var data = form.serialize();
    //console.log(form+' '+url_post+' '+data);
    // Sending form
    $.post(url_post, data, function(response, status){
        if (response.code==1 && response.type=='success') {
            $('#new-client,#new-ubication').removeClass('hidden');
            $('#new-client,#new-ubication').addClass('hidden');
        }
        new PNotify({
            title: response.title,
            text: response.text,
            type: response.type,
            styling: response.styling
        });
    }).fail(function(){
        new PNotify({
            title: 'Lo sentimos!',
            text: 'No se procesó la solicitud',
            type: 'error',
            styling: 'bootstrap3'
        });
    });
}

function saveUbication() {
    var form = $("#ubications");
    var url_post = form.attr('action');
    var data = form.serialize();
    console.log(form+' '+url_post+' '+data);
    // Sending form
    $.post(url_post, data, function(response, status){
        if (response.code==1 && response.type=='success') {
            $('#new-client,#new-ubication').removeClass('hidden show');
            $('#new-client,#new-ubication').addClass('hidden');
        }
        new PNotify({
            title: response.title,
            text: response.text,
            type: response.type,
            styling: response.styling
        });
    }).fail(function(){
        new PNotify({
            title: 'Lo sentimos!',
            text: 'No se procesó la solicitud',
            type: 'error',
            styling: 'bootstrap3'
        });
    });
}

function saveService() {
    var form = $("#service");
    var url_post = form.attr('action');
    var data = form.serialize();
    //console.log(form+' '+url_post+' '+data);
    // Sending form
    $.post(url_post, data, function(response, status){
        if (response.code==1 && response.type=='success') {
            loadServices();
        }
        new PNotify({
            title: response.title,
            text: response.text,
            type: response.type,
            styling: response.styling
        });
    }).fail(function(){
        new PNotify({
            title: 'Lo sentimos!',
            text: 'No se procesó la solicitud',
            type: 'error',
            styling: 'bootstrap3'
        });
    });
}