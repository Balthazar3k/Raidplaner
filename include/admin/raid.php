<?php
require_once("include/includes/func/b3k_func.php");

defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

# DESIGN
$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

RaidErrorMsg();
aRaidMenu();

echo "<br>";


$tpl = new tpl ( 'raid/raid.htm',1 );


switch($menu->get(1)){
	case "add":
		list($jahr, $monat, $tag ) = explode( "-", $_POST['begind'] );
		$_POST['inv'] = mktime( $_POST['istd'], $_POST['imin'], 0, $monat, $tag, $jahr ); 
		$_POST['pull'] = mktime( $_POST['pstd'], $_POST['pmin'], 0, $monat, $tag, $jahr );
		$_POST['ende'] = mktime( $_POST['estd'], $_POST['emin'], 0, $monat, $tag, $jahr );
			
		db_query("INSERT INTO `prefix_raid_raid` (`id` ,`statusmsg` ,`leader` ,`gruppen`,`stammgrp` ,`inzen` ,`treff` ,`loot` ,`inv` ,`pull` ,`ende`, `invsperre`, `txt`,`von`, `bosskey` )
		VALUES ( NULL , '".
		$_POST['statusmsg']."', '".
		$_POST['leader']."', '".
		$_POST['gruppen']."', '".
		$_POST['stammgrp']."', '".
		$_POST['inzen']."', '".
		$_POST['treff']."', '".
		$_POST['loot']."', '".
		$_POST['inv']."', '".
		$_POST['pull']."', '".
		$_POST['ende']."', '".
		$_POST['invsperre']."', '".
		ascape($_POST['txt'])."', '".
		$_SESSION['authid']."', '".
		$_POST['bosskey']."');");
		wd('admin.php?raid','Neuer eintrag war erfolgreich!', 0);
	break;
	case "editsave":
		if( !RaidPermission( $menu->get(2) ) ){ $design->footer(); exit('don\'t Permission<br>'. $allFalse); }
		list($jahr, $monat, $tag ) = explode( "-", $_POST['begind'] );
		$_POST['inv'] = mktime( $_POST['istd'], $_POST['imin'], 0, $monat, $tag, $jahr ); 
		$_POST['pull'] = mktime( $_POST['pstd'], $_POST['pmin'], 0, $monat, $tag, $jahr );
		$_POST['ende'] = mktime( $_POST['estd'], $_POST['emin'], 0, $monat, $tag, $jahr );
		
		if(db_query("UPDATE prefix_raid_raid SET 
		`statusmsg`='".$_POST['statusmsg']."', 
		`leader`='".$_POST['leader']."', 
		`gruppen`='".$_POST['gruppen']."', 
		`stammgrp`='".$_POST['stammgrp']."', 
		`inzen`='".$_POST['inzen']."', 
		`treff`='".$_POST['treff']."', 
		`loot`='".$_POST['loot']."', 
		`inv`='".$_POST['inv']."', 
		`pull`='".$_POST['pull']."', 
		`ende`='".$_POST['ende']."', 
		`invsperre`='".$_POST['invsperre']."', 
		`txt`='".$_POST['txt']."', 
		`bosskey`='".$_POST['bosskey']."' 
		WHERE `id`=".$menu->get(2)) ){
			wd('admin.php?raid','Update war erfolgreich!', 0);
		}else{
			wd('admin.php?raid','Update war nicht erfolgreich!', 10);
		}
	break;
	case "del":
		if( RaidPermission() ){
			db_query("DELETE FROM prefix_raid_raid WHERE id = '".$menu->get(2)."'");
			db_query("DELETE FROM prefix_raid_anmeldung WHERE rid = '".$menu->get(2)."'");
			db_query("DELETE FROM prefix_raid_dkp WHERE rid = '".$menu->get(2)."'"); 
			wd('admin.php?raid','Löschen war erfolgreich!');
		}else{
			$design->footer();
			exit('don\'t Premission<br>'. $allFalse);
		}
	break;
	case "edit":
		$db = "prefix_raid_raid";
		$res = db_query(" SELECT * FROM prefix_raid_raid WHERE id = '".$menu->get(2)."'");
		$row = mysql_fetch_array( $res );
		$row['PFAD'] = "admin.php?raid-editsave-".$menu->get(2);
		$row['status'] = drop_down_menu("SELECT id, statusmsg FROM prefix_raid_statusmsg WHERE  sid='1'", "statusmsg",  db_value( $db, "statusmsg", $menu->get(2)), "", true);
		$row['char'] = drop_down_menu("SELECT id, name FROM prefix_raid_chars WHERE rang>='4'" , "leader",  db_value( $db, "leader", $menu->get(2)), "", true);
		$row['gruppen'] = drop_down_menu("SELECT id, gruppen FROM prefix_raid_gruppen WHERE gruppen!='n/a' ORDER BY gruppen ASC" , "gruppen",  db_value( $db, "gruppen", $menu->get(2)), "", true);
		$row['stammgrp'] = drop_down_menu("prefix_raid_stammgrp", "stammgrp", $row['stammgrp'], "");
		$row['inzen'] = drop_down_menu("prefix_raid_inzen" , "inzen",  db_value( $db, "inzen", $menu->get(2)), "");
		$row['loot'] = drop_down_menu("prefix_raid_loot" , "loot",  db_value( $db, "loot", $menu->get(2) ), "" );
		$ifbid = db_value( $db, "bosskey", $menu->get(2));
		$row['bbid'] .= "<option value='0'>New ID</option>\n";
		$res = db_query("SELECT id FROM prefix_raid_raid WHERE bosskey='0' ORDER BY id DESC");
		while( $i = db_fetch_assoc( $res )){
			$id_select = ( $ifbid == $i['id'] ? 'selected' : '' );
			$row['bbid'] .= "<option value='".$i['id']."' ".$id_select."># ".$i['id']."</option>\n";
		}	
		$row['begind'] = date("Y-m-d", $row['inv']);
		$row['istd'] = date("H", db_value( $db, "inv", $menu->get(2)));
		$row['imin'] = date("i", db_value( $db, "inv", $menu->get(2)));
		$row['pstd'] = date("H", db_value( $db, "pull", $menu->get(2)));
		$row['pmin'] = date("i", db_value( $db, "pull", $menu->get(2)));
		$row['estd'] = date("H", db_value( $db, "ende", $menu->get(2)));
		$row['emin'] = date("i", db_value( $db, "ende", $menu->get(2)));
		$row['invsperre'] = db_value( $db, "invsperre", $menu->get(2));
		$row['treff'] = db_value( $db, "treff", $menu->get(2));
		$row['txt'] = db_value( $db, "txt", $menu->get(2));
		$row['SMILIS'] = getsmilies();
	break;
	default:
		$row['PFAD'] = "admin.php?raid-add";
		$row['status'] = drop_down_menu("SELECT id, statusmsg FROM prefix_raid_statusmsg WHERE sid='1'", "statusmsg", 1, "", true);
		$row['char'] = drop_down_menu("SELECT id, name FROM prefix_raid_chars WHERE rang>='4'" , "leader", $_SESSION['charid'],"" , true);
		$row['gruppen'] = drop_down_menu("SELECT id, gruppen FROM prefix_raid_gruppen WHERE gruppen!='n/a' ORDER BY gruppen ASC" , "gruppen", $value, "", true);
		$row['stammgrp'] = drop_down_menu("prefix_raid_stammgrp", "stammgrp", "", "");
		$row['inzen'] = drop_down_menu("prefix_raid_inzen" , "inzen", $value, "");
		$row['loot'] = drop_down_menu("prefix_raid_loot" , "loot", $value, "");
		$dif = time()-(7*86400);
		$res = db_query("SELECT id FROM prefix_raid_raid WHERE bosskey='0' AND inv>". $dif ." ORDER BY id DESC");
		$row['bbid'] .= "<option value='0'>New ID</option>\n";
		while( $i = db_fetch_assoc( $res )){
			$row['bbid'] .= "<option value='".$i['id']."'># ".$i['id']."</option>\n";
		}
		$row['begind'] = date("Y-m-d", time());
		$row['istd'] = 18;
		$row['imin'] = "00";
		$row['pstd'] = 18;
		$row['pmin'] = 10;
		$row['estd'] = 22;
		$row['emin'] = "00";
		$row['invsperre'] = $allgAr["mams"];
		$row['treff'] = "";
		$row['txt'] = "";
		$row['SMILIS'] = getsmilies();
}
$res = db_query("SELECT * FROM prefix_raid_gruppen ORDER BY gruppen ASC");
while($grs = db_fetch_assoc($res)){
	$row['listgrp'] .= "<option value='admin.php?raid-order-".$grs['id']."'>".$grs['gruppen']."</option>\n";
}

$tpl->set_ar_out( $row, 0);

$filter = ( $menu->get(1) == 'order' ? 'WHERE c.id='.$menu->get(2)."" : '' );
if( $menu->get(1) == 'order' ){
	$count = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen=".$menu->get(2) ),0);
}else{
	$count = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid" ),0);
}

$limit = $allgAr[ "meps_araid" ];  // Limit 
$page = ( $menu->getA(1) == 'p' ? escape($menu->getE(1), 'integer') : 1 );
$MPL = db_make_sites ($page , "" , $limit , "?raid" , 'raid_raid', $count );
$anfang = ($page - 1) * $limit;

$pages = ( $menu->get(1) == 'order' ? '' : 'LIMIT '.$anfang.','.$limit );

$res = db_query( "	SELECT 
						a.id, a.inv, a.gruppen as grp, a.bosskey, a.stammgrp AS sid, a.von AS owner, 
						b.name as inzen,
						c.gruppen, c.stammgrp, 
						d.statusmsg, d.color,
						e.grpsize, 
						f.name as leader 
					FROM prefix_raid_raid AS a 
						LEFT JOIN prefix_raid_inzen AS b ON a.inzen = b.id
						LEFT JOIN prefix_raid_gruppen AS c ON a.gruppen = c.id
						LEFT JOIN prefix_raid_statusmsg AS d ON a.statusmsg = d.id
						LEFT JOIN prefix_raid_grpsize AS e ON b.grpsize = e.id 
						LEFT JOIN prefix_raid_chars AS f ON a.leader = f.id 
					".$filter."
					ORDER BY d.id, a.inv  ASC ". $pages );
					#a.inv ASC, d.id  DESC
while( $row = db_fetch_assoc( $res )){
	if( RaidPermission($row['id']) or isStamm($row['sid']) ){
		if( $Class == $Cnorm ){ $Class = $Cmite; }else{ $Class = $Cnorm; }
		$row['CLASS'] = $Class;
		$row['idn'] = ( $row['bosskey'] > 0 ? '' : $row['id'] );
		$row['img'] = ( date("d.m.Y", $row['inv']) == date("d.m.Y", time()) ? "<img src='include/images/icons/online.gif'>" : '' );
		#### Anmeldungen
		$ply_res = db_query("SELECT COUNT(id) FROM prefix_raid_anmeldung WHERE rid = '".$row['id']."'");
		$ply_res = db_result( $ply_res, 0);
		$row['ply'] = $ply_res . "/" .  $row['grpsize'];
		####
		
		if( RaidPermission($row['id'])){
			$row['inv'] = "<a href='admin.php?dkp-".$row['id']."-".$row['grp']."'>".DateFormat("H:i - D d.m.Y", $row['inv'])."</a>";
			$row['edit'] = "<a href='admin.php?raid-edit-".$row['id']."'><img src='include/images/icons/edit.gif'></a>";
		}else{
			$row['inv'] = DateFormat("H:i - D d.m.Y", $row['inv']);
			$row['edit'] = "";
		}
		
		if( $_SESSION['authid'] == $row['owner'] || RaidPermission() ){ #$_SESSION['authright']
			$wayl = "admin.php?raid-del-" . $row['id'];
			$wayn = "Raid wirklich löschen? (DKP und Anemldungen werden mitgelöscht)";
			$row['del'] = "<a href='javascript:janein(\"$wayn\",\"$wayl\");'><img src='include/images/icons/del.gif'></a>";
		}else{
			$row['del'] = "";
		}
		
		$tpl->set_ar_out( $row, 1);
	}
}
$tpl->set_ar_out( array("MPL" => $MPL ), 2);

copyright();

$design->footer();

?>