<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Raidplaner Installation</title>
</head>
<body>
<?php
define( "main", true );

include("include/includes/config.php");
include("include/includes/loader.php");

error_reporting(E_ERROR | E_WARNING | E_PARSE);
@ini_set('display_errors','On');

db_connect ();

/*if( $_GET['install'] ){
	$arrSQL = explode("\n", $rSQL );
	$sqlStat = TRUE;
	foreach( $arrSQL as $value ){
		if( !empty( $value ) ){
			if( db_query( escape( trim( $value ) , "string" ) ) ){
	
			}else{
				$sqlStat = FALSE;
			}
		}
	}*/
if( $_GET['install'] ){
	$sqlStat = TRUE;
	$sql_file = implode('',file('RAIDPLANER.sql'));
	$sql_file = preg_replace ("/(\015\012|\015|\012)/", "\n", $sql_file);
	$sql_statements = explode(";\n",$sql_file);
	foreach ( $sql_statements as $sql_statement ) {
	  	if ( trim($sql_statement) != '' ) {
		#echo '<pre>'.$sql_statement.'</pre><hr>';
			if ( @db_query($sql_statement) ){
			}else{
				$sqlStat = FALSE;
			}
		}
	}
	
	if( $sqlStat ){
		echo "Eintrag in die MySQL Datenbank war <b>erfolgreich</b>!<br>Lösch bitte die \"install.raidplaner.php\" und dir \"RAIDPLANER.sql\"";
	}else{
		echo "Eintrag in die Datenbank war <b>nicht</b> erfolgreich!";
	}
}else{
	echo "Raidplaner Datenbank Tabellen Installieren? ";
	echo "[ <a href='".$_SERVER['PHP_SELF']."?install=1'>Installieren</a> | <a href='javascript:window.close()'>Abbrechen</a> ]";
}

db_close();

?>
</body>
</html>
