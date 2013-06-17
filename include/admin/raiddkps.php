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

$tpl = new tpl ( 'raid/raiddkps.htm',1 );

$iniPath = "include/images/inzen/";

switch($menu->get(2)){ 
	case "new":			
		if( db_query("INSERT INTO prefix_raid_dkps (inzen, name, dkp, pm) 
		VALUES('".
		$menu->get(1)."','".
		ascape($_POST['name'])."','".
		ascape($_POST['dkp'])."','".
		$_POST['pm']."'); ") ){
			wd('admin.php?raiddkps-'.$menu->get(1),'Neuer eintrag war erfolgreich!');
		}else{
			wd('admin.php?raiddkps-'.$menu->get(1),'Neuer eintrag war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "edit":
		
		if( db_query("UPDATE prefix_raid_dkps SET
		 name='".ascape($_POST['name'])."',  
		 dkp='".ascape($_POST['dkp'])."',
		 pm='".ascape($_POST['pm'])."' WHERE id=".$_POST['id'] )){
			wd('admin.php?raiddkps-'.$menu->get(1),'Update war erfolgreich!');
		}else{
			wd('admin.php?raiddkps-'.$menu->get(1).'-'.$_POST['id'],'Update war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "del":
		if(	db_query("DELETE FROM prefix_raid_dkps WHERE id = '".$menu->get(3)."' LIMIT 1") ){
			wd('admin.php?raiddkps-'.$menu->get(1),'Löschen war erfolgreich!');
		}else{
			wd('admin.php?raiddkps-'.$menu->get(1),'Löschen war <b>nicht</b> erfolgreich!');
		}
	break;
	default:
		$tpl->out(0); #Header
		### FORMULAR
		if( $menu->get(1) != '' ){
			if( $menu->get(2) == '' ){
				$out->pfad = "admin.php?raiddkps-".$menu->get(1)."-new";
				$out->name = $out->dkp = "";
				
			}else{
				$res = db_query("SELECT * FROM prefix_raid_dkps WHERE id=".$menu->get(2)." LIMIT 1" );
				$out = db_fetch_object( $res );
				$out->pfad = "admin.php?raiddkps-".$menu->get(1)."-edit";
				$out->pm1 = ( $out->pm == '+' ? 'checked="checked"' : '');
				$out->pm2 = ( $out->pm == '-' ? 'checked="checked"' : '');
			}
			
			$tpl->set_ar_out( $out, 1 );
		}else{
			$tpl->set_out("msg","Bitte Instanze Auswählen in der Mitte", 2);
		}
		
		$tpl->out(3); #Ende Erste Spalte
		### Instanze Liste
		$tpl->out(4);
		
		$res = db_query("SELECT 
							a.id, a.name, a.img,  
							b.id AS iid, b.info,
							COUNT(c.id) AS dkps 
						FROM prefix_raid_inzen AS a 
							LEFT JOIN prefix_raid_info AS b ON a.info=b.id 
							LEFT JOIN prefix_raid_dkps AS c ON a.id=c.inzen 
						GROUP BY a.name 
						ORDER BY b.id ASC");
		if( db_num_rows( $res ) != 0 ){
			while( $ini = db_fetch_object($res) ){
				$c1iid = $ini->iid;
				if( $ini->iid != $c2iid ){ $tpl->set_out("msg","<b><div align='right'>".$ini->info."</div></b>", 6); }
				$ini->class = ( $ini->id == $menu->get(1) ? 'Cdark' : 'Cnorm');
				$ini->name = "<b>".aLink( $ini->name, "raiddkps-".$ini->id, 1)."</b> (".$ini->dkps.")";
				$ini->img = ( is_file( $iniPath.$ini->img ) ? "<img width='20' src='".$iniPath.$ini->img."'>" : "" );
				$tpl->set_ar_out($ini, 5);
				$c2iid = $c1iid;
			}
		}else{
			$tpl->set_out("msg","Zuerst eine Instanze Anlegen", 6);
		}
		
		$tpl->out(7);
		### Instanze Liste ENDE
		$tpl->out(8); #Ende Zweiter Spalte
		### Boss Liste Anfang `id`, `inzen`, `name`, `dkp`, `pm`
		$inzen = ( $menu->get(1) != NULL ? db_result(db_query("SELECT name FROM prefix_raid_inzen WHERE id=".$menu->get(1)." LIMIT 1" ), 0) : '');
		$tpl->set_out("inzen", $inzen, 9);
		if( $menu->get(1) != '' ){
			$res = db_query("SELECT id, name, dkp, pm FROM prefix_raid_dkps WHERE inzen=".$menu->get(1) );
			if( db_num_rows( $res ) ){
				while( $boss = db_fetch_object($res) ){
					$boss->edit = "<a href='admin.php?raiddkps-".$menu->get(1)."-".$boss->id."'><img src='include/images/icons/edit.gif'></a>";
					$boss->del = "<a href='admin.php?raiddkps-".$menu->get(1)."-del-".$boss->id."'><img src='include/images/icons/del.gif'></a>";
					$tpl->set_ar_out( $boss, 10);
				}
			}else{
				$tpl->set_out("msg","Kein Boss für \"<b>".$inzen."</b>\" Gefunden!", 11);
			}
		}else{
			$tpl->set_out("msg","Bitte Instanze Auswählen in der Mitte!", 11);
		}
		$tpl->out(12);
		### Boss Liste ENDE
		$tpl->out(13); #Ende Dritte Spalte + FOOTER
	break;
}

copyright();

$design->footer();

?>