<?php
/**
 * Copyright: Balthazar3k
 * Modul: Raidplaner 1.1
 * Update: 12.04.2014
 */

defined ('main') or die ( 'no direct access' );

require_once("include/includes/func/b3k_func.php");
require_once("include/raidplaner/raidplaner.php");
$raid = new Raidplaner();

$title = $allgAr['title'].' :: Bewerbung';
$hmenu = 'Bewerbung';
$design = new design ( $title , $hmenu );


$design->header();

switch($menu->get(1)){
    
    case "form":
        if( loggedin() or is_admin() ){
            $raid->charakter()->form('Bewerbungs Formular', 'index.php?bewerbung-save');
        }else{
            wd('index.php?user-regist', 'Sie m&uuml;ssen sich erst Regestrieren', 3);
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

            wd("index.php?bewerbung","Du hast dich erfolgreich Beworben", 3);
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
        
        $data = array();
        
        $data['application'] = nl2br($allgAr['bewerbung']);
        
        $data['class'] = $raid->db()->queryRows("
            SELECT 
                a.*, b.klassen AS class_name, b.style,
                (SELECT COUNT(id) FROM prefix_raid_chars WHERE s1 = a.id AND rang > 2) AS num
            FROM `prefix_raid_classification` as a
                LEFT JOIN `prefix_raid_klassen` AS b ON a.class_id = b.id
            WHERE search > 0
            ORDER BY a.class_id
        ");
 
        $data['candidate'] = $raid->db()->queryRows("
            SELECT
                a.id, a.name, a.s1, a.s2, a.level, a.regist,
                b.id as class_id, b.klassen as class_name, b.style as class_style,
                c.name as s1_name,
                d.name as s2_name
            FROM prefix_raid_chars AS a 
                LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id
                LEFT JOIN prefix_raid_classification AS c ON a.s1 = c.id
                LEFT JOIN prefix_raid_classification AS d ON a.s2 = d.id
             WHERE 
                a.rang = 1 
             ORDER BY regist ASC" ,0
        );
        
        $tpl = $raid->smarty();
        $tpl->assign('data', $data);
        $tpl->display('application.tpl');
    break;
}

copyright();
$design->footer();
?>
