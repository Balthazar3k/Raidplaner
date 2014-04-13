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
});

function button(a){
	document.location.href= a;
}
function creatWindow( path, title, breit, hoch ) {
	var one = path;
	var tow = title;
    var fenster = window.open( path , title ,"scrollbars=yes,toolbar=no,status=no,resizable=no,top=0,left=0,width="+breit+",height="+hoch);

}

function janein( msg, action )
{
	var del = confirm( msg );
	if( del ){
		document.location.href= action;
	}
}

function alertmsg( msg ){
	alert( msg );
}

function adddir( action ){
	var oname = prompt("Wie soll der Ordner Hei�en?\nOrdner Namen die es Bereits gibt vermeiden!\nBitte keine Leerzeichen in den Namen", "");
	if( oname != null ){
		document.location.href= action + oname;
	}
}

function ajaxInstallItems(page, container)
{	var http = null;
	if(window.XMLHttpRequest)
	{	ajax = new XMLHttpRequest();
	}else
	{	ajax = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if(ajax != null)
	{	ajax.open("GET", page, true);
		ajax.onreadystatechange = function ()
		{	var status = ajax.readyState;
			var span = document.getElementById(container);
			span.style.border = "solid 1px black";
			span.style.padding = "5px";
			switch(status)
			{	case 1: span.innerHTML = "Items werden Installiert, Bitte Warten!"; 
						span.style.backgroundColor = "red"; 
						span.style.color = "#FFFFFF"; 
						break;
				case 3: span.innerHTML = "Warte auf antwort vom Server!"; break;
				case 4: span.innerHTML = "Items wurden erfolgreich Installiert";
						span.style.backgroundColor = "green"; 
						span.style.color = "#FFFFFF"; 
						break;
			}
		}
		
		ajax.send(null);
	}
}