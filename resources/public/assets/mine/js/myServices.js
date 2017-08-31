$(document).ready(function(e){
    $("div[id='services']").on( "change", ".updateStatus", function(e) {
        e.preventDefault();
        var row = $(this).parents('div');
        var id = row.data('id');

        var form = $("#state-"+id);
        var url_post = form.attr('action');
        var data = form.serialize();
        
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

        }).fail(function(e){
            new PNotify({
                title: 'Lo sentimos!',
                text: 'No se procesó la solicitud',
                type: 'error',
                styling: 'bootstrap3'
            });
        });
    });
});