<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );
require_once("include/includes/func/b3k_func.php");

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

$raid->permission()->stay('update', 'Classes');

RaidErrorMsg();
aRaidMenu();

switch($menu->get(1)){
    case 'saveClass':
        if( $menu->get(2) != NULL ){
            
            $raid->db()->singel()
                ->update('raid_klassen')
                ->fields($_POST)
                ->where(array('id' => $menu->get(2)))
                ->init();
            
            wd('admin.php?raidclasses', 'Klasse wurde gespeichert!');
            
        } else {
            
            $raid->db()->singel()
                ->insert('raid_klassen')
                ->fields($_POST)
                ->init();
            
            wd('admin.php?raidclasses', 'Neue Klasse wurde angelegt');
        }
    break;

    default:
        if( $menu->get(1) == 'class'){
            
            $data['edit'] = $raid->db()
                ->select('id', 'klassen')
                ->from('raid_klassen')
                ->where(array('id' => $menu->get(2)))
                ->row();
        }
        
        $data['class'] = $raid->db()
                ->select('id', 'klassen')
                ->from('raid_klassen')
                ->rows();
        
        
        $raid->smarty()->assign('data', $data);
        $raid->smarty()->display('class_form.tpl');
    break; 
}

copyright();

$design->footer();

?>