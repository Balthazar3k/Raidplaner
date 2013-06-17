<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

$design->header();

aRaidMenu();
$table = new tpl ( 'raid/2zeilen4spalten.htm',1 );

function div($txt, $i=1, $width=100 )
{	$width = "width: ". $width ."px; ";
	if( $i == 0 ) return "<div class='aDIV01'>".$txt."</div>\n";
	if( $i == 1 ) return "<div class='aDIV02' style='".$width."float: left;'>".$txt."</div>\n";
	if( $i == 2 ) return "<div class='aDIV03' style='".$width."float: left;'>".$txt."</div><br style='clear: both;' />\n";
	if( $i == 3 ) return "<div class='aDIV03' style='".$width."float: right;'>".$txt."</div><br style='clear: both;' />\n";
}

## Chars
$id = 1;
$res = db_query("SELECT 
					a.id, a.name, a.regist, b.klassen, c.level,
					a.s1, a.s2, a.s3, b.id AS kid
				FROM prefix_raid_chars AS a 
					LEFT JOIN prefix_raid_klassen AS b ON b.id = a.klassen
					LEFT JOIN prefix_raid_level AS c ON c.id = a.level
				WHERE rang <= 2 ORDER BY a.id DESC LIMIT 5");

if( db_num_rows( $res ) )
{	echo div("<b style='color: red; font-size: 12px;'>Neue Chars/Bewerber</b>", 2, 632);
	while( $row = db_fetch_assoc( $res ) )
	{	$skill = char_skill($row['s1'],$row['s2'],$row['s3'],$row['kid']);
		echo div( nuller($id++), 1, 20 ). 
			 div( aLink( $row['name'], "chars-details-".$row['id'], 1)). 
			 div($row['level']." ".$row['klassen']." ".$skill, 1, 250). 
			 div( DateFormat("D d.m.Y H:i:s", $row['regist'])." ".agoTimeMsg( $row['regist'] ), 2, 250);
	}
}else
{	$r['chars'];
}

$design->footer();
?>