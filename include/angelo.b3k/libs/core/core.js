$(document).ready(function() {
    $(document).on('click', "[data-amount]", function(event){
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
    
    $(document).on('submit', 'form#standart', function(event){
        event.preventDefault();
   
        var action = $(this).attr('action'); // Wohin die Daten gesendet werden!
        var input = $(this).serialize();     // Formular Daten umwandeln!

        $.post( action, input, function(data){
            
            $.each(data, function( key, val ){
                $('#'+key).text(val);
            });
            
        }, 'JSON');
    });
    
    $('a[href*=ajax]').click(function(event){
        event.preventDefault();
        
        var action = $(this).attr('href'); // Wohin die Daten gesendet werden!

        $.post( action, '', function(data){
            
            $.each(data, function( key, val ){
                $('#'+key).text(val);
            });
            
        }, 'JSON');
    });
    
    $('input[data-search]').bind('keyup', function(event){
        var search = $(this).val();

        if( search.length >= 3 ){
            $('.search-icon').toggleClass('fa-spinner fa-spin', 'fa-search');
            var action = $(this).attr('data-search'); // Wohin die Daten gesendet werden!
            var setIn = $(this).attr('data-set'); // Searchstring!      
        
            $.post( action, 'search=' + search, function(data){

                $(setIn).html(data);
                $('.search-icon').toggleClass('fa-spinner fa-spin', 'fa-search');
            }, 'HTML');
        }
    });
    
    $('[data-amount]').bind('click', function(event){
        event.preventDefault();
        var user_amount = $(this).parent().next(); // INPUT WERT
        var user_price = $(this).parent().next().next().next(); //GESAMT PREIS ANZEIGE
        var article_price = $(this).parent()
        console.log(user_amount.val(),user_price.text());
    });
    
    
    $('[data-toggle=popover]').popover();
});