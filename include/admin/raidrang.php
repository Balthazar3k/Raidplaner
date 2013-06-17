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

$tpl = new tpl ( 'raid/raidrang.htm',1 );

switch($menu->get(1)){
	case "edit":
		#arrPrint($_POST);
		$arrNotSend = array( "button" => 1 );
		foreach( $_POST['r'] as $id => $rang ){
			if( $arrNotSend[$id] != 1){
				db_query("UPDATE prefix_raid_rang SET rang='".ascape($rang)."', module='".ascape($_POST['m'][$id])."' WHERE id=".$id );
			}
		}
		
		wd("admin.php?raidrang-update","Rang Update Fertig!",0);
	break;
	case "update":
		$res = db_query("SELECT 
							a.user, 
							b.module 
						 FROM prefix_raid_chars AS a 
							LEFT JOIN prefix_raid_rang AS b ON a.rang=b.id");
						 
		### Modul Rechte Vergeben!
		while( $char = db_fetch_object( $res ) ){
			@db_query("DELETE FROM prefix_modulerights WHERE mid IN(800,801,802,803,804,805,806,807,808,809,810) AND uid=".$char->user);
			$modules = explode(",", $char->module );
			foreach( $modules as $mids ){
				if( $mids != NULL ){
					@db_query("INSERT INTO prefix_modulerights (uid, mid) VALUES(".$char->user.", ".$mids.");");
				}
			}
		}
		
		wd("admin.php?raidrang","Alle Chars Modul rechte wurden erneuert!",0);
	break;
	default:
		$res = db_query("SELECT id, name FROM prefix_modules WHERE name LIKE 'R:%' ORDER BY id ASC");
		while( $rang = db_fetch_object( $res ) ){
			$header->list_raid_module .= "Modul <b>".$rang->id."</b>: ". str_replace("R:","", $rang->name)."<br>";
		}
		
		$res = db_query("SELECT id, name FROM prefix_modules WHERE name NOT LIKE 'R:%' ORDER BY id ASC");
		while( $rang = db_fetch_object( $res ) ){
			$header->list_noraid_module .= "Modul <b>".$rang->id."</b>: ". str_replace("R:","", $rang->name)."<br>";
		}
		
		$header->pfad = "admin.php?raidrang-edit";
		$tpl->set_ar_out($header,0); # Header
	
		$res = db_query("SELECT * FROM prefix_raid_rang ORDER BY id DESC");
		while( $row = db_fetch_object( $res ) ){
			$row->class = ( $row->class == "Cmite" ? "Cnorm" : "Cmite" );
			$tpl->set_ar_out( $row, 1);
		}
		
		$tpl->out(2); # Tab Footer
	break;
}

copyright();

$design->footer();

?>