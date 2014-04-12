$(document).ready(function() {
	$('.datepicker').datepicker({ dateFormat: "yy-mm-dd" });
	$('.buttons').button();
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

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}