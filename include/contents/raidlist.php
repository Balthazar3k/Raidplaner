<?php
defined ('main') or die ( 'no direct access' );

require_once("include/includes/func/b3k_func.php");

if( $menu->get(1) == "bossinfos" ){
	echo $raidHeader . '<style type="text/css">
			<!--
			body {
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
				background-color: #000000;
			}
			body,td,th {
				font-family: Arial;
				font-size: 12px;
				color: #FFFFFF;
			}
			-->
		  </style>';
	echo bossinfos($menu->get(2), $menu->get(3));
	exit();
}

$title = $allgAr['title'].' :: Raidplaner';
$hmenu = 'Raidplaner';
$design = new design ( $title , $hmenu );

$design->header();

RaidErrorMsg();
#arrPrint($_SESSION);
switch($menu->get(1)){
	default:
		$tpl = new tpl ('raid/RAID_LIST.htm');
		$tpl->out(7);
			#(SELECT COUNT(aa.id) FROM prefix_raid_anmeldung AS aa WHERE a.id = aa.rid AND aa.user = ".$_SESSION['authid'].") AS regist,
		$sql = "		SELECT 
							a.id, a.inv, a.gruppen AS gid,
						  	IF( a.bosskey>0, a.bosskey, a.id ) AS abid, 
							b.name AS inzen, b.maxbosse,
							c.statusmsg, c.color,
							d.grpsize, 
							g.gruppen AS gname, g.stammgrp AS rechte,
							h.id AS regist,
							(SELECT COUNT(bb.id) FROM prefix_raid_anmeldung AS bb WHERE a.id = bb.rid) AS cAnmeldungen, 
							(SELECT COUNT(cc.id) FROM prefix_raid_anmeldung AS cc WHERE a.id = cc.rid AND cc.stat = 12) AS cZusagen,
							ROUND(((SELECT COUNT(dd.rid) FROM prefix_raid_bosscounter AS dd WHERE abid = dd.rid) * 100 / b.maxbosse)) AS pz  
						FROM prefix_raid_raid AS a 
							LEFT JOIN prefix_raid_inzen AS b ON a.inzen=b.id 
							LEFT JOIN prefix_raid_statusmsg AS c ON a.statusmsg=c.id 
							LEFT JOIN prefix_raid_grpsize AS d ON b.grpsize=d.id 
							LEFT JOIN prefix_raid_stammrechte AS e ON a.stammgrp=e.sid AND e.cid=".( !empty( $_SESSION['charid'] ) ? $_SESSION['charid'] : 0 )." 
							LEFT JOIN prefix_config AS f ON f.schl='canSeeStamm' 
							LEFT JOIN prefix_raid_gruppen AS g ON a.gruppen=g.id
							LEFT JOIN prefix_raid_anmeldung AS h ON a.id=h.rid AND h.user=".$_SESSION['authid']."
						WHERE erstellt>'".date("Y-m-d H:i:s", $_SESSION['lastlogin'])."' AND (f.wert=1 OR a.stammgrp='' OR e.sid = a.stammgrp)";
						
		
		$cRaids = db_num_rows(db_query($sql));
		$limit = $allgAr[ "meps_raid" ];  // Limit 
		$page = ( $menu->getA(3) == 'p' ? escape($menu->getE(3), 'integer') : 1 );
		$MPL = db_make_sites ($page , "" , $limit , "?raidlist-raids-".$menu->get(2) , 'raid_raid', $cRaids);
		$anfang = ($page - 1) * $limit;
			
		$sql .= " LIMIT ".$anfang.",".$limit;
						
		$res = db_query( $sql );
		if( db_num_rows( $res ) != 0 ){
			$i = 0;
			while( $row = db_fetch_object( $res )){
				$i++;
				if( $i == 1 ){ $tpl->set_out("msg", "<div style='color:green; text-align:center;'><b>Neue Raids seit deinem Letzten besuch!</b></div>", 9); }
				$row->class = cssClass($row->class);
				$img = ( !empty($row->regist) ? 'online' : 'offline' );
				$row->img = "<img src='include/images/icons/".$img.".gif'>";
				if( date("d.m.Y", $row->inv) == date("d.m.Y", time()) ){
					$toDayA = "<font color='#00cc00'><b>Heute ";
					$toDayE = "</font>";
				}else{
					$toDayA = $toDayE = "";
				}
				$row->inv = $toDayA . aLink(DateFormat("D d.m.Y H:i", $row->inv),"raidlist-showraid-".$row->id) . $toDayE;
				$row->sName = ( !empty($row->gname) ? "&raquo; ".$row->gname : "" );
				$style = "{ background-color:".$row->color."; width: 150px; border: 1px solid black; padding: 5px; }";
				$row->statusmsg = "<span style='".$style."'><b>".$row->statusmsg."</b> (".$row->pz."%)</span>";
				$row->dkplink = aLink( "<img src='include/images/icons/editor/number.gif' border=0>", "raidlist-dkplist-".$row->gid."--".$row->id);
				$tpl->set_ar_out($row, 8);
			}
		}else{
			$tpl->set_out("msg", "Es gab keine Neuen Raids seit deinem lezten Besuch!", 9);
		}
		
		$tpl->set_out("MPL", $MPL, 10);
		echo "<br />";
		
		$tpl = new tpl ('raid/RAID_LIST.htm');
		
		$tpl->out(0);
		
		$res = db_query("SELECT a.id AS gid, a.gruppen, a.img,   
							c.stammgrp, 
							(SELECT COUNT(ar.id) FROM prefix_raid_raid AS ar WHERE a.id = ar.gruppen AND ar.statusmsg=1) AS aRaids,  
							(SELECT COUNT(er.id) FROM prefix_raid_raid AS er WHERE a.id = er.gruppen) AS eRaids 
 						 FROM prefix_raid_gruppen AS a 
							LEFT JOIN prefix_raid_stammgrp AS c ON a.stammgrp = c.id AND c.id IS NOT NULL 
							LEFT JOIN prefix_raid_stammrechte AS d ON c.id=d.sid AND d.cid=".( !empty( $_SESSION['charid'] ) ? $_SESSION['charid'] : 0 )." 
							LEFT JOIN prefix_config AS e ON e.schl = 'canSeeStamm'
						 WHERE e.wert = 1 OR a.stammgrp='' OR a.stammgrp=d.sid");
						 
		if( db_num_rows( $res ) != 0 ){
			while( $row = db_fetch_object( $res ) ){
				if( $allgAr['show_details_raidgruppen'] OR !is_file("include/raidplaner/images/raidgruppen/".$row->img) ){
					$row->gruppen = aLink( $row->gruppen, "raidlist-raids-".$row->gid);
					$row->dkplink = aLink( "<img src='include/images/icons/editor/number.gif' border=0>", "raidlist-dkplist-".$row->gid);
					$row->stammgrp = ( !empty( $row->stammgrp ) ? "&raquo; ".$row->stammgrp : "" );
					$style = "width:100px;border:1px solid black;vertical-align: middle;";
					$row->aktive = ( $row->aRaids > 0 ? "<div style='background-color:green;".$style."'>Akive Raids</div>":"<div style='background-color:darkred;".$style."'>No Active Raids</div>" );
					$tpl->set_ar_out($row, 1);
				}
				if( $allgAr['show_img_raidgruppen'] AND is_file("include/raidplaner/images/raidgruppen/".$row->img) ){
					$img = getimagesize("include/raidplaner/images/raidgruppen/".$row->img);
					$tpl->set_out("img", aLink("<img ".$img[3]." src='include/raidplaner/images/raidgruppen/".$row->img."' border='0'>", "raidlist-raids-".$row->gid), 2);
				}
				
			}
		}else{
			$tpl->out("msg","Es wurden keine DKP-Gruppen gefunden.", 3);
		}

		$tpl->out(4);
	break;
	case "raids":
		if( isset( $_POST['reset'] ) OR !isset($_SESSION['raidlist']) ){
			$_SESSION['raidlist']['sort'] = 'c.id, a.inv';
			$_SESSION['raidlist']['upordown'] = 'ASC';
			$_SESSION['raidlist']['order'] = "c.id, a.inv ASC ";
			$_SESSION['raidlist']['where'] = '';
			
			wd("index.php?raidlist-raids-".$menu->get(2)."-".$menu->get(3) , "Einen moment Bitte es wird neu Sortiert.", 1);
			$design->footer();
			exit();
		}
		
		if( isset( $_POST['sort'] )){
			$_SESSION['raidlist']['sort'] = $_POST['sort'];
			$_SESSION['raidlist']['upordown'] = $_POST['upordown'];
			$_SESSION['raidlist']['order'] = ( isset($_POST['sort']) ? $_POST['sort']." ".$_POST['upordown'] : "c.id, a.inv ASC " );
			$_SESSION['raidlist']['where'] = $_POST['where'];

			wd("index.php?raidlist-raids-".$menu->get(2)."-".$menu->get(3) , "Einen moment Bitte es wird neu Sortiert.", 1);
			$design->footer();
			exit();
		}
		
		$tpl = new tpl ('raid/RAID_LIST.htm');
		
		class rowler{}
		$row = new rowler();
		$ss = new rowler();
		
		$res = db_query("SELECT 
							a.gruppen, a.img, a.date,
							b.stammgrp, 
							(SELECT COUNT(aa.id) FROM prefix_raid_raid AS aa WHERE a.id=aa.gruppen) AS aRaids, 
							(SELECT COUNT(bb.id) FROM prefix_raid_raid AS bb WHERE a.id=bb.gruppen AND bb.statusmsg=2) AS bRaids, 
							(SELECT SUM(cc.dkp) FROM prefix_raid_dkp AS cc WHERE a.id=cc.dkpgrp AND cc.pm='+') AS pDKP, 
							(SELECT SUM(dd.dkp) FROM prefix_raid_dkp AS dd WHERE a.id=dd.dkpgrp AND dd.pm='-') AS mDKP, 
							(SELECT SUM(ee.dkp) FROM prefix_raid_dkp AS ee WHERE a.id=ee.dkpgrp) AS iDKP, 
							(SELECT COUNT(ff.id) FROM prefix_raid_raid AS ff WHERE ff.statusmsg=2) AS allgRaids, 
								(SELECT 
									SUM(bbb.maxbosse)
								FROM prefix_raid_raid AS aaa 
									LEFT JOIN prefix_raid_inzen AS bbb ON aaa.inzen = bbb.id 
								WHERE a.id = aaa.gruppen 
								) AS amBosse,
							(SELECT COUNT(gg.id) FROM prefix_raid_bosscounter AS gg WHERE a.id=gg.grpid) AS akBosse 
						FROM prefix_raid_gruppen AS a 
							LEFT JOIN prefix_raid_stammgrp AS b ON a.stammgrp = b.id
						WHERE a.id=".$menu->get(2));
		$row = db_fetch_object( $res );
		
		if( $allgAr['show_img_raidgruppen'] AND is_file("include/raidplaner/images/raidgruppen/".$row->img) ){
			$img = getimagesize("include/raidplaner/images/raidgruppen/".$row->img);
			$row->img = "<img src='include/raidplaner/images/raidgruppen/".$row->img."' ".$img[3].">";
		}else{
			$row->img = "<span style='font-size:15px'><b>".$row->gruppen."</b></span>";
		}
		
		$row->date = ( $row->date == '0000-00-00 00:00:00' ? '' : DateFormat("D d.m.Y", DateToTimestamp($row->date)) );
		$title = "<span style='float:left;'>Raids Beendet.</span><span style='float:right;'>".$row->bRaids."/".$row->aRaids." Beendete Raids</span>";
		$row->pzRaids = pzVortschritsAnzeige($row->bRaids,$row->aRaids, $title,2);
		$title = "<span style='float:left;'>Clear Status.</span><span style='float:right;'>".$row->akBosse."/".$row->amBosse." m�glichen Bossen Eliminiert</span>";
		$row->pzBosse = pzVortschritsAnzeige($row->akBosse,$row->amBosse, $title, 2);
		$title = "<span style='float:left;'>Raidleistung Insgesamt.</span>"
		."<span style='float:right;'>".$row->bRaids."/".$row->allgRaids." Raids von ".$row->gruppen."</span>";
		$row->allRaids = pzVortschritsAnzeige($row->bRaids,$row->allgRaids, $title, 2);
		
		$tpl->set_ar_out($row, 5);
			
		$ss->pfad = $ss->reset = "index.php?raidlist-raids-".$menu->get(2)."-".$menu->get(3);
		
		$canSort = array("Optionen"=>"c.id, a.inv",
		 "Angemeldet"=>"regist",
		 "Datum"=>"a.inv",
		 "Instanzen"=>"a.inzen", 
		 "Status"=>"c.id",
		 "Status (%)"=>"pz",
		 "Anmeldungen"=>"cAnmeldungen",
		 "Zusagen"=>"cZusagen");
		 
		 $canUpOrDown = array( "Reihenfolge"=>"ASC", "Aufw�rts (A,B,C)"=>"ASC", "Abw�rts (C,B,A)"=>"DESC" );
		
		foreach( $canSort as $name => $sort ){
			$selected = ( $_SESSION['raidlist']['sort'] == $sort ? 'selected="selected"' : '' );
			$ss->selectA .= "<option value='".$sort."' ".$selected.">".$name."</option>";
		}
		
		foreach( $canUpOrDown as $name => $sort ){
			$selected = ( $_SESSION['raidlist']['upordown'] == $sort ? 'selected="selected"' : '' );
			$ss->selectB .= "<option value='".$sort."' ".$selected.">".$name."</option>";
		}
		
		$resStatus = db_query("SELECT id, statusmsg, color FROM prefix_raid_statusmsg WHERE sid=1");
		$ss->selectC .= "<option value=''>Status</option>";
		while( $row = db_fetch_object( $resStatus ) ){
			$selected = ( $_SESSION['raidlist']['where'] == "AND a.statusmsg=".$row->id ? 'selected="selected"' : '' );
			$style = "style='{ color: white; background-color:".$row->color."; border-color: black; '";
			$ss->selectC .= "<option value='AND a.statusmsg=".$row->id."' ".$style." ".$selected.">".$row->statusmsg."</option>";
		}
		
		$tpl->set_ar_out($ss, 6);
		
		$tpl->out(7);
			#(SELECT COUNT(aa.id) FROM prefix_raid_anmeldung AS aa WHERE a.id = aa.rid AND aa.user = ".$_SESSION['authid'].") AS regist,
		$sql = "		SELECT 
							a.id, a.inv, a.gruppen AS gid,
						  	IF( a.bosskey>0, a.bosskey, a.id ) AS abid, 
							b.name AS inzen, b.maxbosse,
							c.statusmsg, c.color,
							d.grpsize, 
							g.stammgrp AS rechte,
							h.id AS regist,
							i.stammgrp, 
							(SELECT COUNT(bb.id) FROM prefix_raid_anmeldung AS bb WHERE a.id = bb.rid) AS cAnmeldungen, 
							(SELECT COUNT(cc.id) FROM prefix_raid_anmeldung AS cc WHERE a.id = cc.rid AND cc.stat = 12) AS cZusagen,
							ROUND(((SELECT COUNT(dd.rid) FROM prefix_raid_bosscounter AS dd WHERE abid = dd.rid) * 100 / b.maxbosse)) AS pz  
						FROM prefix_raid_raid AS a 
							LEFT JOIN prefix_raid_inzen AS b ON a.inzen=b.id 
							LEFT JOIN prefix_raid_statusmsg AS c ON a.statusmsg=c.id 
							LEFT JOIN prefix_raid_grpsize AS d ON b.grpsize=d.id 
							LEFT JOIN prefix_raid_stammrechte AS e ON a.stammgrp=e.sid AND e.cid=".( !empty( $_SESSION['charid'] ) ? $_SESSION['charid'] : 0 )." 
							LEFT JOIN prefix_config AS f ON f.schl='canSeeStamm' 
							LEFT JOIN prefix_raid_gruppen AS g ON a.gruppen=g.id
							LEFT JOIN prefix_raid_anmeldung AS h ON a.id=h.rid AND h.user=".$_SESSION['authid']."
							LEFT JOIN prefix_raid_stammgrp AS i ON a.stammgrp=i.id 
						WHERE  
							a.gruppen = ".$menu->get(2)." AND 
							(f.wert=1 OR a.stammgrp='' OR e.sid = a.stammgrp) 
							".$_SESSION['raidlist']['where']." 
						ORDER BY ".$_SESSION['raidlist']['order'];
						
		
		$cRaids = db_num_rows(db_query($sql));
		$limit = $allgAr[ "meps_raid" ];  // Limit 
		$page = ( $menu->getA(3) == 'p' ? escape($menu->getE(3), 'integer') : 1 );
		$MPL = db_make_sites ($page , "" , $limit , "?raidlist-raids-".$menu->get(2) , 'raid_raid', $cRaids);
		$anfang = ($page - 1) * $limit;
			
		$sql .= " LIMIT ".$anfang.",".$limit;
						
		$res = db_query( $sql );
		if( db_num_rows( $res ) != 0 ){
			while( $row = db_fetch_object( $res )){
				if( $_SESSION['stammgrp'][$row->rechte] or $allgAr['canSeeStamm'] ){
					$row->class = cssClass($row->class);
					$img = ( !empty($row->regist) ? 'online' : 'offline' );
					$row->img = "<img src='include/images/icons/".$img.".gif'>";
					if( date("d.m.Y", $row->inv) == date("d.m.Y", time()) ){
						$toDayA = "<font color='#00cc00'><b>Heute ";
						$toDayE = "</font>";
					}else{
						$toDayA = $toDayE = "";
					}
					$row->inv = $toDayA . aLink(DateFormat("D d.m.Y H:i", $row->inv),"raidlist-showraid-".$row->id) . $toDayE;
					$row->sName = ( !empty($row->stammgrp) ? "&raquo; ".$row->stammgrp : "" );
					$style = "{ background-color:".$row->color."; width: 100%; border: 1px solid black; padding: 5px; font-size:9px; }";
					$row->statusmsg = "<span style='".$style."'><b>".$row->statusmsg."</b> (".$row->pz."%)</span>";
					$row->dkplink = aLink( "<img src='include/images/icons/editor/number.gif' border=0>", "raidlist-dkplist-".$row->gid."--".$row->id);
					$tpl->set_ar_out($row, 8);
				}else{
					$tpl->set_out("msg", "Du hast hier Leider nicht die N�tigen Rechte!", 9);
					break;
				}
			}
		}else{
			$tpl->set_out("msg", "Es wurden keine Raids gefunden! (evt. keine Raids f�r diese Einstellungen)", 9);
		}
		
		$tpl->set_out("MPL", $MPL, 10);
		$tpl->out(11);
		
	break;
		
	case "anmelden":
		$user_exist = @db_result(db_query("SELECT id FROM prefix_raid_anmeldung WHERE user='".$_SESSION['authid']."' AND rid='".$menu->get(2)."' LIMIT 1"),0);
		if( $user_exist == '' ){
			$sql = 'INSERT INTO `prefix_raid_anmeldung` (`id`, `rid`, `grp`, `char`, `user`, `kom`, `stat`, `timestamp`) 
			VALUES (NULL, 
			\''.$menu->get(2).'\', 
			\''.$menu->get(3).'\', 
			\''.ascape($_POST['char']).'\',
			\''.$_SESSION['authid'].'\', 
			\''.ascape($_POST['kom']).'\', 
			\''.ascape($_POST['stat']).'\', 
			\''.time().'\');';
			
			if( db_query( $sql ) ){
				wd('index.php?raidlist-showraid-'.$menu->get(2),'Du bist nun Angemeldet!');
			}else{
				wd('index.php?raidlist-showraid-'.$menu->get(2),'Fehler beim Anmelden!');
			}
		}else{
			wd('index.php?raidlist-showraid-'.$menu->get(2),'Doppelt Anmeldungen sind nicht M�glich! =�');
		}
	break;
	
	case "edit":
		foreach( $_POST as $key => $value ){
			if( $key != 'button' ){
				db_query("UPDATE prefix_raid_anmeldung SET `".$key."`='".$value."' WHERE id='".$menu->get(3)."'");
			}
		}
		wd('index.php?raidlist-showraid-'.$menu->get(2),'Deine Anmeldung wurde erfolgreich ge�ndert!');
	break;
	 
	case "showraid":
		####################
		### RAID Details ###
		####################
		$tpl = new tpl ('raid/RAIDLIST_RAIDDETAILS.htm');
		$res = db_query( "
		SELECT 
			a.id, a.inv, a.pull, a.ende, a.invsperre, a.txt, a.treff, a.statusmsg AS status_raid_ende, a.gruppen as gid, a.erstellt,
			IF(a.bosskey>0, a.bosskey, a.id) AS bosskey,
			b.id as bid, b.name as inzen, b.grpsize as size, b.maxbosse, b.img, 
			c.gruppen, c.id AS grpid, c.regeln, 
			d.statusmsg, d.color,
			e.name as leader,
			f.loot, 
			i.grpsize, 
			(SELECT COUNT(aa.id) FROM prefix_raid_anmeldung AS aa WHERE aa.rid=a.id AND stat=12) AS gamers 
		FROM prefix_raid_raid AS a 
			LEFT JOIN prefix_raid_inzen AS b ON a.inzen = b.id
			LEFT JOIN prefix_raid_gruppen AS c ON a.gruppen = c.id
			LEFT JOIN prefix_raid_statusmsg AS d ON a.statusmsg = d.id
			LEFT JOIN prefix_raid_chars AS e ON a.leader = e.id
			LEFT JOIN prefix_raid_loot AS f ON a.loot = f.id 
			LEFT JOIN prefix_raid_stammrechte AS g ON a.stammgrp=g.sid AND g.cid='".$_SESSION['charid']."' 
			LEFT JOIN prefix_config AS h ON h.schl='canSeeStamm' 
			LEFT JOIN prefix_raid_grpsize AS i ON b.grpsize=i.id
		WHERE 
			a.id = '".$menu->get(2)."' AND 
			(h.wert=1 OR a.stammgrp='' OR g.sid = a.stammgrp) 
		LIMIT 1
		" );
							
		if( @db_num_rows( $res ) == 0 ){ $tpl->set_out("msg", "Du Hast hier keine Rechte f�r diesen Raid!", 1); $design->footer(); exit(); }
		$row = db_fetch_assoc( $res );
		$row['CLASS'] = cssClass($row['CLASS']);
		### Spieler Anzahl ermitteln
		$row['size'] = db_value( "prefix_raid_grpsize", "grpsize", $row['size'], "");
		### Raid Images Laden und Abfragen
		$img_inis = "include/raidplaner/images/dungeon/".$row['img'];
		if( file_exists( $img_inis )){
			$row['img'] = "<img src='".$img_inis."'>";
		}else{
			$row['img'] = "NoPic";
		}
		$row['death'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter WHERE rid='".$row['bosskey']."'"),0);
		$row['pz'] = pzVortschritsAnzeige($row['death'],$row['maxbosse'],'Raidstatus');
		$row['biinfo'] = "index.php?raidlist-bossinfos-".$row['bid']."-".$row['bosskey'];
		### Datum und Zeit functionen
		$row['date'] = $day . DateFormat("D d.m.Y H:i", $row['inv']);
		$row['date'] .= " min. Anmeld. : ".DateFormat("D d.m.Y H:i", $row['inv'] - ($row['invsperre']*3600)); 
		$row['inv'] = date("H:i", $row['inv']);
		$row['pull'] = date("H:i", $row['pull']);
		$row['ende'] = date("H:i", $row['ende']);
		### Text
		$row['statusmsg'] = "<font color='".$row['color']."'><b>".$row['statusmsg']."</b></font>";
		$row['txt'] = "<b>Mitteilung:</b> ".includer(bbcode($row['txt']));
		$regeln = ( $row['regeln'] != "" ? '<br><a href="index.php?raidlist-'.$menu->get(1).'-'.$menu->get(2).'-regeln">Regeln</a>' : "" );
		$row['regeln'] = ( $menu->get(3)=='regeln' ? "<br><b><a href='javascript:history.back()'>Gruppen Regel</a>: </b>".bbcode($row['regeln']) : $regeln);
		### Datenausgabe
		$row['back'] = "<a href='javascript:history.back()'><img src='include/images/icons/editor/undo.gif' border='0'></a>";
		$row['sdkp'] = aLink("<img src='include/images/icons/editor/number.gif' border='0'>", "raidlist-dkplist-".$row['gid']."--".$row['id']);
		$row['adkp'] = ( user_has_admin_right ($menu,false) ? aLink("<img src='include/images/icons/editor/table.gif' border='0'>", "dkp-".$menu->get(2)."-".$row['gid'], 1) : "<img src='include/images/icons/editor/unknown.gif' border='0'>" );
		$row['edit'] = ( user_has_admin_right ($menu,false) ? aLink("<img src='include/images/icons/editor/spellcheck.gif' border='0'>", "raid-edit-".$menu->get(2), 1) : "<img src='include/images/icons/editor/unknown.gif' border='0'>" );
		$tpl->set_ar_out( $row, 0 );
		### Datenweitergabe
		$grp = $row['grpid'];
		$raid_ende = $row['status_raid_ende'];
		######################
		### User Anmeldung ###
		######################
		$tpl = new tpl ('raid/RAIDLIST_FORM_ANMELDUNG.htm');
		$res = @db_query("SELECT id, `char`, stat, kom FROM prefix_raid_anmeldung WHERE rid='".$menu->get(2)."' AND user='".$_SESSION['authid']."'");
		$res = db_fetch_assoc( $res );
		### Chars Auflisten
		$abf = db_query("SELECT id, name FROM prefix_raid_chars WHERE user='".$_SESSION['authid']."'");
		while( $opt = db_fetch_assoc( $abf )){
			$selected = ( $res['char'] != $opt['id'] ? '' : 'selected' );
			$form['chars'] .= "<option value='".$opt['id']."' ".$selected.">".$opt['name']."</option>\n";
		}
		### Status Auflisten
		$abf = @db_query("SELECT id, statusmsg, sid FROM prefix_raid_statusmsg WHERE sid='2'"); 
		if( $res['stat'] != "" ){
			$opt_sperre = @db_result(@db_query("SELECT sid FROM prefix_raid_statusmsg WHERE id=".$res['stat']),0); ### Akttiviert die IF abfrage #101
		}
		while( $opt = db_fetch_assoc( $abf )){
			### #101 ### Pr�fen ob Raitleiter dem User schon ein Status gegeben hat und in die auswahl hinzuf�gen (Kniffliege sache ^^ mal schauen obs funktioniert ^^)
			if( $opt_sperre == 3 ){
				$raid_stat = "<option value='".$res['stat']."' selected>Raid: Einstellung</option>\n";
				$opt_sperre = ""; ### Deaktiviert die IF Abfrage #101			
			}else{
				$raid_stat = "";
			}
			### Normal Benutzerstatus
			if( $res['stat'] != $opt['id'] ){
				$form['status'] .= $raid_stat . "<option value='".$opt['id']."'>".$opt['statusmsg']."</option>\n";
			}else{
				$form['status'] .= "<option value='".$opt['id']."' selected>".$opt['statusmsg']."</option>\n";
			}
			
		}
		### Ausgabe
		# Daten Abrufen!
		$RaidRes = db_query("SELECT inv, invsperre FROM prefix_raid_raid WHERE id=".$menu->get(2) );
		$RaidInvTime = db_result( $RaidRes , 0, 0);
		$RaidInvSperre = db_result( $RaidRes ,0, 1);
		$isRaidStarted = ( time() > $RaidInvTime ? FALSE : TRUE );
		### Main Chars Daten �berpr�fen
		$exRaidChar = exRaidChar();
		$isRaidKalender = ( $allgAr['isRaidKalender'] == 0 ? TRUE : isRaidKalender() );
		$isRaidSkillung = ( $allgAr['isRaidSkillung'] == 0 ? TRUE : isRaidSkillung() );
		
		if( $isRaidStarted ){
			if( $res['id'] != NULL ){
				$form['title'] = "Anmeldung Bearbeiten";
				$form['smsg'] = "Hier kannst du deine Anmeldung Bearbeiten";
				$form['kom'] = $res['kom'];
				$form['button'] = "�ndern";
				$form['pfad'] = "index.php?raidlist-edit-".$menu->get(2)."-".$res['id'];
	
				$tpl->set_ar_out( $form, 0 );
			}else{
				### Raid Mindest anmelde Zeit �berpr�fung!
				if( time() <= $RaidInvTime - ($RaidInvSperre*3600) ){
					$RaidStatus = TRUE;
				}else{
					$RaidStatus = FALSE;
					echo ( exRaidChar() ? "<center>Mindestanmeldezeit von ".$RaidInvSperre."Std. wurde erreicht!</center>" : "");
				}
				
				### Normale Ausgabe
				if( $RaidStatus && $exRaidChar && $isRaidKalender && $isRaidSkillung ){			
					$form['title'] = "Anmelden";
					$form['smsg'] = "Hier kannst du dich Anmelden!";
					$form['kom'] = "";
					$form['button'] = "Anmelden";
					$form['pfad'] = "index.php?raidlist-anmelden-".$menu->get(2)."-".$grp;
			
					$tpl->set_ar_out( $form, 0 );
				}else{
					echo ( loggedin() ? "<center><b><font color='red'>Anmeldung Geschlossen/Gespert!</font></b></center><br>" : "" );
				}
			}
		}
		##################################
		### Angemeldet Chars auflisten ###
		##################################
		$tpl = new tpl ('raid/RAIDLIST_ANMELDUNGEN.htm');
		$tpl->out( 0 );
		$sql = db_query( "SELECT 
					a.kom, a.stat, a.timestamp,					
					b.name, b.id as cid, b.s1, b.s2, b.s3,
					c.id as kid, c.klassen, 
					d.level, 
					e.id as rid,
					f.statusmsg, f.color, 
					SUM(dkp) as DKP 
				FROM prefix_raid_anmeldung AS a 
					LEFT JOIN prefix_raid_chars AS b ON a.char = b.id 
					LEFT JOIN prefix_raid_klassen AS c ON b.klassen = c.id  
					LEFT JOIN prefix_raid_level AS d ON b.level = d.id 
					LEFT JOIN prefix_raid_raid AS e ON a.rid = e.id 
					LEFT JOIN prefix_raid_statusmsg AS f ON a.stat = f.id 
					LEFT JOIN prefix_raid_dkp AS g ON b.id = g.cid AND e.gruppen=g.dkpgrp 
				WHERE 
					a.rid = '".$menu->get(2)."' 
				GROUP BY b.name 
				ORDER BY a.stat ASC, DKP DESC, c.id DESC");
		if( db_num_rows( $sql ) != 0 ){
			while( $row = db_fetch_assoc( $sql )){
				$Class = cssClass($Class);
				$row["CLASS"] = $Class;
				$row['img'] = class_img($row['klassen']);
				$row['name'] = "<a href='index.php?raidlist-verlauf-".$row['rid']."-".$row['cid']."' title='".$name_title."'>".$row['name']."</a>";
				$row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['kid']);
				$tpl->set_ar_out( $row, 1 );
			}
		}else{
			$tpl->out( 2 );
		}
		$tpl->out( 3 );
	break;
	case "verlauf":
		###############
		### VERLAUF ###
		###############
		$rID = $menu->get(2);
		$charID = $menu->get(3);
		
		$tpl = new tpl ('raid/DKPLISTE_DKPVERLAUF_CHAR.htm');
		$tpl->set_out("title","Verlauf f�r diesen Raid", 0);
		$res = db_query("SELECT info, dkp, date FROM prefix_raid_dkp WHERE rid = '".$rID."' AND cid = '".$charID."'"); 
		while( $row = db_fetch_assoc( $res )){
			$row['CLASS'] = cssClass($row['CLASS']);
			$row['info'] = RaidItems($row['info'], $menu->get(0) );
			$row["date"] = date("H:i", $row["date"]);
			$tpl->set_ar_out( $row , 1);
		}
		$bekomm = db_result(db_query("SELECT SUM(dkp) FROM prefix_raid_dkp WHERE dkp NOT LIKE '-%' AND rid=".$rID." AND cid=".$charID),0);
		$ausgabe = db_result(db_query("SELECT SUM(dkp) FROM prefix_raid_dkp WHERE dkp LIKE '-%' AND rid=".$rID." AND cid=".$charID),0);
		$gesamt = db_result(db_query("SELECT SUM(dkp) FROM prefix_raid_dkp WHERE rid=".$rID." AND cid=".$charID),0);
		$tpl->set_ar_out( array("msg"=>"Deine in diesem Raid bekommenen DKP", "dkp"=> $bekomm), 2);
		$tpl->set_ar_out( array("msg"=>"Deine in diesem Raid ausgegebene DKP", "dkp"=> $ausgabe), 2);
		$tpl->set_ar_out( array("msg"=>"Gesamt Verlust oder Gewin in diesem Raid", "dkp"=> $gesamt), 2);
		$tpl->set_ar_out( array("link"=> "index.php?raidlist-verlauf-".$menu->get(2)."-".$menu->get(3).""), 3);
	break;
	case "dkplist":
		#######################
		### DKPLISTE GESAMT ###
		#######################
		button("Zur�ck","", 8);
		button("Aktualisieren","", 10);
		$tpl = new tpl ('raid/RAIDLIST_DKPLIST.htm');
		$row['gruppen'] = db_result(db_query("SELECT gruppen FROM prefix_raid_gruppen WHERE id='".$menu->get(2)."'"),0);
	    $row['maxraids'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen='".$menu->get(2)."'"),0);
		$row['endraids'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen='".$menu->get(2)."' AND statusmsg=2"),0);
		$row['erfraid'] = pzVortschritsAnzeige($row['endraids'], $row['maxraids']);
		$row['bosskill'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter WHERE grpid='".$menu->get(2)."'"),0);
		$row['gdkp'] = db_result(db_query("SELECT SUM(dkp) as gdkp FROM prefix_raid_dkp WHERE dkpgrp='".$menu->get(2)."'"),0);
		### Filter
		$klassen .= '<option value="index.php?raidlist-dkplist-'.$menu->get(2).'--'.$menu->get(4).'">alle Anzeigen</option>\n';
		$res = db_query("SELECT id, klassen FROM prefix_raid_klassen ORDER BY id DESC");
		while( $k = db_fetch_assoc( $res )){
			$select = ( $menu->get(3) == $k['id'] ? 'selectet' : '' );
			$klassen .= '<option value="index.php?raidlist-dkplist-'.$menu->get(2).'-'.$k['id'].'-'.$menu->get(4).'"'.$select.'>'.$k['klassen'].'</option>\n';
		}
		$klassen .= '<option value="index.php?raidlist-dkplist-'.$menu->get(2).'-dkp-'.$menu->get(4).'">SET: Druide, Krieger, Priester</option>\n';
		$klassen .= '<option value="index.php?raidlist-dkplist-'.$menu->get(2).'-mhj-'.$menu->get(4).'">SET: Magier, Hexenmeister, J�ger</option>\n';
		$klassen .= '<option value="index.php?raidlist-dkplist-'.$menu->get(2).'-pss-'.$menu->get(4).'">SET: Paladin, Schurke, Schamane</option>\n';
		$row['list_klassen_dkp'] = $klassen;
		$tpl->set_ar_out( $row ,0);
		
		#$sql = 'SELECT a.id, a.name, c.klassen, SUM( b.dkp ) AS adkp 
		 #FROM prefix_raid_chars AS a , prefix_raid_dkp AS b, prefix_raid_klassen AS c WHERE
		 #b.dkpgrp = '.$menu->get(2).' AND a.id = b.cid AND a.klassen=c.id 
		 #GROUP BY a.name 
		 #ORDER BY `adkp` DESC';
		
		switch( $menu->get(3) ){
			case "dkp": # DRUIDE, KRIEGER, PRIESTER
				$sql = 'SELECT a.id, a.name , c.klassen, SUM( b.dkp ) AS adkp 
				 FROM prefix_raid_chars AS a , prefix_raid_dkp AS b, prefix_raid_klassen AS c  WHERE 
				 a.klassen IN( 10, 3, 5 ) AND b.dkpgrp = '.$menu->get(2).' AND  a.id = b.cid AND a.klassen=c.id  
				 GROUP BY a.name 
				 ORDER BY `adkp` DESC';
			break;
			case "mhj": # MAGIER, J�GER, HEXENMEISTER
				$sql = 'SELECT a.id, a.name , c.klassen, SUM( b.dkp ) AS adkp 
				 FROM prefix_raid_chars AS a , prefix_raid_dkp AS b, prefix_raid_klassen AS c  WHERE 
				 a.klassen IN( 4, 8, 9 ) AND b.dkpgrp = '.$menu->get(2).' AND  a.id = b.cid AND a.klassen=c.id   
				 GROUP BY a.name 
				 ORDER BY `adkp` DESC';
			break;
			case "pss": # PLADIN, SCHURKE, SCHAMANE
				$sql = 'SELECT a.id, a.name , c.klassen, SUM( b.dkp ) AS adkp 
				 FROM prefix_raid_chars AS a , prefix_raid_dkp AS b, prefix_raid_klassen AS c  WHERE 
				 a.klassen IN( 2, 6, 7 ) AND b.dkpgrp = '.$menu->get(2).' AND  a.id = b.cid AND a.klassen=c.id  
				 GROUP BY a.name 
				 ORDER BY `adkp` DESC';
			break;
			default:
				if( $menu->get(3) != "" ){
					$klas = 'AND a.klassen=\''.$menu->get(3).'\'';
				}
				$sql = 'SELECT 
				 	a.id, a.name, 
				 	c.klassen, SUM( b.dkp ) AS adkp 
				 FROM prefix_raid_chars AS a 
				 	LEFT JOIN prefix_raid_dkp AS b ON a.id = b.cid 
				 	LEFT JOIN prefix_raid_klassen AS c ON a.klassen=c.id  
				 WHERE b.dkpgrp = '.$menu->get(2).' '.$klas.'  
				 GROUP BY a.name 
				 ORDER BY `adkp` DESC';
			break;
		}
 
		$res = db_query($sql);
		$i = 1;
		while( $row = db_fetch_assoc( $res )){
		
			$playedCharId = ( $menu->get(4) == NULL ? '' : @db_result(db_query('SELECT `char` FROM `prefix_raid_anmeldung` WHERE `char`='.$row['id'].' AND `stat`=12 AND `rid`='.$menu->get(4) ),0) );
			$count_all_raids = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen='".$menu->get(2)."' AND statusmsg='2'"),0);
			$count_raids = db_result(db_query("SELECT COUNT(a.id) FROM prefix_raid_anmeldung AS a, prefix_raid_raid AS b WHERE a.rid = b.id AND `stat`=12 AND `char`=".$row['id']." AND `grp`=".$menu->get(2)." AND b.statusmsg=2"),0);
			
			$row['CLASS'] = cssClass($row['CLASS']);
			$row['nr'] = $i;
			$row['img'] = class_img($row['klassen']);
			$row['name'] = "<a href='index.php?chars-show-".$row['id']."' title='Char Details'>".$row['name']."</a>";
			$row['pz'] = pzVortschritsAnzeige($count_raids, $count_all_raids, 0);
			if( $playedCharId == $row['id'] && $menu->get(4) != NULL ){
				$tpl->set_ar_out( $row, 1 );
				$i++;
			}elseif( $menu->get(4) == NULL ){
				$tpl->set_ar_out( $row, 1 );
				$i++;
			}
		}
		$tpl->out(2);
	break;
}
###########
copyright();
$design->footer();
?>
