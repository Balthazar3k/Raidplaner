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
        
});

function button(a){
	document.location.href= a;
}
function creatWindow( path, title, breit, hoch ) {
	var one = path;
	var tow = title;
    var fenster = window.open( path , title ,"scrollbars=yes,toolbar=no,status=no,resizable=no,top=0,left=0,width="+breit+",height="+hoch);

}
