<?php 
defined ('main') or die ( 'no direct access' );

function klassenSpz( $id)
{	$res = db_query("SELECT s1b, s2b, s3b FROM prefix_raid_klassen WHERE id=".$id."");
	$row = db_fetch_assoc( $res );
	$kspz = "* <select style=\"display: none;\" name=\"s1\"><option></option><option value=\"".$row['s1b']."\">".$row['s1b']."</option><option value=\"".$row['s2b']."\">".$row['s2b']."</option><option value=\"".$row['s3b']."\">".$row['s3b']."</option></select> ";
	$kspz .= "<select style=\"display: none;\" name=\"s2\"><option></option><option value=\"".$row['s1b']."\">".$row['s1b']."</option><option value=\"".$row['s2b']."\">".$row['s2b']."</option><option value=\"".$row['s3b']."\">".$row['s3b']."</option></select>";
	return $kspz;
}

if( isset( $_POST['kid'] ))
{	exit(klassenSpz($_POST['kid']));
	//ALTER TABLE `prefix_raid_chars` CHANGE `s1` `s1` VARCHAR( 55 ) NOT NULL DEFAULT '0', CHANGE `s2` `s2` VARCHAR( 55 ) NOT NULL DEFAULT '0'
}

$title = $allgAr['title'].' :: Chars';
$hmenu = 'Chars';
$design = new design ( $title , $hmenu );
require_once("include/includes/func/b3k_func.php");
$design->addheader($raidHeader);
$design->header();


$kalout .= '<script src="include/includes/js/b3k.js" language="JavaScript" type="text/javascript"></script>';

RaidErrorMsg();

$img_del = "<img src='include/images/icons/del.gif' border='0'>";
$img_edit = "<img src='include/images/icons/edit.gif' border='0'>";

$kalout .= $_SESSION['authid'] ."=". $uid_s;
switch($menu->get(1)){
	#### Char Löschen
	case "del":
		$char_name = db_result(db_query('SELECT name FROM prefix_raid_chars WHERE id='.$menu->get(2) ),0);
		if(   $menu->get(3) != "true" ){
			echo "<center>Wirklich alle daten von \"".$char_name."\" Löschen? ";
			echo "[ <a href='index.php?".$_SERVER['QUERY_STRING']."-true'>Ja</a> | <a href='index.php?chars'>Nein</a> ]</center>";
		}
		
		if( is_admin() && $menu->get(3) == "true" ){
			if( db_query("DELETE FROM prefix_raid_chars WHERE id = '".$menu->get(2)."' LIMIT 1") &&
			db_query("DELETE FROM prefix_raid_dkp WHERE cid = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_kalender WHERE cid = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_anmeldung WHERE `char` = '".$menu->get(2)."'") ){
				wd('index.php?chars',$char_name.' wurde erfolgreich gelöscht!', 1);
			}else{
				wd('index.php?chars',$char_name.' wurde "<b>NICHT</b>" erfolgreich gelöscht!', 3);
			}
		}
	break;
	#### Neuen Charanlegen.
	case "add":
		if( !RaidRechte($allgAr['addchar']) ){ echo "no Permission!"; $design->footer(); exit(); }
		if( arrDataCheck($_POST, "name=is,level=is,klassen=is,rassen=is") ){
		db_query("INSERT INTO ".$_POST['db']." (name,user,level,klassen,rassen,s1,s2,skillgruppe,mberuf,mskill,
				 sberuf,sskill,rlname,teamspeak,warum,pvp,raiden,realm,rang) VALUES (
				'". escape($_POST['name'], 'string')		."',
				'". $_SESSION['authid']						."',
				'". escape($_POST['level'], 'integer')		."',
				'". escape($_POST['klassen'], 'integer')	."',
				'". escape($_POST['rassen'], 'integer')		."',
				'". escape($_POST['s1'], 'string')			."',
				'". escape($_POST['s2'], 'string')			."',
				'". escape($_POST['skillgruppe'], 'integer')."',
				'". escape($_POST['mberuf'], 'integer')		."',
				'". escape($_POST['mskill'], 'integer')		."',
				'". escape($_POST['sberuf'], 'integer')		."',
				'". escape($_POST['sskill'], 'integer')		."',
				'". escape($_POST['rlname'], 'string')		."',
				'". escape($_POST['teamspeak'], 'integer')	."',
				'". escape($_POST['warum'], 'textarea')		."',
				'". escape($_POST['pvp'], 'textarea')		."',
				'". escape($_POST['raiden'], 'textarea')	."',
				'". escape($_POST['realm'], 'string')		."',
				'2')");
			 wd('index.php?chars',escape($_POST['name'], 'string').' wurde erstellt, warte Bitte 3sec!', 3);
		}else{
			echo arrDataCheck($_POST, "name=is,level=is,klassen=is,rassen=is",1)."<br>";
			button("Zurück und die Daten erneut eingeben.","", 8);
		}
				  
	break;
	#### Char Bearbeiten
	case "charedit":
		if( !RaidRechte($allgAr['addchar']) ){ echo "no Permission!"; $design->footer(); exit(); }
			if( arrDataCheck($_POST, "name=is,level=is,klassen=is,rassen=is") ){
				$no = array("Submit" => 1, "db" => 1, "id" => 1);
				foreach( $_POST as $key => $value ){
					if( $no[$key] != 1 ){
						$res = db_query("UPDATE prefix_raid_chars SET ".$key."='".ascape($value)."' WHERE id=".$_POST['id']);
					}else{
						$false = false;
					}
				}
				if( $res && !$false ){
					wd('index.php?chars','Char Bearbeiten erfolgreich', 1);
				}
			}else{
				echo arrDataCheck($_POST, "name=is,level=is,klassen=is,rassen=is,41>-s1,41>-s2,41>-s3,s1=plus,s2=plus,s3=plus,s1+s2+s3|41|skillung|skillpunkten=sum",1);
			}
	break;
	#### Raid Tage
	case "raidtage";
		@db_query("DELETE FROM prefix_raid_kalender WHERE cid=".$menu->get(2) );
		foreach( $_POST as $key => $value ){
			list( $zeit, $wochtag ) = explode("-", $key);
			if( $zeit != 0 ){
				db_query('INSERT INTO `prefix_raid_kalender` (`cid`, `zid`, `wid`) VALUES (\''.$menu->get(2).'\', \''.$zeit.'\', \''.$wochtag.'\');');
			}
		}
		wd('index.php?chars--show-'.$menu->get(2),'Raidtage wurde geändert');
	break;
	#### Neuer Char Formular
	case "newchar":
		if( !RaidRechte($allgAr['addchar']) ){ echo "no Permission!"; $design->footer(); exit(); }
		$tpl = new tpl ('raid/CHARS_EDIT_CREAT.htm');
		$row['PFAD'] = "index.php?chars-add";
		$row['TITEL'] = "Neuer Char";
		$row['name'] = ""; $row['ro'] = "";
		$row['level'] = drop_down_menu("prefix_raid_level" , "level", $value, "");
		$row['klassen'] = drop_down_menu("prefix_raid_klassen" , "klassen", $value, "");
		$row['rassen'] = drop_down_menu("prefix_raid_rassen" , "rassen", $value, "");
		$row['spz'] = "Klasse wählen!";
		$row['skillgruppe'] = skillgruppe(1,0);
		$row['raiden'] = $row['pvp'] = $row['warum'] = $row['rlname'] = "";
		$row['mskill'] = $row['sskill'] = "";
		$row['mberuf'] = drop_down_menu("prefix_raid_berufe" , "mberuf",  "", "");
		$row['sberuf'] = drop_down_menu("prefix_raid_berufe" , "sberuf",  "", "");
		$row['realm'] = $allgAr['realm'];
		$row['user'] = $_SESSION['authid'];
		$row['db'] = "prefix_raid_chars";
		$tpl->set_ar_out( $row, 0 );
	break;
	#### Bearbeiten Char Formular.
	case "edit":
		if( !RaidRechte($allgAr['addchar']) ){ echo "no Permission!"; $design->footer(); exit(); }
		$tpl = new tpl ('raid/CHARS_EDIT_CREAT.htm');
		$res = db_query("SELECT id, name, level, klassen, rassen, s1, s2, skillgruppe, realm, rlname, warum, raiden, pvp, teamspeak, mberuf, sberuf, mskill, sskill FROM prefix_raid_chars WHERE id=".$menu->get(2)." LIMIT 1");
		$row = db_fetch_assoc( $res );
		$row['PFAD'] = "index.php?chars-charedit";
		$row['TITEL'] = "Edit Char";
		$db = "prefix_raid_chars";
		$row['ro'] = "readonly";
		$row['skillgruppe'] = skillgruppe(1,$row['skillgruppe']);
		$row['level'] = drop_down_menu("prefix_raid_level" , "level", $row['level'], "");
		$row['klassen'] = drop_down_menu("prefix_raid_klassen" , "klassen", $value, "");
		$row['spz'] = "Klasse wählen!";
		$row['rassen'] = drop_down_menu("prefix_raid_rassen" , "rassen",  $row['rassen'], "");
		$row['mberuf'] = drop_down_menu("prefix_raid_berufe" , "mberuf",  $row['mberuf'], "");
		$row['sberuf'] = drop_down_menu("prefix_raid_berufe" , "sberuf",  $row['sberuf'], "");
		$row['tsy'] = ( $row['teamspeak'] == 1 ? 'checked="checked"' : '' );
		$row['tsn'] = ( $row['teamspeak'] == 0 ? 'checked="checked"' : '' );
		$row['db'] = $db;
		$tpl->set_ar_out( $row, 0 );
	break;
	
	case "show":
		$tpl = new tpl ('raid/CHARS_DETAILS.htm');
		button("Zurück","",8);
		$row = db_fetch_assoc(db_query("SELECT 
							a.name, a.teamspeak, a.mberuf, a.mskill, a.sberuf, a.sskill, a.raiden, a.warum, a.pvp, a.skillgruppe,
							a.s1, a.s2, a.s3, a.realm, a.user, a.punkte, a.id, a.rlname, 
							b.id as klassenid, b.klassen,
							c.level,
							d.id as rangid,
							d.rang, 
							f.name AS username, 
							e.rassen
						 FROM prefix_raid_chars AS a 
						 	LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
						 	LEFT JOIN prefix_raid_level AS c ON a.level = c.id 
						 	LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
						 	LEFT JOIN prefix_raid_rassen AS e ON a.rassen = e.id 
						 	LEFT JOIN prefix_user AS f ON a.user = f.id 
						 WHERE a.id = ".$menu->get(2) ) );
		
		$row['TITEL'] = "Deatails von ". $row['name'];
		$row['username'] = "<a href='index.php?user-details-".$row['user']."'>".$row['username']."</a>";
		$row['alter'] = alter(db_result(db_query("SELECT gebdatum FROM prefix_user WHERE id='".$row['user']."'"),0));
		$row['teamspeak'] = ( $row['teamspeak'] == 1 ? "Vorhanden" : "Kein Teamspeak" );
		$row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['klassenid']);
		$row['skillgruppe'] = skillgruppe(0,$row['skillgruppe']);
		$row['mberuf'] = db_value( "prefix_raid_berufe", "berufe", $row['mberuf']);
		$row['sberuf'] = db_value( "prefix_raid_berufe", "berufe", $row['sberuf']);
		### Raidkalender
		$wochentag = array( 0 => "So", 1 => "Mo", 2 => "Di", 3 => "Mi", 4 => "Do", 5 => "Fr", 6 => "Sa");
		$ctd = count($wochentag); #--- Zähele Spalten (td)
		$res = db_query("SELECT * FROM prefix_raid_zeit");
		$kalout = '<form id="form" name="form" method="post" action="index.php?chars-raidtage-'.$menu->get(2).'">';
		$kalout .= "<table border='0' cellspacing='1' cellpadding='5' class='border'><tr class='Chead'><td></td>";
		for( $i=0; $i<$ctd; $i++ ){
			$kalout .= "<td><center>".$wochentag[$i]."<center></td>";
		}
		$kalout .= "</tr>";
		while( $rows = db_fetch_assoc($res)){
			if( $Class == $Cnorm ){ $Class = $Cmite; }else{ $Class = $Cnorm; }
			$kalout .= "<tr class='".$Class."'>";
			$kalout .= "<td class='Cdark'><div align='right'>".$rows['zeit']." Uhr</div></td>";
			for( $i=0; $i<$ctd; $i++ ){
				$erf = db_result(db_query("SELECT COUNT(cid) FROM prefix_raid_kalender WHERE cid='".$menu->get(2)."' AND zid='".$rows['id']."' AND wid='". $i."'"),0);
				if( $_SESSION['authid'] == $row['user'] && $row['rangid'] == $_SESSION['charrang'] ){
					$ck = ( $erf == 0 ? '' : 'checked' );
					$color = ( $erf == 0 ? 'darkred' : 'darkgreen' );
					$kalout .= '<td bgcolor="'.$color.'"><input name="'.$rows['id'].'-'.$i.'" type="checkbox" value="1" '.$ck.'></td>';
				}else{
					$color = ( $erf == 0 ? 'darkred' : 'darkgreen' );
					$symb = ( $erf == 0 ? 'X' : 'O' );
					$kalout .= '<td bgcolor="'.$color.'"><center>'.$symb.'</center></td>';
				}
			}
			$kalout .= "</tr>";
		}
		$kalout .= '</table>';
		if( $_SESSION['authid'] == $row['user']  && $row['rangid'] == $_SESSION['charrang'] ){
			$kalout .= '<input type="submit" name="button" id="button" value="Senden" />';
		}
		$kalout .= '</form>';
		
		$row['raid_kalender'] = $kalout;
		$tpl->set_ar_out( $row, 0 );
		
		
		#### WEITERE CHARAKTERE
		$tpl->out(7);
		
		$res = db_query("
			SELECT 
				a.id, a.name,
				b.level,
				c.klassen,
				d.rassen,
				e.rang
			FROM prefix_raid_chars AS a
			  LEFT JOIN prefix_raid_level AS b ON a.level=b.id
			  LEFT JOIN prefix_raid_klassen AS c ON a.klassen=c.id
			  LEFT JOIN prefix_raid_rassen AS d ON a.rassen=d.id
			  LEFT JOIN prefix_raid_rang AS e ON a.rang=e.id
			WHERE a.user = '".$row['user']."'
			  AND a.id != '".$row['id']."' 
			ORDER BY a.name ASC
		");
		
		while( $charaktere = db_fetch_assoc( $res ) ){
			$tpl->set_ar_out($charaktere, 8);
		}
		
		$tpl->out(9);
		
		### Deine Ausgegeben DKP
		$tpl->set_ar_out( array("TITEL" => "Deine DKP Ausgaben"), 1 );
		$abf = db_query("
		
		SELECT rid, prefix_raid_dkp.date, prefix_raid_gruppen.gruppen, info, dkp, prefix_raid_dkp.id 
		FROM prefix_raid_dkp 
		LEFT JOIN prefix_raid_gruppen ON prefix_raid_dkp.dkpgrp=prefix_raid_gruppen.id 
		WHERE dkp LIKE '-%' AND cid='".$row['id']." 
		ORDER BY date ASC'");
		
		while($row = db_fetch_assoc( $abf )){
			$row['date'] = "<a href='index.php?raidlist-showraid-".$row['rid']."'>" . date("d.m.Y H:i", $row['date']) . "</a>";
			$row['info'] = RaidItems($row['info'], $menu->get(0) );
			$row['CLASS'] = cssClass($row['CLASS']);
			$tpl->set_ar_out( $row, 2 );
		}
		$tpl->out(3);
		
		### RAIDBETEILGUNG
		$tpl->out(4);
		$abf = db_query("SELECT id, gruppen FROM prefix_raid_gruppen WHERE gruppen!='n/a'");
		while( $row = db_fetch_assoc( $abf )){
			$count_all_raids = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen='".$row['id']."' AND statusmsg=2"),0);
			$count_raids = db_result(db_query("SELECT COUNT(b.id) FROM prefix_raid_anmeldung AS a, prefix_raid_raid AS b WHERE a.grp='".$row['id']."' AND a.char='".$menu->get(2)."' AND a.stat='12' AND a.rid=b.id AND b.statusmsg=2"),0);
			if( $count_raids != 0 ){
				### Prozente Berechnen
				$row['prz'] = pzVortschritsAnzeige($count_raids,$count_all_raids);
				$row['vonbis'] = $count_raids ."/".  $count_all_raids;
				$row['CLASS'] = cssClass($row['CLASS']);
				### Datenausgabe
				$tpl->set_ar_out( $row, 5 );
			}
		}
		
		$tpl->out(6);
		
	break;
	
	default:
		$tpl = new tpl ('raid/CHARS_LIST.htm');

		$c['COUNT_CHARS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars"),0);
		$c['COUNT_MAINS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang >= 4"),0);
		$c['COUNT_LEVEL'] = db_result(db_query("SELECT COUNT(level) FROM prefix_raid_chars WHERE level = '1'"),0);
		$c['COUNT_EIGENE'] = db_result(db_query("SELECT COUNT(user) FROM prefix_raid_chars WHERE user = '".$_SESSION['authid']."'"),0);
		$c['COUNT_BEWERBER'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang = 1"),0);
		
		if( RaidRechte($allgAr['addchar']) ){
			if( $allgAr['maxchars'] > $c['COUNT_EIGENE'] ){
				$c['USER'] = "[ <a href='index.php?chars-newchar'>Neuer Char</a> ]";
			}else{
				$c['USER'] = "Max. ".$c['COUNT_EIGENE']."/".$allgAr['maxchars']." Chars Erreicht";
			}
		}else{
			$c['USER'] = "LINK CLOSED";
		}
		### Klassen Liste erstellen
		$erg = db_query("SELECT id, klassen FROM prefix_raid_klassen ORDER BY id DESC");
		$l_klassen = "<a href='index.php?chars'>".$img_del."</a> ";
		while( $row = db_fetch_assoc( $erg )){
			$c['list_klassen'] .= "<a href='index.php?chars-".$row['id']."'><img src='include/images/wowklein/".$row['klassen'].".gif' border=0></a> ";
		}
		### Ausgabe der Daten.
		$tpl->set_ar_out( $c , 0 );
		### CHARS AUFLISTEN ################################################################################################################

		$sort = ( $menu->get(1) != "" ? "AND a.klassen='".$menu->get(1)." '" : "" );

		$q = $_POST['search'];
		$res = db_query("SELECT 
							a.name, a.rang AS rangid, a.s1, a.s2, a.s3, a.realm, a.user, a.punkte,a.id,
							b.id as klassenid, b.klassen, 
							c.level, 
							d.rang, 
							e.name AS username 
						 FROM prefix_raid_chars AS a 
							LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
							LEFT JOIN prefix_raid_level AS c ON a.level = c.id 
							LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
							LEFT JOIN prefix_user AS e ON a.user = e.id 
						 WHERE 
						 	a.name LIKE '$q%' 
							AND a.rang != 1    
							".$sort." 
						 ORDER BY b.klassen , a.klassen ASC, a.rang DESC, a.name ASC");
						 #AND a.rang != 1
		while( $row = db_fetch_assoc( $res )){
			$klassen = $row['klassen'];
			if( $klassen_to_change != $row['klassen'] ){
				$c_klassen = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE name LIKE '$q%' AND klassen=".$row['klassenid']),0);
				$c_klassen .= "<a name='".$row['klassen']."'></a>";
				$tpl->set_ar_out( array( "klass_name" => $row['klassen'], "COUNT_KLASSEN" => $c_klassen ), 1 );
			}

			### Ausgaben Ändern/Hinzufügen
			$row['ARSENAL'] = "<a href='http://eu.battle.net/wow/de/character/".urlencode(utf8_encode($row['realm']))."/".str_replace(" ", "+",urlencode(utf8_encode($row['name'])))."/advanced' target='_blank'>Arsenal</a>";
			$row['CLASS'] = cssClass($row['CLASS']);
			$row['img'] = "<img src='include/images/wowklein/".$row['klassen'].".gif'>";
			$row['name'] = "<a href='index.php?chars-show-".$row['id']."' name='".$row['id']."'>".$row['name']."</a>";
			### Skillung Auswerten
			$row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['klassenid']);
			### Edit für Chareigentümer
			if( $row['user'] == $_SESSION['authid']){
				$row['edit'] = "<a href='index.php?chars-edit-".$row['id']."'>".$img_edit."</a>";
			}else{
				$row['edit'] = "";
			}
	
			$tpl->set_ar_out( $row, 2 );
			$klassen_to_change = $klassen;
		}
		 
		$tpl->out(3);
		$tpl->out(4);
		break;
}
copyright();
$design->footer();
?>