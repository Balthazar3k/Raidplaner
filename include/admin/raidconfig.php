<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

$design->header();

RaidErrorMsg();
aRaidMenu();
# 0 = Dropdown oder Normal
# 1 = Felder mitund ohne Dropdown
# 2 = Dropdown f�r eintr�ge in der datenbank ohne prefix_raid_xxx
$exten = array( 
    "cfg" => "2",
    "klassen" => "0", 
    "rassen" => "0",
    "berufe" => "0",
    "loot" => "0",
    "gruppen" => "0",
    "grpsize" => "0",
    "info" => "0",
    "rang" => "0",
    "inzen" => "1",
    "chars" => "1",
    "dkps" => "1",
    "bosse" => "0",
    "statusmsg" => "0",
    "user" => "2",
    "grundrechte" => "2",
    "stammgrp"=>"0"
);

$del_klasse = "-9";
$del_img =	"<img src='include/images/b3kimg/loeschen.jpg' border='0'  title='L�schen'>";
$page = "raidconfig-".$menu->get(1);

switch($menu->get(2)){
	case "addynamisch":
		foreach( $_POST as $key => $value ){
			if( !empty($value) ){
				if( !empty( $value ) && $key != "Submit"){
					list( $id, $feld) = explode("_", $key );
					if( $feld == "img" && $value == 'true' ){
						$file_name = $_FILES[$id."_upload"]['name'];
						$file_tmp_name = $_FILES[$id."_upload"]['tmp_name'];
						$file_size = $_FILES[$id."_upload"]['size'];
						if( $file_size > 0 ){
							if( move_uploaded_file( $file_tmp_name, "include/images/".$menu->get(1)."/". $file_name )){
								$feld = "img";
								$value = $file_name;
							}else{
								echo "Bild konnte <b>nicht</b> Hochgeladen werden!<br>";
							}
						}
					}
					if( $feld != "upload" ){
						$res = db_query( "SELECT ".$feld." FROM prefix_raid_".$menu->get(1)." WHERE id = ".$id." ");
						$row = mysql_fetch_array( $res );
						if( $row[$feld] != $value ){
							db_query("UPDATE prefix_raid_".$menu->get(1)." SET ".$feld." = '". ascape($value) ."' WHERE id = '". $id ."' LIMIT 1");
						}
					}
				}
			}
		}
	break;
	case "new";
		db_query('INSERT INTO prefix_raid_'.$menu->get(1).' (id) VALUES (\'\')');
	break;
	case "del":
		db_query("DELETE FROM prefix_raid_".$menu->get(1)." WHERE id = ".$menu->get(3)." LIMIT 1");
	break;
}

$tpl = new tpl ( 'raid/raidconfig.htm',1 );
$tpl->set_ar_out( array("0" => "0"), 0);
### Klassen ###################################################
if( $menu->get(1) != "" ){
	$neu = aLink("Neuen Zeile '".ucfirst($menu->get(1))."' Anf�gen",$page."-new#ende",1);
	$tpl->set_ar_out( array("NAME" => $neu, "PFAD" => "admin.php?raidconfig-".$menu->get(1)."-addynamisch"), 4);
	$res = db_query( 'SELECT * FROM `prefix_raid_'.$menu->get(1).'` ORDER BY `prefix_raid_'.$menu->get(1).'` . `id` ASC');
	$feld = array();
	while( $row = db_fetch_assoc( $res )){
		$i = "-1";
		$Class = cssClass($Class);
		foreach( $row as $key => $value ){
			$i++; 
			$feld[$i] = aLink("<img src='include/images/icons/del.gif'>",$page . "-del-" . $row['id'], 1)." ";
			if( $key != "id" ){
				if( $exten[$key] == "" OR $exten[$menu->get(1)] == 0 OR $exten[$key] == 1 ){
					if($exten[$key] == 1){
						$feld[$i] = drop_down_menu("prefix_raid_".$key , $key, $value, $row['id']);
					}else{
						if( $key != 'img' ){
							$feld[$i] = '<input name="'.$row['id'].'_'.$key.'" type="text" value="'.$value.'" size="20"> ';
						}else{
							$feld[$i] = img_popup( "include/images/".$menu->get(1)."/", $row['id']."_img" ,$value );
							$feld[$i] .= "<input type='file' name='".$row['id']."_upload'>";
						}
					}
				}else{
					if( $exten[$key] == 0 ){
						$feld[$i] = drop_down_menu("prefix_raid_".$key , $key, $value, $row['id']);
					}elseif( $exten[$key] == 2 ){
						$feld[$i] = drop_down_menu("prefix_".$key , $key, $value, $row['id']);
					}
				}
			}
		}
		$felder = implode($feld, "");
		$tpl->set_ar_out( array("FELDER" => $felder, "CLASS" => $Class), 5);
	}
	$tpl->set_ar_out( array("0" => "0"), 6);
}
####################################################################
copyright();

$design->footer();

?>