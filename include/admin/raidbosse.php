<?php #print_r($_SESSION); 
#print_r($_POST);
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

$design->header();

echo "<br>";
if( !RaidPermission(0, TRUE) ){ echo "don't Permission"; $design->footer(); exit(); }

RaidErrorMsg();
aRaidMenu();

$iniPath = "include/raidplaner/images/dungeon/";
$imgPath = "include/raidplaner/images/bosse/";

$tpl = new tpl ( 'raid/raidbosse.htm',1 );

if( $_POST['img'] == 'true' ){
	if( $_FILES['imgUp']['size'] > 0 ){
		$imgName = $_FILES['imgUp']['name'];
		$ext = pathinfo( $imgName, PATHINFO_EXTENSION );
		$imgName = str_replace(" ", "-", escape( $_POST['bosse'], string ) ).".".$ext ;
		$stat = ( @move_uploaded_file($_FILES['imgUp']['tmp_name'], $imgPath.$imgName) ? TRUE : FALSE);
	}else{
		$stat = FALSE;
	}
}else{
	$stat = TRUE;
	$imgName = $_POST['img'];
}

switch($menu->get(2)){ 
	case "new":				
		if( $stat and db_query("INSERT INTO prefix_raid_bosse (inzen, bosse, taktik, img) 
		VALUES('".
		$menu->get(1)."','".
		ascape($_POST['bosse'])."','".
		ascape($_POST['taktik'])."','".
		$imgName."'); ") ){
			wd('admin.php?raidbosse-'.$menu->get(1),'Neuer eintrag war erfolgreich!');
		}else{
			wd('admin.php?raidbosse-'.$menu->get(1),'Neuer eintrag war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "edit":
		
		if( $stat and db_query("UPDATE prefix_raid_bosse SET
		 bosse='".ascape($_POST['bosse'])."',  
		 taktik='".ascape($_POST['txt'])."',
		 img='".$imgName."', 
		 inzen='".$menu->get(1)."' WHERE id=".$_POST['id'] )){
			wd('admin.php?raidbosse-'.$menu->get(1),'Update war erfolgreich!');
		}else{
			wd('admin.php?raidbosse-'.$menu->get(1).'-'.$_POST['id'],'Update war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "del":
		if( $menu->get(3) == "TRUE" ){
			if(	db_query("DELETE FROM prefix_raid_bosse WHERE id = '".$menu->get(4)."' LIMIT 1") ){
				wd('admin.php?raidbosse-'.$menu->get(1),'Löschen war erfolgreich!');
			}else{
				wd('admin.php?raidbosse-'.$menu->get(1),'Löschen war <b>nicht</b> erfolgreich!');
			}
		}else{
			$delLink = "admin.php?raidbosse-".$menu->get(1)."-del-TRUE-".$menu->get(3);
			$inze = db_result(db_query("SELECT bosse FROM prefix_raid_bosse WHERE id=".$menu->get(3).' LIMIT 1' ),0);
			echo "<center><font color=red><b>Boss \"".$inze."\" wirklich L&ouml;schen?"
			."<br /><form name='form' method='post' action='".$delLink."'><input name='submit' type='submit' value='L&ouml;schen' /></form></b></font></center>";
		}
	break;
	case "delImg":
		if( @unlink( $imgPath.$_GET['img'] ) ){
			wd('admin.php?raidbosse','Bild Löschen war erfolgreich!');
		}else{
			wd('admin.php?raidbosse','Bild Löschen war <b>nicht</b> erfolgreich!');
		}
	break;
	default:
		$tpl->out(0); #Header
		### FORMULAR
		if( $menu->get(1) != '' ){
			if( $menu->get(2) == '' ){
				$out['pfad'] = "admin.php?raidbosse-".$menu->get(1)."-new";
				$out['bosse'] = $out['taktik'] = "";
				$out['img'] = img_popup( $imgPath, 'img');
				
			}else{
				$res = db_query("SELECT * FROM prefix_raid_bosse WHERE id=".$menu->get(2)." LIMIT 1" );
				$out = db_fetch_object( $res );
				$out->pfad = "admin.php?raidbosse-".$menu->get(1)."-edit";
				$out->img = img_popup( $imgPath, 'img', $out->img);
			}
			
			$tpl->set_ar_out( $out, 1 );
		}else{
			$tpl->set_out("msg","Bitte Instanze Auswählen in der Mitte!", 2);
		}
		
		$tpl->out(3);
		$countFiles = CountFiles( $imgPath );
		if( $countFiles != 0 ){
			$open = opendir( $imgPath );
			while( $img = readdir( $open ) ){
				if( $img != "." and $img != ".." ){
					$del = ( @is_writeable($imgPath.$img ) ? aLink("<img src='include/images/icons/del.gif'>","raidbosse-".$menu->get(1)."-delImg&img=".$img,1) : '&Oslash;');
					$tpl->set_ar_out( array( "class" => "Cnorm", "img" => aLink($img,$imgPath.$img,2), "del" => $del),4);
				}
			}
			closedir();
		}else{
			$tpl->set_out( "msg", "Keine Boss Bilder Vorhanden!", 5 );
		}
		$tpl->out(6);
		$tpl->out(7); #Ende Erste Spalte
		### Instanze Liste
		$tpl->out(8);
		
		$res = db_query("SELECT 
							a.id, a.name, a.img, a.maxbosse, 
							b.id AS iid, b.info,
							COUNT(c.id) AS amaxbosse 
						FROM prefix_raid_inzen AS a 
							LEFT JOIN prefix_raid_info AS b ON a.info=b.id 
							LEFT JOIN prefix_raid_bosse AS c ON a.id=c.inzen 
						GROUP BY a.name 
						ORDER BY b.id ASC");
						
		if( db_num_rows( $res ) != 0 ){
			while( $ini = db_fetch_object($res) ){
				$c1iid = $ini->iid;
				if( $ini->iid != $c2iid ){
					$tpl->set_out("msg","<b><div align='right'>".$ini->info."</div></b>", 10);
				}
				$ini->class = ( $ini->id == $menu->get(1) ? 'Cdark' : 'Cnorm');
				$ini->name = "<b>".aLink( $ini->name, "raidbosse-".$ini->id, 1)."</b> (".$ini->amaxbosse."/".$ini->maxbosse.")";
				$ini->img = ( is_file( $iniPath.$ini->img ) ? "<img width='20' src='".$iniPath.$ini->img."'>" : "" );
				$tpl->set_ar_out($ini, 9);
				$c2iid = $c1iid;
			}
		}else{
			$tpl->set_out("msg","Zuerst eine Instanze Anlegen", 10);
		}
		
		$tpl->out(11);
		### Instanze Liste ENDE
		$tpl->out(12); #Ende Zweiter Spalte
		### Boss Liste Anfang
		$inzen = ( $menu->get(1) != NULL ? db_result(db_query("SELECT name FROM prefix_raid_inzen WHERE id=".$menu->get(1)." LIMIT 1" ), 0) : '');
		$tpl->set_out("inzen", $inzen, 13);
		if( $menu->get(1) != '' ){
			$res = db_query("SELECT id, bosse, img FROM prefix_raid_bosse WHERE inzen=".$menu->get(1) );
			if( db_num_rows( $res ) ){
				while( $boss = db_fetch_object($res) ){
					$boss->img = ( is_file( $imgPath.$boss->img ) ? "<img width='50' height='50' src='".$imgPath.$boss->img."'>" : "" );
					$boss->edit = "<a href='admin.php?raidbosse-".$menu->get(1)."-".$boss->id."'><img src='include/images/icons/edit.gif'></a>";
					$boss->del = "<a href='admin.php?raidbosse-".$menu->get(1)."-del-".$boss->id."'><img src='include/images/icons/del.gif'></a>";
					$tpl->set_ar_out( $boss, 14);
				}
			}else{
				$tpl->set_out("msg","Kein Boss für \"<b>".$inzen."</b>\" Gefunden!", 15);
			}
		}else{
			$tpl->set_out("msg","Bitte Instanze Auswählen Mitte!", 15);
		}
		$tpl->out(16);
		### Boss Liste ENDE
		$tpl->out(17); #Ende Dritte Spalte + FOOTER
	break;
}

copyright();

$design->footer();

?>