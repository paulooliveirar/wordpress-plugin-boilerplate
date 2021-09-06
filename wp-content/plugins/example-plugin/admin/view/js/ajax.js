
jQuery(document).ready(function($) {
    jQuery('#teste').click(function(){
        var data = {
            'action': 'select',
            'id': 1
        };
        jQuery.post(backend_ajax_data.admin_ajax, data)
        .done(function( msg, textStatus ) {            
            console.log(msg, textStatus);
            if(msg.indexOf("error") != -1) {
                alert("Erro grave ao salvar o popup");
                console.error("Erro grave ao salvar o registro: ", "\nMensagem de Erro:\n", msg, "\nStatus da requisição:\n", textStatus);
                return;
            }
        })
        .fail(function (jqXHR, exception) {
            console.log('Uncaught Error.\n' + jqXHR.responseText + ' - ' + jqXHR.status + ' - ' + exception);                
        });
    });
});
