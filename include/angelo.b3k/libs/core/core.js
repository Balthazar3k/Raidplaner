$(document).ready(function() {
    $( "[data-amount]" ).click(function(event){
        event.preventDefault();
        var amount = $(this).attr('data-amount');
        var input = $(this).parent().next();
        var current = input.val();
        input.val(eval(current+amount));
        
        if( input.val() <= 0){
            $(this).addClass('disabled');
        } else {
            $(this).next().removeClass('disabled');
        }
        
    });
    
    $('form#standart').bind('submit', function(event){
        event.preventDefault();
   
        var action = $(this).attr('action'); // Wohin die Daten gesendet werden!
        var input = $(this).serialize();     // Formular Daten umwandeln!

        $.post( action, input, function(data){
            
            $.each(data, function( key, val ){
                $('#'+key).text(val);
            });
            
        }, 'JSON');
    });
});
