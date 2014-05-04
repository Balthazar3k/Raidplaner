<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Raidplaner Installation</title>
    <!--RAIDPLANER HEADER-->
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin&effect=shadow-multiple|3d">
    <link rel='stylesheet' type='text/css' href='include/includes/css/raidplaner.css' />
    <script src='include/angelo.b3k/libs/jquery/js/jquery-1.10.2.js' type='text/javascript'></script>
    <script src='include/angelo.b3k/libs/jquery/js/jquery-ui-1.10.4.custom.min.js'></script>
    <link rel='stylesheet' type='text/css' href='include/angelo.b3k/libs/jquery/css/ui-darkness/jquery-ui-1.10.4.custom.min.css' />
    <script src='include/includes/js/b3k.js' type='text/javascript'></script>
    <!--RAIDPLANER HEADER END-->
    <script type='text/javascript'>
            $(document).ready(function() {
                    $( "#dialog" ).dialog({
                            autoOpen: true,
                            resizable: false,
                            modal: true,
                            width: 500
                    });

                    $("#dialog[step=1]").dialog({
                            buttons: {					
                                    "System Testen": function() {
                                            window.location.href = "?step="+ $(this).attr("step");
                                    },
                                    "Abbrechen": function(){
                                            window.close();
                                    }
                            }
                    });

                    $("#dialog[step=2]").dialog({
                            buttons: {					
                                    "Reload": function() {
                                            window.location.href = "?step=1";
                                    },
                                    "Abbrechen": function(){
                                            window.close();
                                    }
                            }
                    });

                    $("#dialog[step=3]").dialog({
                            buttons: {					
                                    "Installieren": function() {
                                            window.location.href = "?step="+ $(this).attr("step");
                                    },
                                    "Abbrechen": function(){
                                            window.close();
                                    }
                            }
                    });

                    $("#dialog[step=4]").dialog({
                            buttons: {					
                                    "Fertig": function(){
                                            window.location.href = "index.php";
                                    }
                            }
                    });

                    $("#dialog[step=5]").dialog({
                            buttons: {					
                                    "Dialog schliessen um Fehlermeldung zu sehen!": function(){
                                            $(this).dialog('close');
                                    },
                                    "zur Seite": function(){
                                            window.location.href = "admin.php?raidindex";
                                    }
                            }
                    });
            });
    </script>
    <style type="text/css">
            body { 
                    color: #FFF;
                    text-align: left!important;
            }

            head, body, div {
                    font-family: Ubuntu!important;
                    font-size: 14px!important;
            }



    </style>
</head>
<body>
<?php

define( "main", true );

include("include/includes/config.php");
include("include/includes/loader.php");

error_reporting(E_ERROR | E_WARNING | E_PARSE);
@ini_set('display_errors','On');



$title = "Raidpalaner & DKP System 1.1";
$install_file = "RAIDPLANER.sql";

switch( $_GET['step'] ){
	default:
            dialog( 1, $title, "Bevor Sie den Raidplaner Installieren k&ouml;nnen m&uuml;ssen einige Einstellungen getestet werden.");
	break;
	case 1:
		# Ordner die Schreibrechte Brauchen
		$chmod = array(
                    "include/raidplaner/",
                    "include/raidplaner/cache/",
                    "include/raidplaner/update/",
                    "include/raidplaner/images/bosse/",
                    "include/raidplaner/images/dungeon/",
                    "include/raidplaner/images/raidgruppen/"
		);
		
		$res = $error = array();
		foreach( $chmod as $key => $path ){
			if( !is_dir( $path ) ){
				mkdir( $path );
			}
		
			if( is_writeable( $path ) ){
				$res[] = true;
			}else{
				$error[] = $path;
				$res[] = false;
			}
		}
		
		if( in_array(false, $res) ){
			$path = "<ul>\n<li>".implode("</li>\n<li>", $error)."</li>\n</ul>";
			dialog( 2, "Error", "
				Folgende Ordner exestieren nicht oder brauchen die n&ouml;tigen Schreibrechte (CHMOD 0777)
				<p>
					".$path."
				</p>
				Dr&uuml;cken Sie \"<b>Reload</b>\" um die Einstellungen noch ein mal zu &Uumlberpr&uuml;fen.
			");
		}else{
			dialog( 3, $title, "
				Sie k&ouml;nnen den <b>". $title ."</b> nun Installieren,<br />
				es sind keine Fehler aufgetreten!
			");
		}
	break;
	
	case 3:
		db_connect();
		$sqlStat = TRUE;
		$sql_file = implode('',file($install_file));
		$sql_file = preg_replace ("/(\015\012|\015|\012)/", "\n", $sql_file);
		$sql_statements = explode(";\n",$sql_file);
		foreach ( $sql_statements as $sql_statement ) {
			if ( trim($sql_statement) != '' ) {
				if ( !@db_query($sql_statement) ){
					$sqlStat = FALSE;
				}
			}
		}
		
		if( $sqlStat ){
			$res = true;
			$res = @unlink("install.raidplaner.php");
			$res = @unlink($install_file);
			
			$removeText = ( $res ? "" : "<br />L&ouml;schen Sie die \"install.raidplaner.php\" und die \"".$install_file."\"" );
			
			dialog( 4, $title, "Eintrag in die MySQL Datenbank war <b>erfolgreich</b>!". $removeText);
		}else{
			dialog( 5, $title, "Installation in die Datenbank war <b>nicht</b> erfolgreich!");
		}
		
		db_close();
	break;
}
?>
</body>
</html>
<?php
function dialog( $step, $title, $msg ){
	echo 	'<div id="dialog" title="'.$title.'" step="'.($step).'" align="left">
				'. $msg .'
			</div>';
}
?>