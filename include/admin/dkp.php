<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );
$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

$design->header();

$tpl = new tpl ( 'raid/dkp.htm',1 );

if( !RaidPermission( $menu->get(1) ) ){ echo 'don\'t Permission'; $design->footer(); exit(); }

aRaidMenu();

$authid = $_SESSION['authid'];

if( $menu->get(4) == "rezzen" ){
    $bid = $menu->get(5);
    $rid = $menu->get(1);
    db_query("DELETE FROM prefix_raid_bosscounter WHERE bid=".$bid." AND rid=".$rid." LIMIT 1");
    wd('admin.php?dkp-'.$menu->get(1).'-'.$menu->get(2), 'Boss wurde Wiederbelebt!', 0);
    $design->footer();
    exit();
}

function statmsg($ssid){
    $abf = db_query("SELECT * FROM prefix_raid_statusmsg WHERE sid='2' or sid='3';");
    $opt = "";
    while( $r = db_fetch_assoc( $abf )){
        if( $r['id'] != $ssid  ){
            $opt .= "<option value='".$r['id']."'>".$r['statusmsg']."</option>\n";
        }else{
            $opt .= "<option value='".$r['id']."' selected>".$r['statusmsg']."</option>\n";
        }
    }

    return $opt;
}

switch( $menu->get(3) ){
    case "dkp":	
        $dkp = json_decode( $_POST['punkte'] );
        $boss = json_decode( $_POST['boss'] );

		
       
        $info = (empty($_POST['sinfo']) ? $dkp->name : $_POST['sinfo']);
        $dkp = (empty($_POST['sdkp']) ? $dkp->dkp : $_POST['sdkp']);

        $info .= ( empty( $boss->bossname ) ? "" : ": ". $boss->bossname );

        if( isset($_POST['chars']) )
        {	foreach( $_POST['chars'] as $value )
                {	$user = json_decode( $value );

                        $sql = 'INSERT INTO `prefix_raid_dkp` (`id`, `rid`, `dkpgrp`, `cid`, `uid`, `dkp`, `info`, `date`) 
                                        VALUES (NULL, 
                                        \''.$menu->get(1).'\',
                                         \''.$menu->get(2).'\',
                                          \''.$user->char.'\',
                                           \''.$user->user.'\',
                                            \''.$dkp.'\',
                                             \''.$info.'\',
                                              \''.time().'\');'; 

                        db_query( $sql );
                }
        }

        $res = db_query("SELECT inzen, bosskey FROM prefix_raid_raid WHERE id='".$menu->get(1)."' LIMIT 1");
        $row = db_fetch_assoc( $res );

        $BossKey = ( $row['bosskey'] == 0 ? $menu->get(1) : $row['bosskey'] );

        $isBossKilled = @db_result(db_query("SELECT id FROM prefix_raid_bosscounter WHERE rid='".$BossKey."' AND bid='".$boss->bossid."'"),0);

        if( !empty($boss->bossid) && empty($isBossKilled)){
                $sql = 'INSERT INTO `prefix_raid_bosscounter` (`id`, `bid`, `grpid`, `rid`, `iid`, `time`) 
                VALUES (NULL, \''.$boss->bossid.'\', \''.$menu->get(2).'\', \''.$BossKey.'\', \''.$row['inzen'].'\', \''.time().'\');';
                db_query( $sql );
        }

        if( isset( $itemID ) ){ 
            $iClass = $_POST['itemClass'];
            $iClass = (empty( $iClass ) ? "itemStandart" : $iClass );
            $drop = db_result(db_query("SELECT `drop` FROM prefix_raid_items WHERE id='".$itemID."' LIMIT 1"),0);
            db_query("UPDATE prefix_raid_items SET `drop`='".($drop+1)."' , bid='".$boss->bossid."' , iid='".$row['inzen']."', class = '".$iClass."' WHERE id='".$itemID."' LIMIT 1");
        }
    break;

    case "status":
        if( isset($_POST['all']) ){
            foreach( $_POST['cid'] as $cid ){
                db_query("UPDATE prefix_raid_anmeldung SET stat='".$_POST['all']."' WHERE rid='".$menu->get(1)."' AND `char`='".$cid."'");
            }
        }
    break;
    case "del":
        db_query("DELETE FROM prefix_raid_dkp WHERE id='".$menu->get(4)."'");
    break;
    case "addchar":
        $id = db_result(db_query("SELECT user FROM prefix_raid_chars WHERE id='".$_POST['chars']."'"), 0);
        db_query("INSERT INTO `prefix_raid_anmeldung` (`id` ,`rid` ,`grp` ,`char` ,`user` ,`kom` ,`stat`,`timestamp`) 
                                VALUES ( NULL , 
                                '".$menu->get(1)."',
                                '".$menu->get(2)."',
                                 '".$_POST['chars']."',
                                  '".$id."',
                                   '".$_POST['admsg']."',
                                    '".$_POST['stat']."',
                                     '".time()."');");

    break;
    case "versort":
        $_SESSION['dkpsort'] = $_POST['versort'];
        $_SESSION['updown'] = $_POST['updown'];
    break;
    case "beenden":
        db_query("UPDATE prefix_raid_raid SET statusmsg='2' WHERE id='".$menu->get(1)."'");
    break;
    case "oben":
        db_query("UPDATE prefix_raid_raid SET statusmsg='1' WHERE id='".$menu->get(1)."'");
    break;
    case "ADel":
        db_query("DELETE FROM prefix_raid_anmeldung WHERE id=".$menu->get(4) );
        echo "<b>L&ouml;schen war erfolgreich!</b>";
    break;
}

$InzenID = db_result(db_query("SELECT inzen FROM prefix_raid_raid WHERE id='".$menu->get(1)."'"), 0);

### Spieler hinzufügen!
$res = db_query("
    SELECT  
        a.char,
        a.user,
        b.name 
     FROM
        prefix_raid_anmeldung AS a, 
        prefix_raid_chars AS b 
     WHERE
        a.char = b.id AND 
        a.rid = '".$menu->get(1)."' AND 
        a.stat = '12'  
");

while( $row = db_fetch_assoc( $res )){
	$value = json_encode( $row );
	$dkps['chars'] .= "<option value='".$value."'>".$row['name']."</option>\n";
}

### DKP Werte Laden
$res = db_query("SELECT name, dkp FROM prefix_raid_dkps WHERE inzen='".$InzenID."' ORDER BY id ASC");
if(db_num_rows($res)){
    while($row = db_fetch_assoc( $res )){
        $value = json_encode( $row );
        $dkps['DKP'] .= "<option value='".$value."'>".$row['name']." (".$row['pm'].$row['dkp'].")</option>\n";
    }
} else {
    $res = db_query("SELECT name, dkp FROM prefix_raid_dkps WHERE inzen='0' ORDER BY id ASC");
    while($row = db_fetch_assoc( $res )){
        $value = json_encode( $row );
        $dkps['DKP'] .= "<option value='".$value."'>".$row['name']." (".$row['pm'].$row['dkp'].")</option>\n";
    }
}
### Bosse Laden
$dkps['BOSSE'] .= "<option id='0' style='background-color: darkgreen; color: #FFFFFF; font-weight: bold;'>Lebende Bosse ..........</option>\n";
$res = db_query("
    SELECT 
        a.id AS bossid, 
        a.bosse AS bossname 
    FROM prefix_raid_bosse AS a 
        LEFT JOIN prefix_raid_bosscounter AS b ON b.rid=".$menu->get(1)." AND a.id=b.bid
    WHERE a.inzen=".$InzenID." AND bid IS NULL;
");
				
while( $row = db_fetch_assoc( $res )){
	$value = json_encode( $row, true);
	$dkps['BOSSE'] .= "<option id='".$row['bossid']."' value='".$value."' style='background-color: green; color: #FFFFFF;'>".$row['bossname']."</option>\n";
}
### Killed Bosse
$dkps['BOSSE'] .= "<option id='0' style='background-color: darkred; color: #FFFFFF; font-weight: bold;'>Tote Bosse ..........</option>\n";

$res = db_query("
    SELECT a.id, 
        b.bosse AS bossname, 
        b.id AS bossid 
    FROM prefix_raid_bosscounter AS a 
        LEFT JOIN prefix_raid_bosse AS b ON a.bid=b.id 
    WHERE a.rid=".$menu->get(1)."; 
");
				
while( $row = db_fetch_assoc( $res )){
    $value = urlSerialize(array("bossid" => $row['bossid'], "bossname" => $row['bossname'] ));
    $dkps['BOSSE'] .= "<option id='".$row['bossid']."' value='".$value."' style='background-color: red; color: #FFFFFF;'>".$row['bossname']."</option>\n";
}

### Links zum Raid Schließen und öffnen
$dkps['PFAD'] = "admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-dkp";
$stat_msg = db_result(db_query("SELECT statusmsg FROM prefix_raid_raid WHERE id='".$menu->get(1)."'"), 0);
if( $stat_msg != 2 ){
    $dkps['ENDE'] = "<a href='admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-beenden'>Raid beenden</a>";
}else{
    $dkps['ENDE'] = "<a href='admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-oben'>Raid &ouml;ffnen</a>";
}
$tpl->set_ar_out( $dkps, 0);
###VERLAUF
$sort = array("1"=>"b.name","2"=>"a.info","3"=>"a.pm","4"=>"a.dkp","5"=>"a.date");
$row['PFAD'] = "admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-versort";

$tpl->set_ar_out( $row , 1);

if( isset($_SESSION['dkpsort'] )){
    $versort = $_SESSION['dkpsort'];
}else{
    $versort = 5;
}

if( isset($_SESSION['updown'] )){
    $usort = $_SESSION['updown'];
}else{
    $usort = "DESC";
}

$sql = "
    SELECT 
        a.id, a.info, a.dkp, a.pm, a.date, 
        b.name
    FROM prefix_raid_dkp AS a
        LEFT JOIN prefix_raid_chars AS b ON a.cid = b.id 
    WHERE 
        a.rid = '".$menu->get(1)."'
    ORDER BY ".$sort[$versort]." ".$usort." LIMIT 500;
";

$res = db_query( $sql );
$i = 1;
while( $row = db_fetch_assoc( $res )){
	if( $Class == $Cnorm ){ $Class = $Cmite; }else{ $Class = $Cnorm; }
	$row['CLASS'] = cssClass($row['CLASS']);
	$row['info'] = RaidItems($row['info'], $menu->get(0) );
	$row['nr'] = $i;
	$row['date'] = date("H:i:s", $row['date']);
	$row['DEL'] = "<a href='admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-del-".$row['id']."'><img src='include/images/icons/del.gif'></a>";
	$tpl->set_ar_out( $row, 2);
	$i++;
}

$tpl->set_ar_out(
    array(
        "addchar" => drop_down_menu("SELECT id, name FROM prefix_raid_chars ORDER BY name ASC" , "chars", "", "", true), 
        "PFAD" => "admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-addchar",
        "PFADA" => "admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-status",
        "msg" => statmsg(''))
, 3);

$sql = "
    SELECT 
        a.id as aid, a.user, a.stat as status, a.rid,  a.kom, a.timestamp,
        b.id as cid, b.name, 
        c.statusmsg,  c.color, 
        b.s1, b.s2, 
        d.id as klassenid, d.klassen, 
        (SELECT SUM(e.dkp) FROM prefix_raid_dkp AS e WHERE e.cid=b.id AND e.dkpgrp=a.grp) AS dkp  
    FROM prefix_raid_anmeldung AS a 
        LEFT JOIN prefix_raid_chars AS b ON a.char = b.id 
        LEFT JOIN prefix_raid_statusmsg AS c ON a.stat = c.id 
        LEFT JOIN prefix_raid_klassen AS d ON b.klassen = d.id
    WHERE 
        a.rid = '".$menu->get(1)."' 
    ORDER BY a.stat ASC, dkp DESC
";

$res = db_query($sql);
$i = 1;
while( $row = db_fetch_assoc( $res )){
	$row['CLASS'] = cssClass($row['CLASS']);
	$row['nr'] = $i;
	$row['name'] = ( $row['kom'] != '' ? "<b><a href='#' title='Kommentar: ". $row['kom'] ."'>".$row['name'] . "</a></b>" : "<b>".$row['name']."</b>" );
	$row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['klassenid']);
	$row['datum'] = DateFormat("D d.m.Y H:i", $row['timestamp'] );
	#$row['PFADA'] = "admin.php?dkp-".$menu->get(1)."-".$menu->get(2)."-status";
	$row['del'] = aLink("<img src='include/images/icons/del.gif'>", "dkp-".$menu->get(1)."-".$menu->get(2)."-ADel-".$row['aid'], 1);
	$row['stat'] = $row['aid'];
	$tpl->set_ar_out( $row, 4);
	$i++;
}
$tpl->set_ar_out( array(), 5);
$design->footer();

?>