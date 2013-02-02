<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<?php 
## Extern Window
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

include("include/includes/func/b3k_func.php");
echo "<script src='include/includes/js/b3k.js' language='JavaScript' type='text/javascript'></script>\n";

$cssPfad = 'include/designs/'.$_SESSION['authgfx'].'/';
$cssErmitteln = opendir( $cssPfad );
while( $css = readdir( $cssErmitteln )){
	if( ereg('.css', $css ) ){ $cssFile = $css; break; }else{ $cssFile = NULL; }
}
closedir($cssErmitteln);

echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
?>
<table width="100%" height="100%" border="0" cellspacing="1" cellpadding="0" class="border">
  <tr>
    <td class="Cnorm" align="center">
<?php
switch( $menu->get(1) ){
	### Insert Char
	case 'addCharInDB':
	
		if( !RaidPermission() ){ echo 'don\'t Permission'; $design->footer(); exit(); }
		
		$name = $_POST['name'];
		if( !empty( $name )){
			echo ( db_query("INSERT INTO prefix_raid_chars ( name, user ) VALUES( '".$name."', '".$_SESSION['authid'] ."') ") ? 'Erfolg' : 'Fehlschlag');
		}else{
			echo "Das Feld ist Leer, Bitte geb einen Char Name ein. ";
			button("Zurück","javascript:history.back();", 0);
		}
	break;
	case 'addCharForm':
?>  
	<br />
	Hier kann man Chars anlegen, die sich noch nicht regestriert haben!
    Der Char wird vor&uuml;bergehend unter deiner ID gespeichert!
	<form name="form" method="post" action="admin.php?extern-addCharInDB">
	   <input type="text" name="name" id="name">
	   <input type="submit" name="button" id="button" value="Speichern">
	</form>
<?php
	break;
	### Char einem anderen User Zuordnen
	case 'CharSaveAsAccount';
	
		if( !RaidPermission(0, TRUE) ){ echo 'don\'t Permission'; $design->footer(); exit(); }
		
		if( arrDataCheck( $_POST , "cid=is,uid=is" ) ){
			$res[0] = db_query("UPDATE prefix_raid_chars SET user=".$_POST['uid']." WHERE id=".$_POST['cid']);
			$res[1] = db_query("UPDATE prefix_raid_anmeldung SET user=".$_POST['uid']." WHERE `char`=".$_POST['cid']);
			$res[2] = db_query("UPDATE prefix_raid_dkp SET uid=".$_POST['uid']." WHERE cid=".$_POST['cid']);
			
			$true = array( 	 "Char wurde erfolgreich Übertragen",
							 "Alle Raid Anmeldugen vom neuen User wurde Geändert",
							 "Alle DKP wurden dem neuen User Zugeordnet");
			
			$false = array(  "Char konnte nicht Übertragen werden!",
							 "Es konnten keine Anmeldugen geändert werden!",
							 "Es Konnten keine DKP Übertragen werden!");
							 
			foreach( $res as $key => $value ){
				echo ( $value ? $true[$key]."<br>\n" : $false[$key]."<br>\n" );
			}
			
			button("Fenster Schließen","javascript:window.close();", 0);
		}else{
			echo arrDataCheck( $_POST , "cid=is,uid=is", 1);
		}
	break;
	case 'CharChangeAccount';
?>  
	<br />
	Einen Char einen andere Account zuteilen!! 
	<form name="form" method="post" action="admin.php?extern-CharSaveAsAccount">
	   Char:
	   <?php echo drop_down_menu("SELECT id, name FROM prefix_raid_chars ORDER BY name ASC" , "cid", '', '', true); ?>
        auf User: 
       <?php echo drop_down_menu("SELECT id, name FROM prefix_user ORDER BY name ASC" , "uid", '', '', true); ?>
	   <input type="submit" name="button" id="button" value="Uebertragen">
	</form>
<?php
	break;
}
?>
	</td>
  </tr>
</table>