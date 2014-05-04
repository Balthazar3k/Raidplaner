$(document).ready(function() {
    $( "#dialog-confirm" ).dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Ja": function() {
              window.location.href = $(this).attr('data-true');

            },
            'Nein': function() {
                window.location.href = $(this).attr('data-false');
            }
        }
    });

    $('.buttonset').buttonset();      
});
