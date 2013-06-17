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

$imgPath = "include/images/raidstammgrp/";

$tpl = new tpl ( 'raid/raidstammgrp.htm',1 );

switch($menu->get(1)){
	case "new":				
		if(db_query("INSERT INTO prefix_raid_stammgrp (stammgrp) VALUES('".ascape($_POST['stammgrp'])."'); ") 
			and db_query("INSERT INTO prefix_groups (name) VALUES('".ascape($_POST['stammgrp'])."');") ){
			wd('admin.php?raidstammgrp','Neuer eintrag war erfolgreich!');
		}else{
			wd('admin.php?raidstammgrp','Neuer eintrag war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "edit":
		$sname = db_result(db_query("SELECT stammgrp FROM prefix_raid_stammgrp WHERE id=".$_POST['id']), 0);
		if(db_query("UPDATE prefix_raid_stammgrp SET stammgrp='".ascape($_POST['stammgrp'])."' WHERE id=".$_POST['id'] ) 
			and db_query("UPDATE prefix_groups SET name='".ascape($_POST['stammgrp'])."' WHERE name='".$sname."'") ){
			wd('admin.php?raidstammgrp','Update war erfolgreich!');
		}else{
			wd('admin.php?raidstammgrp','Update war <b>nicht</b> erfolgreich!', 10);
		}
	break;
	case "del":
		if( $menu->get(3) == "TRUE" ){
			$sname = db_result(db_query("SELECT stammgrp FROM prefix_raid_stammgrp WHERE id=".$menu->get(2)), 0);
			$gid = db_result(db_query("SELECT id FROM prefix_groups WHERE name='".$sname."'"), 0);
			if(	    db_query("DELETE FROM prefix_raid_stammgrp WHERE id = '".$menu->get(2)."'")
				and db_query("DELETE FROM prefix_raid_stammrechte WHERE sid=".$menu->get(2) )
				and db_query("DELETE FROM prefix_groups WHERE name = '".$sname."'") 
				and db_query("DELETE FROM prefix_groupusers WHERE gid='".$gid."'" )
				and db_query("UPDATE prefix_raid_raid SET stammgrp='0' WHERE stammgrp='".$menu->get(2)."'")
				and db_query("UPDATE prefix_raid_gruppen SET stammgrp='0' WHERE stammgrp='".$menu->get(2)."'") )
			{
				wd('admin.php?raidstammgrp','Löschen war erfolgreich!');
			}else{
				wd('admin.php?raidstammgrp','Löschen war <b>nicht</b> erfolgreich!');
			}
		}else{
			$delLink = "admin.php?raidstammgrp-del-".$menu->get(2)."-TRUE";
			$grp = db_result(db_query("SELECT stammgrp FROM prefix_raid_stammgrp WHERE id=".$menu->get(2) ),0);
			echo "<center><font color=red><b>Die Gruppe \"".$grp."\" wirklich Löschen?"
			."<br /> Achtung! DKP, Raids und Anmeldungen werden unwiederruflich mit gelöscht!"
			."<br /><form name='form' method='post' action='".$delLink."'><input name='submit' type='submit' value='L&ouml;schen' /></form></b></font></center>";
		}
	break;
	default:
		if( $menu->get(1) == '' ){
			$out->pfad = "admin.php?raidstammgrp-new";
			$out->id = "";
			$out->stammgrp = '';
			
		}else{
			$res = db_query("SELECT * FROM prefix_raid_stammgrp WHERE id=".$menu->get(1)." LIMIT 1" );
			$out = db_fetch_object( $res );
			$out->pfad = "admin.php?raidstammgrp-edit";
		}
		
		$tpl->set_ar_out( $out, 0 );
		
		$res = db_query("SELECT a.id, a.stammgrp FROM prefix_raid_stammgrp AS a");
		if( db_num_rows( $res ) ){
			while( $row = db_fetch_object( $res ) ){
				$row->clas = cssClass( $row->clas );
				$row->del = "<a href='admin.php?raidstammgrp-del-".$row->id."'><img src='include/images/icons/del.gif'></a>";
				$row->edit = "<a href='admin.php?raidstammgrp-".$row->id."'><img src='include/images/icons/edit.gif'></a>";
				$tpl->set_ar_out($row, 1);
			}
		}else{
			$tpl->set_out( "msg", "Keine DKP stammgrp Vorhanden!", 2 );
		}
		
		$tpl->out(3);
	break;
}

copyright();

$design->footer();

?>