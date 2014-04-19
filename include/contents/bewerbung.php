<?php
/**
 * Copyright: Balthazar3k
 * Modul: Raidplaner 1.1
 * Update: 12.04.2014
 */

defined ('main') or die ( 'no direct access' );
$title = $allgAr['title'].' :: Bewerbung';
$hmenu = 'Bewerbung';
$design = new design ( $title , $hmenu );
require_once("include/includes/func/b3k_func.php");

$design->header();

$tpl = $raid->smarty();

$array = array();

if( RaidPermission( 0, TRUE ) ){
    if( $menu->get(1) == 'updateKlassen' ){
        db_query("UPDATE prefix_raid_klassen SET aufnahmestop=".$menu->get(3)." WHERE id=".$menu->get(2) );
    }

    $result = db_query("SELECT id, klassen FROM prefix_raid_klassen WHERE aufnahmestop=0");
    while( $row = db_fetch_object( $result )){
        $array[] = aLink(class_img($row->klassen), "bewerbung-updateKlassen-".$row->id."-1")." ";
    }

    $tpl->assign('panel', array(
        'title' => 'Einstellungen, welche Klassen suchen wir noch',
        'content' => '<div class="btn-group">'. implode('', $array) .'</div>'
    ));
    
    $tpl->display('panel.tpl');
    
}

$array = array();
$result = db_query("SELECT id, klassen FROM prefix_raid_klassen WHERE aufnahmestop=1");
$cKlassen = db_num_rows($result);
if( $cKlassen > 0 ){
    while( $row = db_fetch_object($result) ){
        if( RaidPermission( 0, TRUE) ){
            $array[] = aLink(class_img($row->klassen),"bewerbung-updateKlassen-".$row->id."-0")." ";
        }else{
            $array[] = class_img($row->klassen);
        }
    }
    
    $tpl->assign('panel', array(
        'title' => 'Klassen die wir Suchen',
        'content' => '<div class="btn-group">'. implode('', $array) .'</div>'
    ));
    $tpl->display('panel.tpl');
}else{
    $tpl->assign('panel', array(
        'title' => '<i class="fa fa-search"></i> Klassen die wir Suchen',
        'content' => 'Momentan suchen wir keine weiteren Spieler'
    ));
    $tpl->display('panel.tpl');
}
echo "<br />";

switch($menu->get(1)){
    
    case "form":
        button("Zur&uuml;ck","",8);
        echo "<br><br>";
        if( loggedin() and !RaidRechte($allgAr['addchar']) or is_admin() ){
            if( !RaidRechte($allgAr['addchar']) ){ echo "no Permission!"; $design->footer(); exit(); }
            $raid->charakter()->form('Bewerbungs Formular', 'index.php?bewerbung-save');
        }else{
            echo bbcode(allgArInsert($allgAr['bewerbung']));
        }
    break;
    
    case "save":
        
        $res = $_POST;
        
        $bewerber = array(
            'charakter' => array(
                'rang' => 1,
                'user' => $_SESSION['authid']
            )
        );
        
        $bewerber = array_merge_recursive($res, $bewerber);

        if( $raid->charakter()->save($bewerber) ){

            // Sendet eine PM an berechtigte
            sendpm_2_legitimate(
                'Script: eine neue Bewerbung',
                'Es hat sich '.$bewerber['name'].' Beworben!'
            );

            //wd("index.php?bewerbung","Du hast dich erfolgreich Beworben", 3);
        }else{
            wd("index.php?bewerbung","Du hast dich <b>nicht</b> erfolgreich Beworben ", 3);
        }

    break;
    case "sendKlassen":
            if( RaidPermission() ){
                foreach( $_POST as $id => $val ){
                    if( $id != 'button' ){
                        db_query("UPDATE prefix_raid_klassen SET aufnahmestop=".$val." WHERE id='".$id."'");
                    }
                }

                wd('index.php?bewerbung','', 0 ); 
            }else{
                echo "Don't Permission!";
                $design->footer();
                exit();
            }
    break;
######################
### Char Löschen #####
######################
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
                
                wd('index.php?bewerbung',$name.' wurde erfolgreich gel&ouml;scht!', 1);
            }else{
                wd('index.php?bewerbung',$message . '<br>' .$name.' wurde "<b>nicht</b>" erfolgreich gel&ouml;scht!', 3);
            }
        }
    break;
######################
### BEWERBER ######### 
######################
    default:
        $tpl = new tpl ("raid/BEWERBER_LISTE.htm");
        button("Formular","index.php?bewerbung-form", 0); 
        print "<br><br>";
        $result = db_query( "
            SELECT
                a.id, a.name, a.s1, a.s2, a.s3, 
                b.id as kid, b.klassen, c.level,
                DATE_FORMAT(a.regist,'%d.%m.%y %H:%i') as datum 
                FROM prefix_raid_chars AS a 
                LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                LEFT JOIN prefix_raid_level AS c ON a.level = c.id  
             WHERE 
                a.rang = 1 
             ORDER BY datum ASC" ,0
        );

        $tpl->out(0);				 
        if( db_num_rows( $result ) > 0 ){
            $i = 0;
            while( $row = db_fetch_assoc( $result ) ){
                $i++;
                $row['class'] = ( $row['class'] != 'Cmite' ? 'Cmite' : 'Cnorm' );
                $row['img'] = class_img($row['klassen']);
                $row['name'] = $i.". ".aLink($row['name'], "chars-show-".$row['id']);
                $row['skill'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['kid']);
                $row['opt'] = ( is_admin() ? button("L&ouml;schen","index.php?bewerbung-del-".$row['id'], 2) : '');
                $tpl->set_ar_out( $row, 1 );
            }
        }else{
            $tpl->out(2);
        }
        $tpl->out(3);
    break;
}

copyright();
$design->footer();
?>
