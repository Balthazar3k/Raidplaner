<?php 
require_once("include/includes/func/b3k_func.php");

if( !RaidPermission(0, TRUE) ){ echo 'don\'t Permission'; $design->footer(); exit(); }

$cssPfad = 'include/admin/templates/';
$cssFile = 'style.css';

function groupinfos($cid,$id){
	$sql = "SELECT
				a.id AS cid,
				a.user AS uid,
				c.id AS grid, 
				b.stammgrp AS grname,
				d.uid AS guid, d.gid AS ggid, d.fid AS gfid 
			FROM prefix_raid_chars AS a 
				LEFT JOIN prefix_raid_stammgrp AS b ON b.id='".$id."'
				LEFT JOIN prefix_groups AS c ON b.stammgrp=c.name
				LEFT JOIN prefix_groupusers AS d ON c.id=d.gid AND a.user=d.uid
			WHERE a.id=".$cid
			;
	$res = db_query( $sql );
	return db_fetch_object( $res );
}

$tpl = new tpl ( 'raid/chars.htm',1 );
$table = new tpl ( 'raid/2zeilen4spalten.htm',1 );

switch($menu->get(1)){
	
	case "editrang":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		
		if(is_admin() or $_SESSION['charrang'] >= $allgAr['char_rang_edit'] and $_SESSION['charrang'] >= $row['rangid'] ){
			setModulrightsForCharRang($menu->get(2),'remove');
			if( db_query("UPDATE prefix_raid_chars SET rang='".$menu->get(3)."' WHERE id='".$menu->get(2)."'") ){
				setModulrightsForCharRang($menu->get(2),'insert');
				wd('admin.php?chars-'.$menu->get(4),'Rang wurde erfolgreich geändert!', 0);
			}else{
				wd('admin.php?chars-'.$menu->get(4),'Rang wurde <b>nicht</b> erfolgreich geändert!');
			}
		}else{
			wd('admin.php?chars-'.$menu->get(4),'no Permission!');	
		}
	break;
	
	case "edit":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		
		setModulrightsForCharRang($_POST['id'],'remove');
		setModulrightsForCharRang($_POST['id'],'insert');
		
		arrPrint( $_POST );
		
		if( db_query("UPDATE prefix_raid_chars SET 
		`name` = '".ascape( $_POST['name'])."', 
		`rang` = '".ascape( $_POST['rang'])."', 
		`level` = '".ascape( $_POST['level'])."', 
		`klassen` = '".ascape( $_POST['klassen'])."',
		`rassen` = '".ascape( $_POST['rassen'])."', 
		`s1` = '".ascape( $_POST['s1'])."', 
		`s2` = '".ascape( $_POST['s2'])."', 
		`s3` = '".ascape( $_POST['s3'])."', 
		`skillgruppe` = '".ascape( $_POST['skillgruppe'])."', 
		`mberuf` = '".ascape( $_POST['mberuf'])."', 
		`mskill` = '".ascape( $_POST['mskill'])."', 
		`sberuf` = '".ascape( $_POST['sberuf'])."', 
		`sskill` = '".ascape( $_POST['sskill'])."', 
		`warum` = '".ascape( $_POST['warum'])."', 
		`pvp` = '".ascape( $_POST['pvp'])."', 
		`raiden` = '".ascape( $_POST['raiden'])."', 
		`teamspeak` = '".$_POST['teamspeak']."', 
		`realm` = '".ascape( $_POST['realm'])."' 
		WHERE id=".$_POST['id']) ){
			wd( "admin.php?chars-details-".$_POST['id'], "Speichern war Erfolgreich!" );
		}else{
			wd( "admin.php?chars-details-".$_POST['id'], "Speichern war <b>nicht</b> Erfolgreich!" );
		}
	break;
	
	case "add":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
	
		$chk = "name=is,rang=int,level=int,klassen=int,rassen=int";
		if( arrDataCheck( $_POST, $chk, 0 ) ){
			if( @db_query( "INSERT INTO prefix_raid_chars (`name`, `rang`, `level`, `klassen`, `rassen`, `s1`, `s2`, `s3`, `skillgruppe`, `mberuf`, `mskill`, `sberuf`, `sskill`, `realm`, `user`, `warum`, `raiden`, `pvp`, `teamspeak`) VALUES(
			'".ascape( $_POST['name'] )."', 
			'".ascape( $_POST['rang'] )."', 
			'".ascape( $_POST['level'] )."', 
			'".ascape( $_POST['klassen'] )."',
			'".ascape( $_POST['rassen'] )."', 
			'".ascape( $_POST['s1'] )."', 
			'".ascape( $_POST['s2'] )."', 
			'".ascape( $_POST['s3'] )."', 
			'".ascape( $_POST['skillgruppe'] )."', 
			'".ascape( $_POST['mberuf'] )."', 
			'".ascape( $_POST['sskill'] )."', 
			'".ascape( $_POST['sberuf'] )."', 
			'".ascape( $_POST['sskill'] )."', 
			'".ascape( $_POST['realm'] )."', 
			'".ascape( $_POST['id'] )."',
			'".ascape( $_POST['warum'] )."',
			'".ascape( $_POST['raiden'] )."',
			'".ascape( $_POST['pvp'] )."',
			'".ascape( $_POST['teamspeak'] )."');" ) ){
				echo "<center>Char wurde unter deinen Username/id Gespeichert! ";
				button("Fenster Schließen","javascript:window.close();", 1);
				echo "</center>";
			}else{
				echo "<center>Char konnte <b>nicht</b> Gespeichert werden! ";
				button("Zurück","", 8);
				echo "</center>";
			}
		}else{
			echo arrDataCheck( $_POST, $chk, 1 );
		}
	break;
	
	case "del":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
	
		$char_name = db_result(db_query('SELECT name FROM prefix_raid_chars WHERE id='.$menu->get(2) ),0);
		if(   $menu->get(3) != "true" ){
			echo "<center><table border=0 cellpadding=5 cellspacing=1 class=border><tr class=Cnorm><td algin='center'>";
			echo "<b><font  color=red>Wirklich alle daten von \"".$char_name."\" Löschen? ( dkp, anmeldungen, kalender )";
			echo "[ <a href='admin.php?".$_SERVER['QUERY_STRING']."-true'>Ja</a> | <a href='admin.php?chars'>Nein</a> ]</font></b>";
			echo "</td></tr></table></center>";
		}
		
		if( RaidPermission( 0, TRUE) && $menu->get(3) == "true" ){
			if( db_query("DELETE FROM prefix_raid_chars WHERE id = '".$menu->get(2)."' LIMIT 1") &&
			db_query("DELETE FROM prefix_raid_dkp WHERE cid = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_kalender WHERE cid = '".$menu->get(2)."'") &&
			db_query("DELETE FROM prefix_raid_anmeldung WHERE `char` = '".$menu->get(2)."'") ){
				wd('admin.php?chars',$char_name.' wurde erfolgreich gelöscht!', 0);
			}else{
				wd('admin.php?chars',$char_name.' wurde <b>nicht</b> erfolgreich gelöscht!', 3);
			}
		}
	break;
	
	case "addtostamm":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		$ginfo = groupinfos($menu->get(2),$_POST['stammgrp']);
		$count = db_result(db_query("SELECT COUNT(cid) FROM prefix_raid_stammrechte WHERE cid=".$menu->get(2)." AND sid=".$_POST['stammgrp']),0);
		if( $count == 0 ){
		db_query("INSERT INTO prefix_raid_stammrechte (cid, sid, eid) 
				VALUES('".ascape($menu->get(2))."','".ascape($_POST['stammgrp'])."','".ascape($_SESSION['charid'])."');");
		db_query("INSERT INTO `prefix_groupusers`(`uid`, `gid`, `fid`) VALUES('".$ginfo->uid."','".$ginfo->grid."','0');");
			wd('admin.php?chars-details-'.$menu->get(2),'Ist nun in einer Stamm Gruppe', 3);
		}else{
			wd('admin.php?chars-details-'.$menu->get(2),'Char ist bereits Mitglied dieser Gruppe!', 3);
		}
	break;
	
	case "delfromstamm":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		$ginfo = groupinfos($menu->get(2),$menu->get(3));
		if( db_query("DELETE FROM prefix_raid_stammrechte WHERE cid=".$menu->get(2)." AND sid=".$menu->get(3)."")
			and db_query("DELETE FROM prefix_groupusers WHERE uid=".$ginfo->guid." AND gid=".$ginfo->ggid) ){
			wd('admin.php?chars-details-'.$menu->get(2),'Wurde erfolgreich Gelöscht', 3);
		}else{
			wd('admin.php?chars-details-'.$menu->get(2),'Wurde <b>nicht</b> erfolgreich Gelöscht', 3);
		}
	break;
	
	default:
		defined ('main') or die ( 'no direct access' );
		defined ('admin') or die ( 'only admin access' );
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		$design->addheader($raidHeader);
		$design->header();
		
		RaidErrorMsg();
		aRaidMenu();
		
		#$classen = array();
		##### SUCHEN
		if( $_SESSION['authmod']['CharsEditKlassen'] != 1 ){
			$res = db_query("SELECT id, klassen FROM prefix_raid_klassen ORDER BY id DESC");
			while( $row = db_fetch_object( $res ) ){
				$classImg .= '<input type="image" name="klassen['.$row->id.']" src="include/images/wowklein/'.$row->klassen.'.gif" /> ';
				#$classen[$row->id] = $row->klassen;
			}
		
			$tpl->set_out("srcClass",$classImg,0); ### SUCHEN
			
			button("NewChar", "javascript:creatWindow( \"admin.php?chars-new\", \"NewChar\", \"517\", \"350\" );", 12);
			button("CharChangeAccount", "javascript:creatWindow( \"admin.php?extern-CharChangeAccount\", \"CharChangeAccount\", \"500\", \"40\" );", 12);
		}
				
		$search = ( isset( $_POST['search'] ) ? 'WHERE '. $_POST['from'].' LIKE \''.$_POST['search'].'%\' ' : '' );
		
		if( isset( $_POST['klassen'] ) ){
			$key = array_keys( $_POST['klassen'] );
			$search .= "AND b.id = ".$key[0]." ";
		}
		
		$tpl->out(1);
		$sql = "SELECT 
					a.id, a.name, a.user, a.regist, a.s1, a.s2, a.s3, 
					b.id as kid, b.klassen, 
					c.id as rid, c.rang, 
					d.id as uid, d.name as uname, d.gebdatum, 
					e.level 
				FROM prefix_raid_chars AS a 
					LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
					LEFT JOIN prefix_raid_rang AS c ON a.rang = c.id
					LEFT JOIN prefix_user AS d ON a.user = d.id 
					LEFT JOIN prefix_raid_level AS e ON a.level = e.id 
				".$search."
				".( $_SESSION['authmod']['CharsEditKlassen'] == 1  ? 'WHERE b.id = '.$_SESSION['charklasse'].' ' : '' )."
				ORDER BY c.id DESC, b.id DESC ";
				
				$limit = 18;  // Limit
				$page = ( $menu->getA(1) == 'p' ? escape($menu->getE(1), 'integer') : 1 );
				$MPL = db_make_sites ($page , '' , $limit , "?chars" , 'raid_chars', db_num_rows(db_query($sql)) );
				$anfang = ($page - 1) * $limit;
				
				$sql .= "LIMIT ".$anfang.", ".$limit;
				
		$res = db_query($sql);
		
		if( db_num_rows( $res ) != 0 ){
			while( $row = db_fetch_object( $res )){
				$aRang = $row->rang;
				if( $row->rang != $cRang ){
					$t->class = 'Cdark';
					$t->msg = "<b>".$row->rang."</b>";
					$tpl->set_ar_out( $t , 3);
				}
				$row->class = cssClass( $row->class );
				$row->img = class_img($row->klassen);
				$row->name = aLink( $row->name, "chars-details-".$row->id, 1);
				$row->geb = ( $row->gebdatum != "0000-00-00" ? "[".alter($row->gebdatum)."]" : '');
				$row->uname = aLink( $row->uname, "user-1-".$row->uid , 1);
				if( is_admin() 
					or $_SESSION['charrang'] >= $allgAr['char_rang_edit'] 
					and $_SESSION['charrang'] >= $row->rid 
					and $_SESSION['charid'] != $row->id
					and $_SESSION['authid'] != $row->user)
				{
					$select = '<select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu(\'parent\',this,0)">';
						if( is_admin() ){
							$erg = db_query("SELECT id, rang FROM prefix_raid_rang");
						}else{
							$erg = db_query("SELECT id, rang FROM prefix_raid_rang WHERE id <=".($_SESSION['charrang'] - 1));
						}
					while( $mm = db_fetch_object( $erg )){
						$sel = ( $row->rid == $mm->id ? ' selected' : '' );
						$select .= '<option value="admin.php?chars-editrang-'.$row->id.'-'.$mm->id.'-'.$menu->get(1).'" '.$sel.'>'.$mm->rang.'</option>\n';
					}
					$select .= '</select>';
					$row->rang = $select;
				}else{
					$row->rang = $row->rang;
				} ## Rang änderungen!
				$row->skill = char_skill($row->s1, $row->s2, $row->s3, $row->kid);
				$row->regist = DateToTimestamp($row->regist);
				$row->regist = DateFormat("D d.m.Y H:i:s", $row->regist, 1) ."  ". agoTimeMsg($row->regist);
				if( is_admin() 
					or $_SESSION['charrang'] >= $allgAr['char_rang_edit'] 
					and $_SESSION['charrang'] >= $row->rid
					and $_SESSION['authmod']['CharsEditKlassen'] != 1)
				{
					$row->edit = aLink( '<img src="include/images/icons/edit.gif">', "chars-details-".$row->id, 1);
					$row->del = aLink( '<img src="include/images/icons/del.gif">', "chars-del-".$row->id, 1);
				}else{
					$row->edit = aLink( '<img src="include/images/icons/edit.gif">', "chars-details-".$row->id, 1);
					$row->del = '';
				}
				$tpl->set_ar_out($row, 2);
				$cRang = $aRang;
			}
		}else{
			$t->class = 'Cnorm';
			$t->msg = "Es wurde kein Char gefunden!";
			$tpl->set_ar_out( $t , 3);
		}
		$tpl->set_out( "MPL", $MPL ,4);	
		
		copyright();
		
		$design->footer();
	break;
	
	case "details":
		## INHALT für TD1
		defined ('main') or die ( 'no direct access' );
		defined ('admin') or die ( 'only admin access' );
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		$design->addheader($raidHeader);
		$design->header();
		
		RaidErrorMsg();
		aRaidMenu();
		
		button("Zurück", "admin.php?chars", 0);
		
		$table->out(0); #Table bis TD 1
		
		$res = db_query("SELECT 
							a.id, a.name, a.user, a.rang, a.level, a.klassen, a.rassen,
							a.s1, a.s2, a.s3, a.skillgruppe, a.warum, a.pvp, a.raiden,
							a.mberuf, a.mskill, a.sberuf, a.sskill, a.teamspeak, 
							a.realm, 
							b.rang as rangname 
						FROM prefix_raid_chars AS a 
							LEFT JOIN prefix_raid_rang AS b ON a.rang=b.id 
						WHERE
						a.id = ".$menu->get(2)." 
						LIMIT 1");
						
		$c = db_fetch_object( $res );
		
		$c->editPath = "admin.php?chars-edit";
		$c->title = "Edit Char:" .$c->name;
		$hidden1 = "<input type='hidden' name='rang' value='".$c->rang."'>";
		$c->rang = ( $_SESSION['authid'] != $c->user ? drop_down_menu("prefix_raid_rang" , "rang", $c->rang, "") : $c->rangname.$hidden1 );
		$c->level = drop_down_menu("prefix_raid_level" , "level", $c->level, "");
		$c->klassen = drop_down_menu("prefix_raid_klassen" , "klassen", $c->klassen, "");
		$c->rassen = drop_down_menu("prefix_raid_rassen" , "rassen", $c->rassen, "");
		$c->skillgruppe = skillgruppe( 1, $c->skillgruppe );
		$c->mberuf = drop_down_menu("prefix_raid_berufe" , "mberuf", $c->mberuf, "");
		$c->sberuf = drop_down_menu("prefix_raid_berufe" , "sberuf", $c->sberuf, "");
		$c->tsy = ( $c->teamspeak = 1 ? 'checked="checked"' : '' );
		$c->tsn = ( $c->teamspeak = 0 ? 'checked="checked"' : '' );
		
		$tpl->set_ar_out($c, 5);
		
		$table->out(1); ## SCHLIE?T TD1 öffnet TD2
		$stamm->pfad = "admin.php?chars-addtostamm-".$menu->get(2);
		$stamm->stammgrp = drop_down_menu("prefix_raid_stammgrp" , "stammgrp", "", "");
		$tpl->set_ar_out($stamm,6);
		
		$res = db_query("SELECT 
							a.cid, a.sid, a.date, 
							b.name, 
							c.stammgrp 
						FROM prefix_raid_stammrechte AS a
							LEFT JOIN prefix_raid_chars AS b ON a.eid=b.id 
							LEFT JOIN prefix_raid_stammgrp AS c ON a.sid=c.id 
						WHERE a.cid=".$menu->get(2)."
						ORDER BY a.sid ASC");
		
		if( db_num_rows( $res ) != 0 ){
			while( $row = db_fetch_object( $res )){
				$row->del = aLink("<img src='include/images/icons/del.gif'>","chars-delfromstamm-".$row->cid."-".$row->sid, 1);
				$tpl->set_ar_out($row, 7);
			}
		}else{
			$tpl->set_out("msg","Ist in keiner Stamm Gruppe Mitglied!",8);
		}
		
		
		
		$tpl->out(9);
		$table->out(4); ###
		copyright();
		
		$design->footer();
	break;
	
	case "new":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
	
		$c->editPath = "admin.php?chars-add";
		$c->title = "Neuen Char Anlegen!";
		
		$c->name = '';
		$c->s1 = $c->s2 = $c->s3 = '';
		$c->id = $_SESSION['authid'];
		$c->rang = drop_down_menu("prefix_raid_rang" , "rang", 1, "");
		$c->level = drop_down_menu("prefix_raid_level" , "level", 1, "");
		$c->klassen = drop_down_menu("prefix_raid_klassen" , "klassen", 2, "");
		$c->rassen = drop_down_menu("prefix_raid_rassen" , "rassen", 1, "");
		$c->skillgruppe = skillgruppe( 1 );
		$c->mberuf = drop_down_menu("prefix_raid_berufe" , "mberuf", 0, "");
		$c->sberuf = drop_down_menu("prefix_raid_berufe" , "sberuf", 0, "");
		$c->mskill = $c->sskill = $c->warum = $c->pvp = $c->raiden = $c->teamspeak = '';
		$c->realm = $allgAr['realm'];
		
		$tpl->set_ar_out($c, 5);
		
		copyright();
	break;
}

?>