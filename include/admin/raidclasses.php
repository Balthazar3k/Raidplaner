<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

require_once("include/includes/func/b3k_func.php");

if( $menu->get(1) == 'search'){
            
    $search = (int) $raid->db()
        ->select('search')
        ->from('raid_classification')
        ->where('id', $menu->getE(2))
        ->cell();

    if( $menu->getA(2) == 'p') {
        $search++;
    } else if( $menu->getA(2) == 'm') {
        $search--;
    } else if( $menu->getA(2) == 'o') {
        $search = 0;
    }
    
    $raid->db()
        ->update('raid_classification')
        ->fields(array('search' => $search))
        ->where(array('id' => $menu->getE(2)))
        ->init();

    header("Location: admin.php?raidclasses");
}

switch($menu->get(1)){
    case 'saveClass':
        if( $menu->get(2) != NULL ){
            
            $raid->db()->singel()
                ->update('raid_klassen')
                ->fields($_POST)
                ->where(array('id' => $menu->get(2)))
                ->init();
            
            header("Location: admin.php?raidclasses");
            wd('admin.php?raidclasses', 'Klasse wurde gespeichert!');
            
        } else {
            
            $raid->db()->singel()
                ->insert('raid_klassen')
                ->fields($_POST)
                ->init();
            
            header("Location: admin.php?raidclasses");
            wd('admin.php?raidclasses', 'Neue Klasse wurde angelegt');
        }
    break;
    
    case 'removeClass':
        
        if( $menu->get(3) == 'true'){
            
            $res = $raid->db()->singel()
                ->delete('raid_klassen')
                ->where('id', $menu->get(2))
                ->init();
            
            $raid->db()->singel()
                ->delete('raid_classification')
                ->where('class_id', $menu->get(2))
                ->init();
            
            if( $res ){
                header("Location: admin.php?raidclasses");
                wd('admin.php?raidclasses', 'Klasse wurde gel&ouml;scht', 3);
            } else {
                wd('admin.php?raidclasses', 'Klasse wurde <b>nicht</b> gel&ouml;scht', 3);
            }
            
        } else {
            $class_name = $raid->db()->select('klassen')->from('raid_klassen')->where('id', $menu->get(2))->cell();
            
            echo $raid->confirm()
                ->message('Klasse "<b>'.$class_name.'</b>" wirklich L&ouml;schen?')
                ->onTrue('admin.php?raidclasses-removeClass-'.$menu->get(2).'-true')
                ->onFalse('admin.php?raidclasses')
                ->html('L&ouml;schen');
        }
        
    break;
    
    case 'saveClassification':
        if( $menu->get(2) != NULL ){
            
            $raid->db()->singel()
                ->update('raid_classification')
                ->fields($_POST)
                ->where(array('id' => $menu->get(2)))
                ->init();
            header("Location: admin.php?raidclasses");
            wd('admin.php?raidclasses', 'Spezialiesierung wurde gespeichert!');
            
        } else {
            
            $raid->db()->singel()
                ->insert('raid_classification')
                ->fields($_POST)
                ->init();
            header("Location: admin.php?raidclasses");
            wd('admin.php?raidclasses', 'Neue Spezialiesierung wurde angelegt');
        }
    break;
    
    case 'removeClassification':
        
        if( $menu->get(3) == 'true'){
            
            $res = $raid->db()->singel()
                ->delete('raid_classification')
                ->where('id', $menu->get(2))
                ->init();
            
            if( $res ){
                header("Location: admin.php?raidclasses");
                wd('admin.php?raidclasses', 'Spezialiesierung wurde gel&ouml;scht', 3);
            } else {
                wd('admin.php?raidclasses', 'Spezialiesierung wurde <b>nicht</b> gel&ouml;scht', 3);
            }
            
        } else {
            $class_name = $raid->db()->select('name')->from('raid_classification')->where('id', $menu->get(2))->cell();
            
            echo $raid->confirm()
                ->message('Spezialiesierung "<b>'.$class_name.'</b>" wirklich L&ouml;schen?')
                ->onTrue('admin.php?raidclasses-removeClassification-'.$menu->get(2).'-true')
                ->onFalse('admin.php?raidclasses')
                ->html('L&ouml;schen');
        }
        
    break;
}



$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

$raid->permission()->stay('update', 'Classes');

RaidErrorMsg();
aRaidMenu();

switch($menu->get(1)){
    default:
        if( $menu->get(1) == 'class'){
            
            $data['edit'] = $raid->db()
                ->select('id', 'klassen', 'style')
                ->from('raid_klassen')
                ->where(array('id' => $menu->get(2)))
                ->row();
        }
        
        $data['class'] = $next = $raid->db()
                ->select('id', 'klassen', 'style')
                ->from('raid_klassen')
                ->order(array('id' => 'ASC'))
                ->rows();
        
        
        $raid->smarty()->assign('data', $data);
        $raid->smarty()->display('class_form.tpl');
        
        $data = array();
        
        $data['class'] = $next;
        
        if( $menu->get(1) == 'classification'){
            
            $data['edit'] = $raid->db()
                ->select('*')
                ->from('raid_classification')
                ->where(array('id' => $menu->get(2)))
                ->row();
        }
        
        $data['classification'] = $raid->db()
                ->queryRows("
                    SELECT 
                        a.*, b.klassen AS class_name, b.style
                    FROM `prefix_raid_classification` as a
                        LEFT JOIN `prefix_raid_klassen` AS b ON a.class_id = b.id
                    ORDER BY a.class_id
                ");
        
        $raid->smarty()->assign('data', $data);
        $raid->smarty()->display('classification_form.tpl');
    break; 
}

copyright();

$design->footer();

?>