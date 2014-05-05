$(document).ready(function() {
    $( "#dialog-confirm" ).dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Ja": function() {
              window.location.href = $(this).attr('data-true');

            },
            'Nein': function() {
                if( $(this).attr('data-false') ){
                    window.location.href = $(this).attr('data-false');
                } else {
                    $(this).dialog('close');
                }
            }
        }
    });    
});
