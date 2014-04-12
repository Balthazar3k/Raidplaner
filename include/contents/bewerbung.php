<?php 

defined ('main') or die ( 'no direct access' );
$title = $allgAr['title'].' :: Bewerbung';
$hmenu = 'Bewerbung';
$design = new design ( $title , $hmenu );
require_once("include/includes/func/b3k_func.php");

$design->header();

$tpl = new tpl ("raid/LEERE_BOX.htm");
if( RaidPermission( 0, TRUE ) ){
	if( $menu->get(1) == 'updateKlassen' ){
		db_query("UPDATE prefix_raid_klassen SET aufnahmestop=".$menu->get(3)." WHERE id=".$menu->get(2) );
	}
	
	$result = db_query("SELECT id, klassen FROM prefix_raid_klassen WHERE aufnahmestop=0");
	while( $row = db_fetch_object( $result )){
		$aK .= aLink("<img src='include/images/wowklein/".$row->klassen.".gif' border=0>","bewerbung-updateKlassen-".$row->id."-1")." ";
	}
	$tpl->set_out("msg", "<b>Einstellungen, welche Klassen sucht ihr noch:</b><hr> ".$aK, 0 );
	echo "<br />";
}

$result = db_query("SELECT id, klassen FROM prefix_raid_klassen WHERE aufnahmestop=1");
$cKlassen = db_num_rows($result);
if( $cKlassen > 0 ){
	while( $row = db_fetch_object($result) ){
		if( RaidPermission( 0, TRUE) ){
			$kName .= aLink("<img src='include/images/wowklein/".$row->klassen.".gif' border=0>","bewerbung-updateKlassen-".$row->id."-0")." ";
		}else{
			$kName .= "<img src='include/images/wowklein/".$row->klassen.".gif' title='".$row->klassen."'> \n";
		}
	}
	$tpl->set_out("msg", "<b>Wir Suchen noch Klassen:</b><hr> ".$kName, 0 );
}else{
	$tpl->set_out("msg", "Zur Zeit suchen wir keine Klassen mehr!", 0 );
}
echo "<br />";

switch($menu->get(1)){
######################
### SENDEN ###########
######################
	case "senden":

		db_query("UPDATE prefix_user SET gebdatum='".$_POST['alter']."' WHERE id=".$_SESSION['authid']);
		
		$erg = db_query( 'INSERT INTO `prefix_raid_chars` (`id`, `user`, `name`, `klassen`, `rassen`, `level`, 
		`s1`, `s2`, `s3`,  `rlname`, `mberuf`, `mskill`, `sberuf`, `sskill`, `warum`, `pvp`, `raiden`, `realm`, `teamspeak`, `regist`) 
		VALUES (NULL, \''.$_SESSION['authid'].'\',
		 \''.ascape($_POST['name']).'\',
		  \''.$_POST['klassen'].'\',
		   \''.$_POST['rassen'].'\',
		    \''.$_POST['level'].'\',
			 \''.$_POST['s1'].'\',
			  \''.$_POST['s2'].'\',
			   \''.$_POST['s3'].'\',
				\''.$_POST['rlname'].'\',
				 \''.$_POST['mberuf'].'\',
				  \''.$_POST['mskill'].'\',
				   \''.$_POST['sberuf'].'\',
					\''.$_POST['sskill'].'\',
					 \''.ascape($_POST['warum']).'\',
					  \''.ascape($_POST['pvp']).'\',
					   \''.ascape($_POST['raiden']).'\',
						\''.ascape($allgAr['realm']).'\',
						 \''.$_POST['teamspeak'].'\',
						  CURRENT_TIMESTAMP);');
			if( $erg ){

				// Sendet eine PM an berechtigte das eine Neuer bewerbe vorhande ist!
				send_pm2legitimate(
					'Script: eine neue Bewerbung',
					'Es hat sich jemand neues Beworben!'
				);

				wd("index.php?bewerbung","Du hast dich erfolgreich Beworben");
			}else{
				wd("index.php?bewerbung","Du hast dich <b>nicht</b> erfolgreich Beworben " );
			}
		 
	break;
	case "sendKlassen":
		if( RaidPermission() ){
			foreach( $_POST as $id => $val ){
				if( $id != 'button' ){
					db_query("UPDATE prefix_raid_klassen SET aufnahmestop=".$val." WHERE id='".$id."'");
				}
			}
			
			wd('index.php?bewerbung','', 0 ); 
		}else{
			echo "Don't Permission!";
			$design->footer();
			exit();
		}
	break;
######################
### Char L�schen #####
######################
	case "del":
		$char_name = db_result(db_query('SELECT name FROM prefix_raid_chars WHERE id='.$menu->get(2) ),0);
		if( is_admin() && $menu->get(3) != "true" ){
			echo "<center>Wirklich alle daten von \"".$char_name."\" L&ouml;chen? ";
			echo "[ <a href='index.php?".$_SERVER['QUERY_STRING']."-true'>Ja</a> | <a href='index.php?bewerbung'>Nein</a> ]</center>";
		}
		
		if( $menu->get(3) == "true" ){
			if( db_query("DELETE FROM prefix_raid_chars WHERE id = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_dkp WHERE cid = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_kalender WHERE cid = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_anmeldung WHERE `char` = '".$menu->get(2)."'") ){
				wd('index.php?bewerbung',$char_name.' wurde erfolgreich gel&ouml;cht!', 1);
			}else{
				wd('index.php?bewerbung',$char_name.' wurde "<b>NICHT</b>" erfolgreich gel�scht!', 3);
			}
		}
	break;
######################
### BEWERBER ######### 
######################
	default:
		$tpl = new tpl ("raid/BEWERBER_LISTE.htm");
		button("Formular","index.php?bewerbung-formular", 0); 
		print "<br><br>";
		$result = db_query( "SELECT
							a.id,
							a.name,
							a.s1,
							a.s2,
							a.s3, 
							b.id as kid,
							b.klassen,
							c.level,
							DATE_FORMAT(a.regist,'%d.%m.%y %H:%i') as datum 
							FROM
							prefix_raid_chars AS a 
							LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
							LEFT JOIN prefix_raid_level AS c ON a.level = c.id  
						 WHERE 
						 	a.rang = 1 
						 ORDER BY datum ASC" ,0);
		
		$tpl->out(0);				 
		if( db_num_rows( $result ) > 0 ){
			$i = 0;
			while( $row = db_fetch_assoc( $result ) ){
				$i++;
				$row['class'] = ( $row['class'] != 'Cmite' ? 'Cmite' : 'Cnorm' );
				$row['img'] = "<img src='include/images/wowklein/".$row['klassen'].".gif'>";
				$row['name'] = $i.". ".aLink($row['name'], "chars-show-".$row['id']);
				$row['skill'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['kid']);
				$row['opt'] = ( is_admin() ? button("L$ouml;schen","index.php?bewerbung-del-".$row['id'], 2) : '');
				$tpl->set_ar_out( $row, 1 );
			}
		}else{
			$tpl->out(2);
		}
		$tpl->out(3);
	break;
######################
### FORMULAR #########
######################
	case "formular":
		button("Zur&uuml;ck","",8);
		echo "<br><br>";
		$tpl = new tpl ("raid/BEWERBUNGS_FORMULAR.htm");
		if( loggedin() and !RaidRechte($allgAr['addchar']) or is_admin() ){
			$tpl->out(0);
			### List menüLevel
			$abf = 'SELECT * FROM prefix_raid_level';
			$erg = db_query($abf);
			while($row = db_fetch_assoc($erg)) {
			  $liste .= $tpl->list_get( 'level', array ( $row['level'] ,$row['id'] ));
			}
			$tpl->set('level', $liste );
			### List menüKlassen
			$abf = 'SELECT * FROM prefix_raid_klassen WHERE aufnahmestop=1';
			$erg = db_query($abf);
			$liste = "";
			while($row = db_fetch_assoc($erg)) {
			  $liste .= $tpl->list_get( 'level', array ( $row['klassen'] ,$row['id'] ));
			}
			$tpl->set('klasse', $liste );
			### List menüRassen
			$abf = 'SELECT * FROM prefix_raid_rassen';
			$erg = db_query($abf);
			$liste = "";
			while($row = db_fetch_assoc($erg)) {
			  $liste .= $tpl->list_get( 'rasse', array ( $row['rassen'] ,$row['id'] ));
			}
			$tpl->set('rasse', $liste );
			### List  menüBerufe
			$abf = 'SELECT * FROM prefix_raid_berufe ORDER BY berufe';
			$erg = db_query($abf);
			$liste = "";
			while($row = db_fetch_assoc($erg)) {
			  $liste .= $tpl->list_get( 'beruf', array ( $row['berufe'] ,$row['id'] ));
			}
			$tpl->set('beruf', $liste );
			
			$tpl->out(1);
		}else{
			echo bbcode(allgArInsert($allgAr['bewerbung']));
		}
	break;
}

copyright();
$design->footer();

function send_pm2legitimate($title, $text, $status = 0){
	$res = db_query('
		SELECT 
		    a.id
		FROM `prefix_user` AS a
		    LEFT JOIN `prefix_raid_chars` AS b ON a.id = b.user 
		WHERE b.rang > 6
	');
	while( $row = db_fetch_assoc($res) ){
		sendpm($_SESSION['authid'], $row['user'], $title, $text, $status);
	}
}
?>
