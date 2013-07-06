<?php
### b3k_func.php Copyright: 2007/2008 edit 2009, 2012, 2013 By: Balthazar3k.de
#arrPrint($_SESSION); 
#arrPrint($_POST);

CreatRaidSession();

function copyright(){
 echo "<br><div align='center' class='smallfont'>[ Raidplaner &amp; DKP System v1.1 &copy; by <a href='http://Balthazar3k.funpic.de' target='_blank'>Balthazar3k.funpic.de</a> ]</div>\n";
}
###### RAIDPLANER HEADER
$ILCH_HEADER_ADDITIONS .= "<!--RAIDPLANER HEADER-->\n\t";
$ILCH_HEADER_ADDITIONS .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin&effect=shadow-multiple|3d\">\n";
$ILCH_HEADER_ADDITIONS .= "<link rel='stylesheet' type='text/css' href='include/includes/css/raidplaner.css' />\n\t";
$ILCH_HEADER_ADDITIONS .= "<script src='include/includes/libs/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js' type='text/javascript'></script>\n\t";
$ILCH_HEADER_ADDITIONS .= "<script src='include/includes/libs/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js'></script>\n\t";
$ILCH_HEADER_ADDITIONS .= "<link rel='stylesheet' type='text/css' href='include/includes/libs/jquery-ui-1.10.3.custom/css/start/jquery-ui-1.10.3.custom.min.css' />\n\t";
$ILCH_HEADER_ADDITIONS .= "<script src='include/includes/js/b3k.js' type='text/javascript'></script>\n\t";
$ILCH_HEADER_ADDITIONS .= "<!--RAIDPLANER HEADER END-->\n";

### Sessions der mainchars Generieren.
function CreatRaidSession(){
	$arr = array('charname', 'charid', 'charrang','charklasse','stammgrp');
	foreach( $arr as $a ){
		if( !loggedin() ){
			$_SESSION[$a] = '';
		}
	}
	
	if( loggedin() ){
		$ses = db_query( "SELECT id, name, rang, klassen FROM prefix_raid_chars WHERE user='".$_SESSION['authid']."' ORDER BY id LIMIT 1" );
		$my = db_fetch_assoc($ses);
		$_SESSION['charname'] = $my['name'];
		$_SESSION['charid'] = $my['id'];
		$_SESSION['charrang'] = $my['rang'];
		$_SESSION['charklasse'] = $my['klassen'];
			
		$_SESSION['stammgrp'][0] = 1;
		
		if( !empty( $_SESSION['charid'] ) ){			
			$res = db_query("SELECT sid FROM prefix_raid_stammrechte WHERE cid=".$my['id']);
			while( $row = db_fetch_object( $res )){
				$_SESSION['stammgrp'][$row->sid] = 1;
			}
		}
		
		### adminaccess neu mit 1.1p
		$perm = db_query( "
			SELECT 
				a.uid, a.mid, b.url 
			FROM prefix_modulerights AS a
				LEFT JOIN prefix_modules AS b ON a.mid = b.id
			WHERE 
				a.uid = ".$_SESSION['authid']."
				AND b.name LIKE 'R:%'
		");
		
		while( $row = db_fetch_assoc( $perm ) ){
			$_SESSION['adminaccess'][$row['url']] = true;
		}
		
	}else{
		$_SESSION['charname'] = $_SESSION['charid'] = $_SESSION['charrang'] = $_SESSION['charklasse'] = 0;
	}
}
### Raid Errors ##############################################
##############################################################
function RaidErrorMsg(){
	global $allgAr;
	### Raids auf Gültigkeit überprüfen
	$res = db_query("SELECT id, ende FROM prefix_raid_raid WHERE statusmsg=1 AND ende<=".(time()-7200) );
	while( $row = db_fetch_assoc( $res )){
		db_query("UPDATE prefix_raid_raid SET statusmsg=17 WHERE id=".$row['id'] );
	}
	### Fehler nur für Admins
	if( is_admin() ){
		
		### Updates überprüfen
		include("include/raidplaner/raidplaner.updater.php");
		$ru = new updater();
		
		$isRaidGrp = db_result(db_query('SELECT COUNT(id) FROM prefix_raid_gruppen'),0);
		$error['isRaidGrp'] = ( $isRaidGrp == 0 ? 'Raidplaner: Es müssen DKP Gruppen angelegt werden, '.aLink('Anlegen','raidgruppen',1).'!' : '');
		
		$isInzen = db_result(db_query('SELECT COUNT(id) FROM prefix_raid_inzen'),0);
		$error['isInzen'] = ( $isInzen == 0 ? 'Raidplaner: Es müssen Instanzen angelegt werden, '.aLink('Anlegen','raidinzen',1).'!' : '');
		
		$isDkps = db_result(db_query('SELECT COUNT(id) FROM prefix_raid_dkps'),0);
		$error['isDkps'] = ( $isDkps == 0 ? 'Raidplaner: Es müssen DKP Definiert werden, '.aLink('Definieren','raiddkps',1).'!' : '');
		
		
	}
	### Fehler für Raidleiter, Super Raidleiter, Offizer, Gildenmeister & Admins
	if( RaidPermission() ){
		### Wenn's ausstehende Raids gibt wird man Informiert.
		$res = db_query("SELECT id, inv FROM prefix_raid_raid WHERE statusmsg=17");
		while( $row = db_fetch_assoc( $res )){
			$error['chkRaids'] .= "<div align='center'>";
			$error['chkRaids'] .= "ERROR: Ausstehender Raid vom: <a href='admin.php?raid-edit-".$row['id']."'>". DateFormat("D d.m.Y H:i", $row['inv'])."</a> ";
			$error['chkRaids'] .= "(Status Ändern!)";
			$error['chkRaids'] .= "</div>";
		}
	}
	### Ab Rang Super Raidleiter Anzeigen!
	if( $_SESSION['charrang'] >= 6 ){
		$cBewerber = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang=1"),0);
		if( $cBewerber == 1 ){
			$error['cBewerber'] = "Es liegt eine Bewerbung vor, ".alink("Ansehen","bewerbung")."!";
		}elseif( $cBewerber > 1 ){
			$error['cBewerber'] = "Es liegen ".$cBewerber." Bewerbungen vor, ".alink("Ansehen","bewerbung")."!";
		}
	}
	### Char Überprüfen!
	$error['exRaidChar'] = exRaidChar(1);
	$error['isRaidKalender'] = ( $allgAr['isRaidKalender'] == 0 ? '' : isRaidKalender(1) );
	
	### Überprüfe ob es Errors gibt!
	$redwindow = FALSE;
	foreach( $error as $key => $value ){
		if( !empty($error[$key]) or $error[$key] ){
			$redwindow = TRUE;
		}
	}
	### Errors Ausgeben wenn vorhanden
	if( $redwindow ){
		$i=0;
		echo '
			<div class="Cnorm" style="text-shadow: 1px 1px 0 #FFF; padding: 5px; border-radius: 10px; border: 5px solid red; margin-bottom: 5px;">
				<h3 style="margin-top: 0;">Folgende Fehler sind aufgetreten!</h3>
				<ol>
		';
		foreach( $error as $value ){
			if( !empty( $value ) ){
				$br = ( $i > 0 ? '<br>' : '' );
				echo "<li style='font-weight: bold; color: darkred;'>".$value."</li>";
				$i++;
			}
		}
		echo '</ol></div>';
	}
}
### Raidplaner Menu Leiste
function aRaidMenu(){
    global $menu;
        
    $raidLinks = array(
    "Index" => "raidindex",
    "Raidplaner" => "raid",
    "Chars" => "chars",
    "Config" => "raidconfig",
    "DKP Gruppen" => "raidgruppen",
    "Stammgruppen" => "raidstammgrp",
    "Instanzen" => "raidinzen",
    "Bosse" => "raidbosse",
    "Ränge" => "raidrang",
    "DKP'S" => "raiddkps");
    
    echo "<div class=\"Chead\" style='border: 1px solid #000; border-radius: 5px; padding: 5px; box-shadow: 0 3px 1px rgba( 0, 0, 0, 0.3);' align='center'>";
    
    foreach( $raidLinks as $name => $url )
    {
        if( isset( $_SESSION['authmod'][$url] ) && $_SESSION['authmod'][$url] == 1 || is_admin() )
        {
            echo "<a href='admin.php?".$url."' class='buttons'>".$name."</a> ";
        }
    }
    
    echo "</div><br />";
} 
### Ist der User Stamm?
function isStamm($i){
	if( $_SESSION['stammgrp'][$i] ){
		return (TRUE);
	}else{
		return (FALSE);
	}
}
### Links
function aLink( $name, $pfad, $if=0 ){
	if( $if == 0 or $if == 1 ){
		return ( $if == 0 ? '<a href="index.php?'.$pfad.'">'.$name.'</a>' :'<a href="admin.php?'.$pfad.'">'.$name.'</a>');
	}elseif( $if == 2 ){
		return '<a href="'.$pfad.'">'.$name.'</a>';
	}
}
### Design Class
function cssClass($i){
	return ( $i == "Cmite" ? "Cnorm" : "Cmite" );
}
### Überprüfen ob User einen Char hat!
function exRaidChar($is=0){
	global $allgAr;
	if( loggedin() and RaidRechte($allgAr['addchar']) ){
		if( $_SESSION['charid'] != ''){
			return ( $is == 0 ? TRUE : '');
		}else{
			return ( $is == 0 ? FALSE : "Sie haben kein Char, <a href='index.php?chars-newchar'>Erstellen</a>." );
		}
	}else{
		return (FALSE);
	}
}
### Überprüfen ob Char Kalendereinträge hat wenn ein Char exestiert!
function isRaidKalender($is=0){
	$erg = db_result(db_query("SELECT COUNT(cid) FROM prefix_raid_kalender WHERE cid='".$_SESSION['charid']."'"),0);
	if( $erg != 0  ){ 
		return ( $is == 0 ? TRUE : '');
	}else{ 
		$ifChar = ( exRaidChar() ? "Es wurde kein Raid Kalender eintrag gefunden! <a href='index.php?chars-show-".$_SESSION['charid']."'>Nachtragen</a>." : "" );
		return ( $is == 0 ? FALSE : $ifChar);
	}
}
### Überprüfen ob Char eine Skillung hat wenn ein Char exestiert!
function isRaidSkillung($is=0){
	$erg = db_query("SELECT `s1`,`s2`,`s3` FROM `prefix_raid_chars` WHERE id='".$_SESSION['charid']."'");
	$sc = db_fetch_assoc($erg);
	if( $sc['s1'] != 0 or $sc['s2'] != 0 or $sc['s3'] != 0 ){ 
		return ( $is == 0 ? TRUE : '');
	}else{
	    $ifChar = ( exRaidChar() ? "Es wurde keine Skillung gefunden! <a href='index.php?chars-edit-".$_SESSION['charid']."'>Nachtragen</a>." : "" );
		return ( $is == 0 ? FALSE : $ifChar );
	}
}
### User die Raidleiter, SuperRaidleiter, Offiziere oder Höher sind bekommt rechte für die raid u. Dkp module Automatisch
#setModulrightsForCharRang(65,'insert');
function setModulrightsForCharRang($cid,$if){
	
	$res = db_query("SELECT 
						a.user, 
						b.module 
					 FROM prefix_raid_chars AS a 
						LEFT JOIN prefix_raid_rang AS b ON a.rang=b.id  
					 WHERE 
						a.id=".$cid." 
					 LIMIT 1");
					 
	$char = db_fetch_object( $res );
	
	$module = explode(",", $char->module );
	
	if( $if == 'insert' ){
		foreach( $module as $mid ){
			if( $mid != NULL ){
				@db_query("INSERT INTO prefix_modulerights (uid, mid) VALUES(".$char->user.", ".$mid.");");
			}
		}
	}elseif( $if == 'remove'){
		foreach( $module as $mid ){
			if( $mid != NULL ){
				@db_query("DELETE FROM prefix_modulerights WHERE uid=".$char->user." AND mid=".$mid);
			}
		}
	}
}
##############################################
function RaidPermission($rid=0, $onlyGaO=FALSE){

	$uid = ( $rid == 0 ? $rid : db_result(db_query('SELECT von FROM prefix_raid_raid WHERE id='. $rid),0)) ;
	
	if( $_SESSION['authid'] == $uid ){ # Eigentümer kann die eigenen Raids Bearbeiten!
		return (TRUE);
	}elseif( $_SESSION['charrang'] == 10 and $_SESSION['authid'] == $uid and $onlyGaO == FALSE ){ # Rang: Raidleiter
		return (TRUE);
	}elseif( $_SESSION['charrang'] == 11 and $onlyGaO == FALSE ){ # Rang: Super Raidleiter
		return (TRUE);
	}elseif( $_SESSION['charrang'] >= 13 ){ # Rang: Offiezier oder Höher
		return (TRUE);
	}elseif( is_admin() ){ # wenn alle kriterien nicht zu treffen ist der admin dafür verantwortlich!
		return (TRUE);
	}else{                 # Tja wenn dann immer noch niemand Rechte hat und jemand soweit kommt wird alles verweigert
		return (FALSE); 
	}
}
##############################################
function RaidRechte( $i ){
	if( $i >= $_SESSION['authright'] ){ return(TRUE); }else{ return (FALSE); }
}
##############################################
if( !function_exists( "DateFormat" ) )
{	function DateFormat( $format, $timestamp=0)# D für wochentag
	{ 	$wochentagRename = array( "Sun" => "So", "Mon" => "Mo", "Tue" => "Di", "Wed" => "Mi", "Thu" => "Do", "Fri" => "Fr", "Sat" => "Sa");
		$timestamp = DateToTimestamp( $timestamp );
		$timestamp = ( $timestamp == 0 ? time() : $timestamp );
		$return = date( $format, $timestamp );
		if( preg_match( "/D/" , $format ) ){
			foreach( $wochentagRename as $key => $value ){
				$return = str_replace( $key, $value, $return );
			}
		}
		return ($return);	
	}
}
### Array Daten überprüfen! # s1=0<10,email=email,name=is :::::::::  ,61>-s2,61>-s3,s1+s2+s3|61|skillung|skillpunkten=sum
#$arr = array( "s1" => "62", "s2" => "-9", "s3" => "-9" );
#echo arrDataCheck($arr, "61>-s1,61>-s2,61>-s3,s1=plus,s2=plus,s3=plus,s1+s2+s3|61|skillung|skillpunkten=sum", 1);

function arrDataCheck($arrValue, $chk, $fm=0){
	$i = 0;
	$SearchString = "^([0-9a-zA-Z][-]?)*[<>]([-]?[0-9a-zA-Z])*$";
	$eMailSearch = "^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-.]?[0-9a-zA-Z])*\\.[a-zA-Z]{2,3}$";
	$arrControl = explode( "," , $chk );
	#arrPrint( $arrControl );
	foreach( $arrControl as $val ){
		$i++;
		list( $feld, $check ) = explode("=", $val );
		if( $check == 'is' ){
			#echo $check . " 1";
			$insert = ( $fm == 0 ? $i : 'Das Feld <b>"'.ucfirst($feld).'"</b> ist immer noch Leer! (id #'.$i.')');
			$er[$insert] = ( !empty($arrValue[$feld]) ? TRUE : FALSE );
		}elseif( $check == 'email' ){
			#echo $check . " 2";
			$insert = ( $fm == 0 ? $i : 'Das Feld <b>"eMail"</b> ist endweder Leer oder dies ist kein eMail Adresse!' );
			$er[$insert] = ( eregi($eMailSearch, $arrValue[$feld]) ? TRUE : FALSE );
		}elseif( eregi($SearchString, $val ) ){
			#echo $check . " 3";
			if( eregi(">-", $val) ){
				#echo $check . " 4";
				list( $a, $b ) = explode(">-", $val);
				$insert = ( $fm == 0 ? $i : 'Die Zahl '.$a.' >= '.$arrValue[$b].' vom Feld "'.$b.'" und so mit Falsch (id #'.$i.')' );
				$er[$insert] = ( $a >= $arrValue[$b] ? TRUE : FALSE );
			}elseif( eregi("<-", $check) ){
				#echo $check . " 5";
				list( $a, $b ) = explode("<-", $val);
				$insert = ( $fm == 0 ? $i : 'Die Zahl '.$a.' <= '.$arrValue[$b].' vom Feld "'.$b.'" und so mit Falsch (id #'.$i.')' );
				$er[$insert] = ( $a <= $arrValue[$b] ? TRUE : FALSE );
			}elseif( eregi("->", $check) ){
				#echo $check . " 6";
				list( $a, $b ) = explode("->", $val);
				$insert = ( $fm == 0 ? $i : 'Die Zahl '.$arrValue[$a].' => '.$b.' vom Feld "'.$a.'" und so mit Falsch (id #'.$i.')' );
				$er[$insert] = ( $arrValue[$a] >= $b ? TRUE : FALSE );
			}elseif( eregi("-<", $check) ){
				#echo $check . " 7";
				list( $a, $b ) = explode("-<", $val);
				$insert = ( $fm == 0 ? $i : 'Die Zahl '.$arrValue[$a].' =< '.$b.' vom Feld <b>"'.$a.'"</b> und so mit Falsch (id #'.$i.')' );
				$er[$insert] = ( $arrValue[$a] <= $b ? TRUE : FALSE );
			}
		}elseif( $check == 'int' ){
			#echo $check . " 8";
			$insert = ( $fm == 0 ? $i : 'Das Feld <b>"'.ucfirst($feld).'"</b> hat einen Falschen Wert! (id #'.$i.')' );
			$er[$insert] = ( $arrValue[$feld] > 0 ? TRUE : FALSE );
		}elseif( $check == 'sum' ){
			#echo $check . " 9";
			# s1+s2+s3|61|skillung|skillpunkten=sum
			list( $felder, $max, $feldname, $info) = explode( "|", $feld );
			$split = explode("+", $felder );
			$add = 0;
			foreach( $split as $val ){
				$add += $arrValue[$val];
			}
			$insert = ( $fm == 0 ? $i : 'Die Felder für die <b>"'.ucfirst($feldname).'"</b> haben einen Falschen wert! Du hast '.$add.' von '.$max.' möglichen '.
			ucfirst($info).' (id #'.$i.')');
			$er[$insert] = ( $max >= $add ? TRUE : FALSE );
		}elseif( $check == 'plus' ){
			$insert = ( $fm == 0 ? $i : 'Der Wert vom Feld <b>"'.ucfirst($feld).'"</b> liegt unter Null und somit Falsch! (id #'.$i.')' );
			$er[$insert] = ( $arrValue[$feld] >= 0 ? TRUE : FALSE );
		}
	}
	
	if( $fm == 0 ){
		foreach( $er as $key => $value ){
			if( $value == 1 ){
				$return = TRUE;
			}else{
				$return = FALSE;
				break;
			}
		}
	}elseif( $fm == 1 ){
		foreach( $er as $key => $val ){
			$return .= ( $val ? '' : $key ."<br>\n" );
		}
		$return .= button("Zurück", "", 9 );
	}else{
		echo "FEHLER";
	}
	
	return ($return);
}
### allgAr Daten
function allgArInsert( $string ){
	global $allgAr;
	foreach( $allgAr as $key => $val ){
		if( eregi( "{".$key."}", $string )){
			$string = str_replace("{".$key."}", $val, $string );
		}
	}
	
	return ($string);
}
### Bytes umrechnen.
function btokbormb($size, $round = 2)
{  
    if ( $size < 1024 ){ 
        return $size." B"; 
    }elseif( $size < pow(1024,2) ){ 
        $size_kb = $size/1024; 
        $size_kb = round($size_kb,$round); 
        return $size_kb." KB"; 
    }elseif( $size < pow(1024,3) ){ 
        $size_mb = $size/1024; 
        $size_mb = $size_mb/1024; 
        $size_mb = round($size_mb,$round); 
        return $size_mb." MB"; 
    }elseif( $size < pow(1024,4) ){
		$size_gb = $size/1024;
		$size_gb = $size_gb/1024;
		$size_gb = $size_gb/1024;
		$size_gb = round($size_gb,$round);
		return $size_gb." GB";
	}elseif( $size < pow(1024,5) ){
		$size_tb = $size/1024;
		$size_tb = $size_tb/1024;
		$size_tb = $size_tb/1024;
		$size_tb = $size_tb/1024;
		$size_tb = round($size_tb,$round);
		return $size_tb." TB";
	}
}
##############################################
function CountDirs( $pfad )
{ 
 	$open = @opendir( $pfad );
	$i = 0;
 	while( $dir = @readdir( $open )){
		if( $dir != "." && $dir != ".." && is_dir( $pfad . $dir )){
			$i++;
		}
 	}
	@rewind($open);
 	@closedir( $open );
	return $i;
}
##############################################
function CountFiles( $pfad )
{ 
 	$open = @opendir( $pfad );
	$i = 0;
 	while( $files = @readdir( $open )){
		if( is_file( $pfad . $files )){
			$i++;
		}
 	}
	@rewind($open);
 	@closedir( $open );
	return $i;
}
##############################################
function SizeDir( $pfad )
{ 
 	$open = @opendir( $pfad );
	$i = 0;
 	while( $files = @readdir( $open )){
		if( is_file( $pfad . $files )){
			$i = $i + filesize($pfad . $files);
		}
 	}
	@rewind($open);
 	@closedir( $open );
	return $i;
}
##############################################
function ServerSize($pfad = './'){
	$aSize = 0;
	$open = @opendir( $pfad ); 
	while( $res = @readdir( $open )){
		if( "." != $res && ".." != $res ){
			$way = $pfad.$res."/";
			if( is_dir( $way ) ){
				$aSize += ServerSize($way);
			}else{
				$aSize += filesize($pfad.$res);
			}
		}
	}
	@closedir( $open );
	return $aSize;
}
##############################################
function FileRead( $pfad ){
	if( file_exists( $pfad )){
		if( $open = fopen( $pfad, 'r' )){
			$size = filesize( $pfad );
			$read = fread( $open, $size );
			fclose($open);
			return $read;
		}else{
			return "Datei kann nicht Gelesen Werden!";
		}
	}else{
		return "Datei Exestiert nicht!";
	}
}
##############################################
function CreatFile( $pfad, $text )
{
	if($open = @fopen( $pfad, "w+" )){
		@fputs( $open, $text );
		@fclose( $open );
		return true;
	}else{
		return false;
	}
}
##############################################
function arrServerStrucktur($pfad){
	$open = @opendir( $pfad ); 
	while( $res = @readdir( $open )){
		if( "." != $res && ".." != $res ){
			$way = $pfad.$res."/";
			if( is_dir( $way ) ){
				$arrDir[$res] = $way;
				$arrDir[$res] = arrServerStrucktur($way);
			}else{
				$arrDir[$res] = $pfad.$res;
			}
		}
	}
	@closedir( $open );
	return $arrDir;
}
##############################################
function is_img( $pfad ){
	if( eregi('.jpg', $pfad) || eregi('.png', $pfad) || eregi('.gif', $pfad) || eregi('.bmp', $pfad) ){
		return (true);
	}else{
		return (false);
	}
}
##############################################
function img_popup( $pfad, $feld, $select = NULL ){
	$open = opendir( $pfad );
	$return = '<select name="'.$feld.'" id="name">';
	$return .= "<option value=''></option>";
	$return .= "<option value='true'><-Bild Hochladen-></option>";
	while( $pic = readdir( $open )){
		if( is_file( $pfad.$pic )){
			$sel = ( $pic == $select ? " selected" : "" );
			$return .= "<option value='".$pic."'".$sel.">".$pic."</option>";
		}
	}
	$return .= '</select>';
	closedir($open);
	return ($return);
}
##############################################
function drop_down_menu($sql, $input, $select, $id, $where = false){
	if( $id != "" ){ $iid = $id."_"; }else{ $iid = ""; }
	if( $where ){
		$res = db_query( $sql );
	}else{
		$res = db_query( "SELECT * FROM ".$sql );
	}
	$menu = "<select name='".$iid.$input."'>\n";
	$menu .= "\t<option value=''>....</option>\n";
	while( $row = db_fetch_assoc( $res )){
		$key = array_keys($row);
		if( $select != $row[$key[0]] ){
			$menu .= "\t<option value='".$row['id']."'>".$row[$key[1]]."</option>\n";
		}else{
			$menu .= "\t<option value='".$row['id']."' selected>".$row[$key[1]]."</option>\n";
		}
	}
	$menu .= "</select>\n";
	return $menu;
}
###############################################
function db_value( $db, $feld, $id, $and = "" ){
	if( $id != 0 ){
		$res = db_query("SELECT ".$feld." FROM ".$db." WHERE `id`='".$id."' ".$and);
		return db_result( $res, 0 );
	}else{
		return "Fehler, id #0 Datenbank!";
	}
}
###############################################
function db_value_field( $db, $feld, $id, $from, $and = "" ){
	$res = db_query("SELECT ".$feld." FROM ".$db." WHERE ".$from."='".$id."'".$and);
	return db_result( $res, 0 );
}
###############################################
function db_cfg( $key ){
	$res = db_query("SELECT value FROM prefix_raid_cfg WHERE way='".$key."'");
	return db_result( $res, 0 );
}
###############################################
function drop_down_int( $von, $bis, $name, $format, $id = "" ){
	#if( $id == 0 ){ $id = ""; }else{ $id = $id; }
	$save = "<select name='".$name."'>\n";
	$save .= "<option value='0'>".$format."</option>\n";
	for( $i=$von; $i < $bis+1; $i++){
		if( strlen( $i ) < 2 ){ $pi = 0; }else{ $pi = ""; }
		$selected = ( $id == $i ? 'selected' : '');
		$save .= "<option value='".$pi.$i."' ".$selected.">".$pi.$i."</option>\n";
	}
	$save .= "</select>";
	return $save;
}
###############################################
### Diese funktion sitzt noch an manschen stellen im Script, denn davor kannte ich mich noch nicht mit MySQL - SUM() aus ^^
function dkp($cid, $gid){
	$res = db_query("SELECT pm, dkp FROM prefix_raid_dkp WHERE dkpgrp = '".$gid."' AND cid='".$cid."'");
	$dkp = 0;
	while( $row = db_fetch_assoc( $res )){
		$row['dkp'] = str_replace("-","", $row['dkp'] );
		if( $row['pm'] == "+" ){
			$dkp += $row['dkp'];
		}elseif( $row['pm'] == "-" ){
			$dkp -= $row['dkp'];
		}
	}
	return $dkp;
}
###############################################
function bossinfos($ini, $rid){
	$cssPfad = 'include/designs/'.$_SESSION['authgfx'].'/';
	$cssErmitteln = opendir( $cssPfad );
	while( $css = readdir( $cssErmitteln )){
		 if( ereg('.css', $css ) ){ $cssFile = $css; break; }else{ $cssFile = NULL; }
	}
	closedir($cssErmitteln);
	$ret .= "<title>Boss Info</title>";
	$ret .= "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
	$res = db_query("SELECT id, bosse, img FROM prefix_raid_bosse WHERE inzen = '".$ini."'");
	$resRaid = db_query("SELECT inv, pull, ende, gruppen FROM prefix_raid_raid WHERE id=".$rid);
	$raid = db_fetch_assoc( $resRaid );
	$inz = db_result(db_query("SELECT name FROM prefix_raid_inzen WHERE id =". $ini),0);
	$cb = db_result(db_query("SELECT maxbosse FROM prefix_raid_inzen WHERE id=".$ini),0);
	$ck = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter WHERE rid='".$rid."'"),0);
	$ret .= '<table width="100%" border="0" cellspacing="1" cellpadding="5" class="border">';
	$ret .= '<tr><td colspan=3 class="Chead"><b>'.$inz.'  Raid Fortschritt</b></td></tr>';
	$ret .= "<tr class='Cdark'><td colspan=3>";
	$ret .= "Raid Datum: ".DateFormat("D d.m.Y",$raid['inv']) . "<br>";
	$ret .= "Raid Invite: ".DateFormat("H:i",$raid['inv']) . "<br>"; 
	$ret .= "Raid Pull: ".DateFormat("H:i",$raid['pull']) . "<br>";
	$ret .= "Raid ende: ".DateFormat("H:i",$raid['ende']) . "<br>"; 
	$ret .= $ck."/".$cb." Bosse sind Tot"; 
	$ret .= "</td></tr>";
	$ret .= "<tr class='Cnorm'><td colspan=3>".pzVortschritsAnzeige($ck, $cb, "Vortschritt:")."</td></tr>";
	$ret .= "<tr><td colspan=3 class='Cdark'>Aktion: ".button("Aktualisieren","",11).button("Schließen","",7)."</td></tr>";
	if( db_num_rows($res) != 0 ){
		while( $row = db_fetch_assoc( $res )){
			$class = cssClass($class);
			$ret .= "<tr>";
			$ret .= '<td width="75" class="'.$class.'"><center><img height=50 src="include/raidplaner/images/bosse/'.$row['img'].'"></center></td>';
			$ret .= '<td  class="'.$class.'" nowrap><b>'.$row['bosse'].'</b></td>';
			$erg = @db_result(@db_query("SELECT id FROM prefix_raid_bosscounter WHERE rid='".$rid."' AND bid='".$row['id']."'"),0);
			$time = @db_result(@db_query("SELECT time FROM prefix_raid_bosscounter WHERE rid='".$rid."' AND bid='".$row['id']."'"),0);
			if( $erg != "" ){
				$ret .= '<td bgcolor="GREEN">'.'<center><b><font color="#FFFFFF">ELIMINIERT</font></b><br>Kill: '.date("H:i:s",$time).'</center>'.'</td>';
			}else{
				$ret .= '<td bgcolor="RED">'.'<b><font color="#FFFFFF"><center>ALIVE</center></font></b>'.'</td>';
			}
			
			$ret .= "</tr>";
		}
	}else{
		$ret .= "<tr><td colspan=3 class='Cnorm'>Es wurden keine Bosse in die Datenbank eingetragen.</td></tr>";
	}
	$ret .= "<tr><td colspan=3 class='Cdark'>Aktion: ".button("Aktualisieren","",11).button("Schließen","",7)."</td></tr>";
	$ret .= "</table>";
	return $ret;
}
###############################################
### Habe keine gut lösung für diese function gefunden ^^ wenn jemand eine bessere idee hat so meldet sie mir ^^
function char_skill( $a, $b, $c, $d, $e = 11 ){ # a=skill 1 b=skill 2, c=skill 3, d=klassenid, e=berechnungswert
	
	return $a ." / ". $b;
}
### SKILLGRUPPE
function skillgruppe($opt=0,$checked=0){
	if( $opt == 0 ){
		$gruppen = array( "0"=>"n/a", "1"=>"Tank", "2"=>"Healer", "3"=>"Damage Dealer" );
		return ($gruppen[$checked]);
	}elseif( $opt == 1 ){
		$gruppen = array( "1"=>"Tank", "2"=>"Healer", "3"=>"Damage Dealer" );
		foreach( $gruppen as $id => $value ){
			$checkin = ( $checked == $id ? 'checked="checked"' : '' );
			$return .= "<label><input type='radio' name='skillgruppe' value='".$id."' ".$checkin."> ".$value."</label><br>";
		}
		return ($return);
	} 
}
####
function class_img($i){
	$link = 'include/images/wowklein/'.$i.'.gif';
	if( file_exists($link)){
		return "<img src='".$link."'>";
	}
}
####
function pz($a, $b, $c = 0){
	if( $a == 0 or $b == 0 ){
		return (0);
	}else{
		return round( ( $a * 100 ) / $b , $c );
	}
	
}
####
function pzVortschritsAnzeige($a, $b, $msg='', $r=0){
	global $allgAr;
	$pz = pz($a,$b,$r);
	$msg = ( !empty( $msg ) ? "<tr><td class='Chead' colspan=3>".$msg."</td></tr>" : "" );
	return ("<table width='100%' class='border' cellspacing=1 cellpadding=1 border=0>"
	.$msg
	."<tr>"
	."<td width='10%' class='Cnorm'><div align='center'>000%</div></td>"
	."<td width='80%' class='Cmite'>"
	."<table width='".$pz."%' class='border' cellspacing=1><tr><td style='".$allgAr['pzBalkenStyle']."'><div align='right'>".$pz."%</div></td></tr></table>"
	."</td>"
	."<td width='10%' class='Cnorm'><div align='center'>100%</div></td>"
	."</tr>"
	."</table>");
}
####
function button( $name, $url, $if = 0 ){
	global $menu, $allgAr;
	
	$who = $_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];
	
	$arrAction = array(6 => 'javascript:window.close()',
					   7 => 'javascript:window.close()',
					   8 => 'javascript:history.back()',
					   9 => 'javascript:history.back()',
					  10 => $_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0],
					  11 => $_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0]);
					  
	foreach( $arrAction as $key => $val ){
		if( $key == $if ){
			$url = $val;
			break;
		}
	}
	
	
	$bt = "<a href='{url}' title='{name}' class='buttons'>{name}</a> ";
	$bt = str_replace( "{name}", $name, $bt);
	$bt = str_replace( "{url}", $url, $bt);
	
	
	switch( $if ){
		case "0": print $bt; break;
		case "1": if( user_has_admin_right ($menu,false) ){ print $bt; } break;
		case "2": return $bt; break;
		case "3": if( user_has_admin_right ($menu,false) ){ return $bt; } break;
		case "4": if( is_admin() ){ print $bt; } break;
		case "5": if( is_admin() ){ return $bt; } break;
		case "6": print $bt; break;
		case "7": return $bt; break;
		case "8": print $bt; break;
		case "9": return $bt; break;
		case "10": print $bt; break;
		case "11": return $bt; break;
		case "12": print ( RaidPermission( 0, TRUE) ? $bt : '' );
		case "13": return ( RaidPermission( 0, TRUE) ? $bt : '' );
	}
}
####
function ascape( $string ){
	if( is_integer( $string )){
		$option = 'integer';
	}elseif( is_string( $string ) && strlen( $string ) > 250 ){
		$option = 'textarea';
	}else{
		$option = 'string';
	}
	return escape( $string, $option );
}
#############
##############
# Ist ein Experiment
# Suche noch immer eine Deutsche, Suchmuster Tabelle.
function include_data( $search, $string ){
	global $allgAr;
	preg_match_all("/\{_".$search."_([^\{\}]+)\}/", $string, $array );
	#print_r( $array );
	$pfad = 'include/boxes/';

	foreach( $array[1] as $key => $value ){
		ob_start();
		include($pfad.$value.".php");
		$buffer[$key] = ob_get_contents();
		ob_end_clean();
		$string = str_replace($array[0][$key], $buffer[$key] ,$string);
	}
	return $string;
}
####
function include_session( $string ){
	preg_match_all("/\{_session_([^\{\}]+)\}/", $string, $array );
	foreach( $array[1] as $key => $value ){
		$string = str_replace($array[0][$key], $_SESSION[$array[1][$key]] ,$string);
	}
	return $string;
}
####
function includer( $a ){
	#$a = include_data( 'boxes', $a );
	$a = include_session( $a );
	return $a;
}
### Automatische Tabelle Generieren ########################################################################################
# Für Kleine unaufwendige Tabellen, geignet.
# Hinweise, um einen Lösch Icon hinzuzubekommen mus man in der SQL Abfrage beispiel das feld id as del umbenen, zudem muss
# auf $db_table_del ein True zugewisen werden als function oder "true" umd das löschen zu Aktivieren. In einem fall habe
# ich das so gemacht "$db_table_del = is_admin();" wenn einer admin ist die function true zurück geben.
# das feld $ord ist für den anfang der Tabelle für die erste spalte damit nicht alles in <center> angeordnet wird.
# $db_table_breite hier wird die breite für jede selbst definiert die dan zu % wird, bsp. $db_table_breite = 50;
# nun ist jede spalte 50% groß.
# $db_table_url weist für den löschlink die url zu die dan noch um $_GET erweitert wird.
############################################################################################################################
function db_table( $sql, $ord  ){
	global $db_table_breite, $db_table_del, $db_table_url;
	# Die Breite im Global bestimmen.
	$breite = ( $db_table_breite != '' ? $db_table_breite."%" : "" );
	# Eine Abfrage als Unsichpaar erstellen.
	$img_del = "<img src='include/images/icons/del.gif' border='0'>";
	# Die Erste Datenbank herausfiltern!
	preg_match("/prefix_([_]?[0-9a-zA-Z])*/", $sql, $db);
	#print_r($array);
	
	if($res = db_query( $sql )){
		### Header
		$anz = mysql_num_fields($res);
		$num = db_num_rows($res);
		$table .= "<table width='100%' border='0' cellpadding='2' cellspacing='1' class='border'>";
		$table .= "<tr>";
		for($a=0; $a < $anz; $a++ ){
			$orda = ( $a == $ord ? 'left' : 'center' );
			if( mysql_field_name($res,$a) != "del" ){
				$table .= "<td class='Chead' width='".$breite."'>"
				."<center><div align='".$orda."'><b>"
				. ucfirst(str_replace("_"," ",mysql_field_name($res,$a))) 
				."</b></div></center></td>";
			}else{
				if( $db_table_del ){
					$table .= "<td class='Chead' width='0%' valign='top'>". $img_del ."</td>";
				}else{
					$table .= "";
				}
			}
		}
		
		$table .= "</tr>";
		### Content
		for($b=0; $b < $num; $b++ ){
			$class = ( $class == 'Cmite' ? 'Cnorm' : 'Cmite' );
			$table .= "<tr class='".$class."'>";
			$row = mysql_fetch_assoc($res);

			# Autmatische Spalten erstellen!
			for($c=0; $c < $anz; $c++){
				$ordc = ( $c == $ord ? 'left' : 'center' );
				$fn = mysql_field_name($res,$c);
				if( $fn != "del" ){
					$table .= "<td valign='top'><div align=".$ordc.">". bbcode($row[$fn]) ."</div></td>";
				}else{
					if( $db_table_del ){
						$table .= "<td valign='top'><div align='".$ordc."'>"
						."<a href='".$db_table_url."&del=".$row[$fn]."&table=".$db[0]."'>". $img_del ."</a></div></td>";
					}else{
						$table .= "";
					}
				}
			}
			$table .= "</tr>";
		}
		### Footer
		$table .= "</table>";
		
		print $table;
	}else{
		print "MySQL ERROR";
	}
}
####
function dbtpl($key){
	return db_result(db_query("SELECT tpl FROM prefix_raid_template WHERE `key`='".$key."'"),0);
}
####
if( !function_exists( "Alter" ) )
{	function Alter( $date )
	{	if( preg_match( DateFormate('DATE'), $date, $tmp)){ $bj = $tmp[3]; $bm = $tmp[2]; $bt = $tmp[1]; }
		elseif( preg_match( DateFormate('MYSQL'), $date, $tmp)){ $bj = $tmp[1]; $bm = $tmp[2]; $bt = $tmp[3]; }
		else { return "00"; }
		
		$j = date("Y")-$bj;
		if( $bm > date("m") ) $j--;
		if( $bm == date("m") AND $bt > date("d")) $j--;
		return $j;
	}
}
####

################################################################################################################################
## Blätter classe für eine forschleife ### Habe früher sehr sehr viel mit dem Datei System programiert!
## Die ist Alt aber sie functioniert noch ^^, ob wohl mann sie etwas abspecken könnte, werde ich mal machen wenn ich mehr zeit habe.
class blaetter   
{   
    var $counter;   
    var $config;  
    var $site;  
      
    function msa()  ### Hier erfolg die Ausgabe für den anfang der for Schleife
    {  
        $seite = array();       
        $pages = $this->counter / $this->config;  
        $pages = ceil($pages);  
        $start = 0;  
        $seite[0] = "";  
        for($i=1; $i < $pages+1; $i++){  
            $seite[] = $start;  
            $start = $start + $this->config;  
        }
		 
        if( !empty( $this->site )){
        	return $seite[$this->site];
		}else{
			return 0;
		}     
    }  
      
    function mse()  ### Hier erfolg die Ausgabe für das ende der for Schleife
    {  
        $seite = array();       
        $pages = $this->counter / $this->config;  
        $pages = ceil($pages);  
        $stop = $this->config;  
        $seite[0] = "";  
        for($i=1; $i < $pages+1; $i++){  
            $seite[] = $stop;  
            $stop = $stop + $this->config;  
        }
		
		if( !empty( $this->site )){
        	return $seite[$this->site];
		}else{
			return $this->config;
		}      
    }  
           
    function msl($pfad, $post, $maxshow = 2) ### Hier werden die Hyperlinks erzeugt womit mann Blättern kann!
    {    
        $link = "";       
        $pages = $this->counter / $this->config;  
        $pages = ceil($pages);
		$max_anzeigen = $maxshow;
		$aktive_seite = $this->site;
		if( empty( $aktive_seite )){
			$aktive_seite = 1;
		}else{
			$aktive_seite = $this->site;
		}
		### Den anfang bestimmen
		if( $aktive_seite > $max_anzeigen ){
			$zeige_start = $aktive_seite - $max_anzeigen;
		}else{
			$zeige_start = 1;
		}
		### Das ende Bestimmen
		if( $aktive_seite <= $pages - $max_anzeigen ){
			$zeige_ende = $aktive_seite + $max_anzeigen;
		}else{
			$zeige_ende = $pages;
		}
		### Ein Sprung bis zu 1 und ein schrit zurück
		if( $aktive_seite != 1 ){
			$link .= "[<a href='".$pfad."&".$post."=1'>1</a>]";
			$seite_sprung = $aktive_seite - 1;
			$link .= "[<a href='".$pfad."&".$post."=".$seite_sprung."'><b>&laquo;</b></a>] - ";
		}
		### Dynamische seitenanzhal ausgeben!
		for($i=$zeige_start; $i < $zeige_ende+1; $i++){  
            if( $aktive_seite != $i ){  
                $link .= "[<a href='".$pfad."&".$post."=" . $i . "'>". $i ."</a>]";
            }else{  
                $link .= "[<b>". $i ."</b>]";  
            }  
        } 
		### Ein Sprung bis zum ende
		if( $aktive_seite != $pages && $aktive_seite > 1 || $aktive_seite != $pages && $pages > 1){
			$seite_sprung = $aktive_seite + 1;
			$link .= " - [<a href='".$pfad."&".$post."=".$seite_sprung."'><b>&raquo;</b></a>]";
			$link .= "[<a href='".$pfad."&".$post."=".$pages."'>".$pages."</a>]";
		}
        return $link;  
    } 
}

/* Ist nur eine Kopie aus show_posts.php vom Forum
	require_once("include/includes/func/b3k_func.php");
	if( $res = db_query( "SELECT a.name, b.klassen, c.level, a.id FROM prefix_raid_chars AS a, prefix_raid_klassen AS b, prefix_raid_level AS c WHERE user='".$row['erstid']."' AND a.klassen=b.id AND a.level=c.id ORDER BY a.id LIMIT 1" ) ){
		$x = mysql_fetch_array($res);
		$row['char'] = "<a href='index.php?chars-show-".$x[3]."'>".$x[0]."</a>";
		$row['lvl'] = $x[2];
		$row['klassen'] = class_img($x[1]);
	}else{
		$row['char'] = '';
		$row['lvl'] = '';
		$row['klassen'] = '';
	}
	
*/
function arrPrint( $arr ){
	echo "<pre>";
	print_r( $arr );
	echo "</pre>";
}
if( !function_exists( "DateFormate" ) )
{	function DateFormate( $key )
	{	$formate = array(
			"MYSQL" => "/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", 
			"MYSQLaTIME" => "/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/",
			"DATE" => "/([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})/",
			"DATEaTIME" => "/([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/");
		
		return $formate[$key];
	}
}
#### Datum zum Timestamp umwandeln.
if( !function_exists( "DateToTimestamp" ) )
{	function DateToTimestamp( $date )
	{	global $formate;
		if( !empty( $date ) )
		{ 	if( preg_match( DateFormate('MYSQLaTIME'), $date, $tmp ) ){ return mktime( $tmp[4], $tmp[5], $tmp[6], $tmp[2], $tmp[3], $tmp[1]); }
			elseif( preg_match( DateFormate('DATEaTIME'), $date, $tmp ) ) { return mktime( $tmp[4], $tmp[5], $tmp[6], $tmp[2], $tmp[1], $tmp[3]); }
			elseif( preg_match( DateFormate('DATE'), $date, $tmp ) ) { return mktime( 0, 0, 0, $tmp[2], $tmp[1], $tmp[3]); }
			elseif( preg_match( DateFormate('MYSQL'), $date, $tmp ) ){ return mktime( 0, 0, 0, $tmp[2], $tmp[3], $tmp[1]);}
			else { return $date; }	
		}	
	}
}
### ITEMS on BUFFED!!
### DKP
function RaidItems( $txt, $i )
{	if( preg_match_all( "/\{_raiditem_([0-9]*)\}/", $txt, $items ) )
	{	$rpl = $set = array();
		foreach( $items[1] as $k => $id )
		{	$rpl[] = $items[0][$k];
			$iName = @db_result(@db_query("SELECT name FROM prefix_raid_items WHERE id='".$id."' LIMIT 1"),0);
			$iClass = @db_result(@db_query("SELECT class FROM prefix_raid_items WHERE id='".$id."' LIMIT 1"),0);
			$image = "http://wowdata.buffed.de/tooltips/items/gif/".$id.".gif";
			$set[] = ( empty( $iName ) ? "" : "<br /><span style='font-size: 9px;'>Loot: </span><a href='http://wowdata.buffed.de/?i=".$id."' target='_blank' class='".$iClass."'>".$iName."</a> ");
		}
		return str_replace($rpl, $set, $txt);
	}
	
	return $txt;
}

function agoTimeMsg( $wert, $lastMsg = 'vor wenigen Sekunden' )
{	$TIME_AGO_sec = round( time() - DateToTimestamp($wert) );
	$TIME_AGO_min = round( $TIME_AGO_sec / 60);
	$TIME_AGO_hrs = round( $TIME_AGO_min / 60);	
	$TIME_AGO_day = round( $TIME_AGO_hrs / 24);
	$TIME_AGO_wek = round( $TIME_AGO_day / 7);
	$TIME_AGO_yea = round( $TIME_AGO_day / 365);
	$TIME_AGO_mon = round( $TIME_AGO_day / 30.42, 0); # 30,42 Tage Durschschnit für ein Monat im Jahr
	
	if($TIME_AGO_sec > ( 86400 * 365 )) return 'vor '. $TIME_AGO_yea .' '.( $TIME_AGO_yea > 1 ? "Jahren" : "Jahr");
	elseif($TIME_AGO_day > 30) return 'vor '. $TIME_AGO_mon .' '.( $TIME_AGO_mon > 1 ? "Monaten" : "Monat");
	elseif($TIME_AGO_sec > ( 86400 * 7 )) return 'vor '. $TIME_AGO_wek .' Wochen';
	elseif ($TIME_AGO_sec > 86400) return 'vor '.$TIME_AGO_day.' Tagen';
	elseif ($TIME_AGO_sec > 3600) return 'vor '.$TIME_AGO_hrs.' Stunden';
	elseif ($TIME_AGO_sec > 60) return 'vor '.$TIME_AGO_min.' Minuten';
	else return $lastMsg;
}

function urlSerialize( $array )
{	return urlencode(serialize( $array ));
}

function urlUnserialize( $string )
{	$string = urldecode( $string );
	return unserialize($string);
}

function nuller( $i )
{	return ( strlen( $i ) == 1 ? "0".$i : $i );
}

?>