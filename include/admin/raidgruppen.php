<?php #print_r($_SESSION); 
#print_r($_POST);
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

$design->header();

echo "<br>";
if( !RaidPermission(0, TRUE, $menu->get(0) ) ){ echo "don't Permission"; $design->footer(); exit(); }

RaidErrorMsg();
aRaidMenu();

$imgPath = "include/images/raidgruppen/";

$tpl = new tpl ( 'raid/raidgruppen.htm',1 );

if( $_POST['img'] == 'true' ){
	if( $_FILES['imgUp']['size'] > 0 ){
		$imgName = ascape(str_replace(" ", "", $_FILES['imgUp']['name']));
		$stat = @move_uploaded_file($_FILES['imgUp']['tmp_name'], $imgPath.$imgName);
	}else{
		$stat = FALSE;
	}
}else{
	$stat = TRUE;
	$imgName = $_POST['img'];
}

switch($menu->get(1)){
	case "new":				
		if( $stat and db_query("INSERT INTO prefix_raid_gruppen (gruppen, regeln, stammgrp, img) 
		VALUES('".ascape($_POST['gruppen'])."','".ascape($_POST['txt'])."','".ascape($_POST['stammgrp'])."','".$imgName."'); ") ){
			wd('admin.php?raidgruppen','Neuer eintrag war erfolgreich!');
		}else{
			wd('admin.php?raidgruppen','Neuer eintrag war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "edit":
		
		if( $stat and db_query("UPDATE prefix_raid_gruppen SET
		 gruppen='".ascape($_POST['gruppen'])."', 
		 stammgrp='".ascape($_POST['stammgrp'])."', 
		 img='".$imgName."', 
		 regeln='".ascape($_POST['txt'])."' WHERE id=".$_POST['id'] )){
			wd('admin.php?raidgruppen','Update war erfolgreich!');
		}else{
			wd('admin.php?raidgruppen','Update war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "del":
		if( $menu->get(3) == "TRUE" ){
			if(	db_query("DELETE FROM prefix_raid_gruppen WHERE id = '".$menu->get(2)."'")
				and db_query("DELETE FROM prefix_raid_anmeldung WHERE grp = '".$menu->get(2)."'")
				and db_query("DELETE FROM prefix_raid_dkp WHERE dkpgrp = '".$menu->get(2)."'")
				and db_query("DELETE FROM prefix_raid_raid WHERE gruppen = '".$menu->get(2)."'") )
			{
				wd('admin.php?raidgruppen','Löschen war erfolgreich!');
			}else{
				wd('admin.php?raidgruppen','Löschen war <b>nicht</b> erfolgreich!');
			}
		}else{
			$delLink = "admin.php?raidgruppen-del-".$menu->get(2)."-TRUE";
			$grp = db_result(db_query("SELECT gruppen FROM prefix_raid_gruppen WHERE id=".$menu->get(2) ),0);
			echo "<center><font color=red><b>Die Gruppe \"".$grp."\" wirklich Löschen?"
			."<br /> Achtung! DKP, Raids und Anmeldungen werden unwiederruflich mit gelöscht!"
			."<br /><form name='form' method='post' action='".$delLink."'><input name='submit' type='submit' value='L&ouml;schen' /></form></b></font></center>";
		}
	break;
	case "delImg":
		if( @unlink( $imgPath.$_GET['img'] ) ){
			wd('admin.php?raidgruppen','Bild Löschen war erfolgreich!');
		}else{
			wd('admin.php?raidgruppen','Bild Löschen war <b>nicht</b> erfolgreich!');
		}
	break;
	default:
		if( $menu->get(1) == '' ){
			$out['pfad'] = "admin.php?raidgruppen-new";
			$out['id'] = $out['gruppen'] = "";
			$out['stammgrp'] = drop_down_menu("prefix_raid_stammgrp" , "stammgrp", "", "");
			$out['img'] = img_popup( $imgPath, 'img');
			$out['regeln'] = "";
			
		}else{
			$res = db_query("SELECT * FROM prefix_raid_gruppen WHERE id=".$menu->get(1)." LIMIT 1" );
			$out = db_fetch_object( $res );
			$out->pfad = "admin.php?raidgruppen-edit";
			$out->img = img_popup( $imgPath, 'img', $out->img);
			$out->stammgrp = drop_down_menu("prefix_raid_stammgrp" , "stammgrp", $out->stammgrp, "");
		}
		
		$tpl->set_ar_out( $out, 0 );
		
		$res = db_query("SELECT a.id, a.gruppen, a.img, b.stammgrp FROM prefix_raid_gruppen AS a
		LEFT JOIN prefix_raid_stammgrp AS b ON a.stammgrp=b.id");
		if( db_num_rows( $res ) ){
			while( $row = db_fetch_object( $res ) ){
				$row->clas = cssClass( $row->clas );
				$img = ( file_exists( $imgPath.$row->img ) ? "<font color='green'>".$row->img."</font>" : "<font color='red'>Bild Existiert nicht!</font>");
				$row->img = ( $row->img != NULL ? $img : "<font color='blue'>Kein Bild</font>" );
				$row->raids = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen=".$row->id),0);
				$row->bkills = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter WHERE grpid=".$row->id),0);
				$row->del = "<a href='admin.php?raidgruppen-del-".$row->id."'><img src='include/images/icons/del.gif'></a>";
				$row->edit = "<a href='admin.php?raidgruppen-".$row->id."'><img src='include/images/icons/edit.gif'></a>";
				$tpl->set_ar_out($row, 1);
			}
		}else{
			$tpl->set_out( "msg", "Keine DKP Gruppen Vorhanden!", 2 );
		}
		
		$tpl->out(3);
		$countFiles = CountFiles( $imgPath );
		if( $countFiles != 0 ){
			$open = opendir( $imgPath );
			while( $img = readdir( $open ) ){
				if( $img != "." and $img != ".." ){
					$del = aLink("<img src='include/images/icons/del.gif'>","raidgruppen-delImg&img=".$img,1);
					$tpl->set_ar_out( array( "class" => "Cnorm", "img" => aLink($img,$imgPath.$img,2), "del" => $del),4);
				}
			}
			closedir();
		}else{
			$tpl->set_out( "msg", "Keine DKP Gruppen Bilder Vorhanden!", 5 );
		}
	
		$tpl->out(6);
	break;
}

copyright();

$design->footer();

?>