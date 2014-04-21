<?php 
defined ('main') or die ( 'no direct access' );

require_once("include/includes/func/b3k_func.php");

if( isset( $_POST['kid'] ))
{	exit(classSpecialization($_POST['kid']));
}

$title = $allgAr['title'].' :: Chars';
$hmenu = 'Charaktere';
$design = new design ( $title , $hmenu );
$design->header();

RaidErrorMsg();

$img_del = "<img src='include/images/icons/del.gif' border='0'>";
$img_edit = "<img src='include/images/icons/edit.gif' border='0'>";

$kalout .= $_SESSION['authid'] ."=". $uid_s;
switch($menu->get(1)){
	
    case "del":
        $name = $raid->charakter($menu->get(2))->name();

        if( $menu->get(3) != "true" ){

            echo $raid->confirm()
                ->message("Wirklich alle daten von \"".$name."\" L&ouml;schen?")
                ->onTrue('index.php?'.$_SERVER['QUERY_STRING'].'-true')
                ->onFalse('index.php?bewerbung')
                ->html('L&ouml;schen');

        }

        if( $menu->get(3) == "true" ){
            if( $raid->charakter()->owner($menu->get(2)) || $raid->permission()->delete('charakter', $message) ){

                $raid->charakter()->delete($menu->get(2));
                wd('index.php?chars',$name.' wurde erfolgreich gel&ouml;scht!', 1);
            }else{
                wd('index.php?chars',$name.' wurde "<b>NICHT</b>" erfolgreich gel&ouml;scht!', 3);
            }
        }

    break;
	   
    case "form":
        button("Zur&uuml;ck","",8);
        echo '<br /><br />';
        $raid->charakter()->form('Charakter Formular', 'index.php?chars-save-'.$menu->get(2), $menu->get(2));
    break;
    
    case "save":

        $charakter = array(
            'charakter' => array(
                'rang' => 2,
                'user' => $_SESSION['authid']
            )
        );
        
        $charakter = array_merge_recursive($_POST, $charakter);

        if( $raid->charakter($menu->get(2))->save($charakter) ){
            wd("index.php?chars","Charakter ".$charakter['name']." wurde erfolgreich gespeichert!", 3);
        }else{
            wd("index.php?chars","Charakter ".$charakter['name']." wurde <b>nicht</b> erfolgreich gespeichert!", 3);
        }

    break;
    
	#### Raid Tage
	case "raidtage";
		@db_query("DELETE FROM prefix_raid_kalender WHERE cid=".$menu->get(2) );
		foreach( $_POST as $key => $value ){
			list( $zeit, $wochtag ) = explode("-", $key);
			if( $zeit != 0 ){
				db_query('INSERT INTO `prefix_raid_kalender` (`cid`, `zid`, `wid`) VALUES (\''.$menu->get(2).'\', \''.$zeit.'\', \''.$wochtag.'\');');
			}
		}
		wd('index.php?chars--show-'.$menu->get(2),'Raidtage wurde ge�ndert');
	break;
    case "details":
        $raid->charakter($menu->get(2))->name();
        $raid->charakter()->details();
    break;
	
    default:
        $tpl = new tpl ('raid/CHARS_LIST.htm');

        $c['COUNT_CHARS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars"),0);
        $c['COUNT_MAINS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang >= 4"),0);
        $c['COUNT_LEVEL'] = db_result(db_query("SELECT COUNT(level) FROM prefix_raid_chars WHERE level = '1'"),0);
        $c['COUNT_EIGENE'] = db_result(db_query("SELECT COUNT(user) FROM prefix_raid_chars WHERE user = '".$_SESSION['authid']."'"),0);
        $c['COUNT_BEWERBER'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang = 1"),0);

        if( RaidRechte($allgAr['addchar']) ){
                if( $allgAr['maxchars'] > $c['COUNT_EIGENE'] ){
                        $c['USER'] = "[ <a href='index.php?chars-form'>Neuer Char</a> ]";
                }else{
                        $c['USER'] = "Max. ".$c['COUNT_EIGENE']."/".$allgAr['maxchars']." Chars Erreicht";
                }
        }else{
                $c['USER'] = "LINK CLOSED";
        }
        ### Klassen Liste erstellen
        $erg = db_query("SELECT id, klassen FROM prefix_raid_klassen ORDER BY id DESC");
        $l_klassen = "<a href='index.php?chars'>".$img_del."</a> ";
        while( $row = db_fetch_assoc( $erg )){
                $c['list_klassen'] .= "<a href='index.php?chars-".$row['id']."'><img src='include/raidplaner/images/class/class_".$row['id'].".jpg' border=0></a> ";
        }
        ### Ausgabe der Daten.
        $tpl->set_ar_out( $c , 0 );
        ### CHARS AUFLISTEN ################################################################################################################

        $sort = ( $menu->get(1) != "" ? "AND a.klassen='".$menu->get(1)." '" : "" );

        $q = $_POST['search'];
        $res = db_query("
            SELECT 
                a.name, a.rang AS rangid, a.s1, a.s2, a.s3, a.realm, a.user, a.punkte, a.id, a.level, 
                b.id as klassenid, b.klassen, 
                d.rang, 
                e.name AS username 
             FROM prefix_raid_chars AS a 
                LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
                LEFT JOIN prefix_user AS e ON a.user = e.id 
             WHERE 
                a.name LIKE '$q%' 
                AND a.rang != 1    
                ".$sort." 
             ORDER BY b.klassen , a.klassen ASC, a.rang DESC, a.name ASC
        ");

        while( $row = db_fetch_assoc( $res )){
            $klassen = $row['klassen'];
            if( $klassen_to_change != $row['klassen'] ){
                    $c_klassen = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE name LIKE '$q%' AND klassen=".$row['klassenid']),0);
                    $c_klassen .= "<a name='".$row['klassen']."'></a>";
                    $tpl->set_ar_out( array( "klass_name" => $row['klassen'], "COUNT_KLASSEN" => $c_klassen ), 1 );
            }

            ### Ausgaben �ndern/Hinzuf�gen
            $row['ARSENAL'] = "<a href='http://eu.battle.net/wow/de/character/".urlencode(utf8_encode($row['realm']))."/".str_replace(" ", "+",urlencode(utf8_encode($row['name'])))."/advanced' target='_blank'>Arsenal</a>";
            $row['CLASS'] = cssClass($row['CLASS']);
            $row['img'] = class_img($row['klassenid']);
            $row['name'] = "<a href='index.php?chars-details-".$row['id']."' name='".$row['id']."'>".$row['name']."</a>";
            ### Skillung Auswerten
            $row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['klassenid']);
            ### Edit f�r Chareigent�mer
            if( $row['user'] == $_SESSION['authid']){
                    $row['edit'] = "<a href='index.php?chars-form-".$row['id']."'>".$img_edit."</a>";
            }else{
                    $row['edit'] = "";
            }

            $tpl->set_ar_out( $row, 2 );
            $klassen_to_change = $klassen;
        }

        $tpl->out(3);
        $tpl->out(4);
    break;
}
copyright();
$design->footer();
?>
