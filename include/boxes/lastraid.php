<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php
defined ('main') or die ( 'no direct access');

require_once("include/includes/func/b3k_func.php");

$res = db_query("SELECT
                    a.id, a.inv, 
                    b.name,
                    c.grpsize  
                FROM prefix_raid_raid AS a 
                    LEFT JOIN prefix_raid_inzen AS b ON a.inzen = b.id  
                    LEFT JOIN prefix_raid_grpsize AS c ON b.grpsize = c.id 
                WHERE  
                    a.statusmsg = 2 
                ORDER BY a.inv ASC 
                LIMIT 5");
                
while( $r = db_fetch_assoc( $res )){
    #$heute = ( date('d.m.Y', $r['inv'] ) == date('d.m.Y') ? 'bgcolor="green"' : '' );
    $ply = db_result(db_query( "SELECT COUNT(id) FROM prefix_raid_anmeldung WHERE rid =".$r['id']." AND stat=12" ), 0);
    $isAng = db_result(db_query( "SELECT COUNT(id) FROM prefix_raid_anmeldung WHERE rid=".$r['id']." AND user=".$_SESSION['authid'] ),0);
    $isAng = ( $isAng == 1 ? "<img src='include/images/icons/online.gif'>" : "<img src='include/images/icons/offline.gif'>" );
    echo "<tr>";
    echo "<td rowspan=2>".$isAng."</td>";
    echo "<td><a href='index.php?raidlist-showraid-".$r['id']."'>".DateFormat('D d.m.y H:i', $r['inv'] )."</a> ".$ply.'/'.$r['grpsize']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><div style='font-size:9px;'>» ".$r['name']."</div></td>";
    echo "</tr>";
}

?>
</table>