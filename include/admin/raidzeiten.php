<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

switch($menu->get(1)){
    case 'save':
        /* when you can creating time can 2 edit */
        if( $raid->permission()->create('times', $message) ){
            $raid->times()->save($_POST, $menu->get(2));
            wd('admin.php?raidzeiten', 'Zeiten wurden erfolgreich ge&auml;ndert.', 5);
            exit();
        } else {
            wd('admin.php?raidzeiten', $message, 5);
            exit();
        }
    break;
    
    case 'edit':
        $return = $raid->db()
            ->select('id', 'weekday', 'start', 'inv', 'end')
            ->from('raid_zeit')
            ->where(array('id' => $menu->get(2)))
            ->row();
    break;

    case 'delete':
        if( $raid->permission()->delete('times', $message)){
            $raid->times()->delete($menu->get(2));
            wd('admin.php?raidzeiten', 'L&ouml;schen der Zeit war erfolgreich!', 3);
            exit();
        } else {
            wd('admin.php?raidzeiten', $message, 5);
            exit();
        }
    break;

    default:
        $return = array(
            'id' => '',
            'start' => '',
            'inv' => '',
            'end' => ''
        );
    break;
}

$design->header();

aRaidMenu();

$return['times'] = $raid->db()->queryRows("
    SELECT
        a.*, (SELECT COUNT(id) FROM prefix_raid_zeit_charakter WHERE zid = a.id) as num
    FROM prefix_raid_zeit as a
");

$return['weekdays'] = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');

$raid->smarty()
    ->assign('data', $return)
    ->display('times_form.tpl');

$design->footer();
?>