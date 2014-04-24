$(document).ready(function() {
	$('.datepicker').datepicker({ dateFormat: "yy-mm-dd" });
	$('.buttons').button();
        
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
        
        var $klasse = $("select[id=klassen]");
        
        var $klassID = $klasse.val();
        
        $klasse.change(function()
        { 	
            $klassID = $(this).val();
            $.post("index.php?chars", "spz=1&kid=" + $klassID, function(data){ 
                $("#spz").html(data); 
                $("#spz select").fadeIn();
            });
        });
        
        $('.buttonset').buttonset();
        
        $("#timeWheel, input[type=time]").live("mousewheel", function(event, delta){
            event.preventDefault();
            var now = $(this).val();
            var now = now.split(":");

            std = parseInt( now[0] );
            min = parseInt( now[1] );

            if( delta > 0 && min != 45 )
            {	min = min + 15;
            }else if( delta > 0 && std == 23 )
            {	std = '00';
            }else if( delta > 0 && min == 45 )
            {	std = std + 1;
                    min = '00';
            }

            if( delta == -1 && min != 00 )
            {	min = min - 15;
                    if( min == 0 )
                            min = "00";

            }else if( delta == -1 && std == 0 )
            {	std = 23;
            }else if( delta == -1 && min == 0 )
            {	std = std - 1;
                    min = 45;
            }

            $(this).val(std+":"+min);

	});
        
});

function button(a){
	document.location.href= a;
}
function creatWindow( path, title, breit, hoch ) {
	var one = path;
	var tow = title;
    var fenster = window.open( path , title ,"scrollbars=yes,toolbar=no,status=no,resizable=no,top=0,left=0,width="+breit+",height="+hoch);

}
