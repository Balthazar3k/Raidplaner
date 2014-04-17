<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");



$tpl = new tpl ('raid/raidindex.htm', 1);

switch($menu->get(1)){
    case 'saveTimes':
        /* when you can creating time can 2 edit */
        if( $raid->permission()->create('times', $message) ){
            $raid->times()->save($_POST, $menu->get(2));
            wd('admin.php?raidindex', 'Zeiten wurden erfolgreich ge&auml;ndert.', 5);
            exit();
        } else {
            wd('admin.php?raidindex', $message, 5);
            exit();
        }
    break;
    
    case 'editTimes':
        $return = $raid->db()
            ->select('id', 'weekday', 'start', 'inv', 'end')
            ->from('raid_zeit')
            ->where(array('id' => $menu->get(2)))
            ->row();
    break;

    case 'deleteTimes':
        if( $raid->permission()->delete('times', $message)){
            $raid->times()->delete($menu->get(2));
            wd('admin.php?raidindex', 'L&ouml;schen der Zeit war erfolgreich!', 3);
            exit();
        } else {
            wd('admin.php?raidindex', $message, 5);
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

$res = $raid->db()->select('*')->from('raid_zeit')->init();
while( $row = db_fetch_assoc($res)){
    $return['zeiten'] .= $tpl->list_get( 'zeiten', 
        array ( 
            $row['id'], 
            $row['weekday'], 
            $row['start'], 
            $row['inv'], 
            $row['end'], 
            $row['options']
        )
    );
}

$_weekdays = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');

foreach( $_weekdays as $weekday ){
    if( $return['weekday'] == $weekday ){
        $return['weekdays'] .= $tpl->list_get( 'weekdays', array($weekday, 'selected="selected"'));
    } else {
        $return['weekdays'] .= $tpl->list_get( 'weekdays', array($weekday, ''));
    }
}

$tpl->set_ar_out($return, 0);

$design->footer();
?>