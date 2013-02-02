<?php
defined ('main') or die ( 'no direct access' );
$title = $allgAr['title'].' :: Raidstatistik';
$hmenu = 'Raidstatistik';
$design = new design ( $title , $hmenu );
require_once("include/includes/func/b3k_func.php");
$design->addheader($raidHeader);
$design->header();

button("Start", "index.php?raidstatistik", 0);
button("Bosskills", "index.php?raidstatistik-bosskills", 0);
button("Instanzen", "index.php?raidstatistik-inztanzen", 0);
button("Items", "index.php?raidstatistik-items", 0);

echo "<br>";

switch($menu->get(1)){
	case "items":
		switch($menu->get(2))
		{ 	case 1:
				$tpl = new tpl ('raid/STATISTIK_ITEMS.htm');
				$tpl->out(3);
				$res = db_query("SELECT 
									a.date, a.info, a.dkp, b.name, b.id, c.klassen 
								FROM prefix_raid_dkp as a
									LEFT JOIN prefix_raid_chars AS b ON a.cid = b.id 
									LEFT JOIN prefix_raid_klassen AS c ON b.klassen = c.id
								WHERE a.info LIKE CONVERT(_utf8 '%{_raiditem_%}%' USING latin1)
								ORDER BY date DESC");
				
				while( $row = db_fetch_assoc( $res ) )
				{	preg_match("/\{_raiditem_([0-9]*)\}/", $row['info'], $tmp );
					
					$resItems = db_query("SELECT name, class FROM prefix_raid_items WHERE id='".$tmp[1]."' LIMIT 1");
					$item = db_fetch_assoc( $resItems );
					
					$row['date'] = DateFormat("D d.m.Y H:i:s", $row['date'] );
					$row['name'] = aLink($row['name'], "chars-show-".$row['id']);
					$row['img'] = "<img src='include/images/wowklein/".$row['klassen'].".gif'>";
					$row['item'] = "<a href=\"http://wowdata.buffed.de/?i=".$tmp[1]."\" class=\"".$item['class']."\" target=\"_blank\">".$item['name']."</a>";
					
					unset( $tmp );
					unset( $item );
					
					$tpl->set_ar_out( $row, 4 );
	
				}
				$tpl->out(5);
		 	break;
		 	default:
				echo "<br />";
				button("ItemsHistory", "index.php?raidstatistik-items-1", 0);
				echo "<br />";
		  		$tpl = new tpl ('raid/STATISTIK_ITEMS.htm');
				
				$tpl->out(0); $i = 1;
				$res = db_query("SELECT id, name, class, `drop` FROM prefix_raid_items WHERE `drop` != '' ORDER BY `drop` DESC");
				while( $row = db_fetch_assoc( $res ))
				{	$row['nr'] = $i;
					$row['CLASS'] = cssClass( $row['CLASS'] );
					$row['img'] = "<div style='width:85px; height:50px; background-image: url(http://wowdata.buffed.de/tooltips/items/gif/".$row['id'].".gif);'></div>";
					$row['item'] = "<a href=\"http://wowdata.buffed.de/?i=".$row['id']."\" class=\"".$row['class']."\" target=\"_blank\">".$row['name']."</a>";
					$tpl->set_ar_out( $row, 1);
					$i++;
				}
				$tpl->out(2);
		  	break;
		}
	break;
	case "bosskills":
		$tpl = new tpl ('raid/STATISTIK_BOSSE.htm');
		
		$SQL = "SELECT 
				a.bosse,
				a.img,  
				COUNT(b.id) as kills, 
				MIN(b.time) as firstkill,
				MAX(b.time) as lastkill, 
				c.name as inzen 
				FROM 
				prefix_raid_bosse AS a, 
				prefix_raid_bosscounter AS b, 
				prefix_raid_inzen AS c 
				WHERE 
				a.id=b.bid AND 
				a.inzen=c.id 
				GROUP BY a.bosse 
				ORDER BY kills DESC";
		
		$res = db_query( $SQL );
		$all = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter"),0);
		$kill = db_query("SELECT id FROM prefix_raid_bosse");
		$killed = 0;
		while( $k = db_fetch_assoc( $kill )){
			$isKilled = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter WHERE id=".$k['id']),0);
			if( $isKilled > 0 ){
				$killed++;
			}
		}
		$r['kills'] = $killed;
		$r['alle'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosse"),0);
		$r['pz'] = pzVortschritsAnzeige( $r['kills'], $r['alle'] );
		
		$tpl->set_ar_out($r,0); #Tab Header
		$i=0;
		while( $row = db_fetch_assoc( $res )){
			$i++;
			$tpl->set_out("nr", $i, 1);
			$row['gruppen1'] = @db_result(@db_query("SELECT b.gruppen FROM prefix_raid_bosscounter AS a, prefix_raid_gruppen AS b WHERE a.time=".$row['firstkill']." AND a.grpid=b.id"),0);
			$row['gruppen2'] = @db_result(@db_query("SELECT b.gruppen FROM prefix_raid_bosscounter AS a, prefix_raid_gruppen AS b WHERE a.time=".$row['lastkill']." AND a.grpid=b.id"),0);
			$row['firstkill'] = DateFormat("D d.m.Y", $row['firstkill']);
			$row['lastkill'] = DateFormat("D d.m.Y", $row['lastkill']);
			$row['CLASS'] = cssClass($row['CLASS']);
			$row['img'] = ( file_exists("include/images/bosse/".$row["img"]) ? "<img height=\"50\" src=\"/include/images/bosse/".$row["img"]."\">" : "noImg" );
			$row['pz'] = pzVortschritsAnzeige( $row['kills'], $all );
			$tpl->set_ar_out( $row, 2);
		}
		
		$tpl->out(3); #Tab footer
	break;
	case "inztanzen":
		$tpl = new tpl('raid/STATISTIK_INIS.htm');
		
		$sql = "SELECT
					a.id, a.name as inzen, a.img, a.maxbosse,
					c.info, 
					b.grpsize, 
					d.level, 
					(SELECT COUNT(aa.id) FROM prefix_raid_bosscounter AS aa WHERE aa.iid=a.id) AS cAllKilledBosse,
					(SELECT COUNT(bb.id) FROM prefix_raid_raid AS bb WHERE bb.inzen=a.id) AS allRaids, 
					((SELECT COUNT(bb.id) FROM prefix_raid_raid AS bb WHERE bb.inzen=a.id) * a.maxbosse) AS cAllCanKilledBosse, 
					((SELECT COUNT(dd.id) FROM prefix_raid_bosscounter AS dd WHERE dd.iid=a.id) / a.maxbosse ) AS clear  
				FROM prefix_raid_inzen AS a 
					LEFT JOIN prefix_raid_grpsize AS b ON a.grpsize=b.id 
					LEFT JOIN prefix_raid_info AS c ON a.info=c.id 
					LEFT JOIN prefix_raid_level AS d ON a.level=d.id
				ORDER BY clear DESC";
		
		$t['pz'] = "";
		
		$tpl->set_ar_out($t,0); #Tab Header
		$res = db_query( $sql );
		$i = 0;
		while( $row = db_fetch_object($res) ){
			if( $row->clear != "0.00" ){
				### Rang
				$i++;
				$tpl->set_out("nr", $i, 1);
				### Ausgabe
				$pfad = "include/images/inzen/";
				
				$row->CLASS = cssClass($row->CLASS);
				$row->img = ( file_exists( $pfad . $img ) ? "<img width='75' src='".$pfad.$row->img."'>" : "n/a" );
				
				$tpl->set_ar_out($row, 2);
			}
		}
		$tpl->out(3);
	break;
	default:
		$db_table_breite = 50;
		echo "<br>";
		db_table( "SELECT a.klassen as Alle_Klassen, COUNT(b.klassen) as anzahl, a.id as del FROM prefix_raid_klassen AS a, prefix_raid_chars AS b 
		WHERE a.id=b.klassen GROUP BY a.klassen ORDER BY anzahl DESC", 0 );
		echo "<br>";
		db_table( "SELECT a.klassen as Alle_Main_Klassen, COUNT(b.klassen) as anzahl FROM prefix_raid_klassen AS a, prefix_raid_chars AS b 
		WHERE a.id=b.klassen AND b.rang>=4 GROUP BY a.klassen ORDER BY anzahl DESC", 0 );
		echo "<br>";
		db_table( "SELECT a.rassen, COUNT(b.rassen) as anzahl FROM prefix_raid_rassen AS a, prefix_raid_chars AS b 
		WHERE a.id=b.rassen GROUP BY a.rassen ORDER BY anzahl DESC", 0 );
		echo "<br>";
		db_table( "SELECT a.berufe as Main_Berufe, COUNT(b.mberuf) as anzahl FROM prefix_raid_berufe AS a, prefix_raid_chars AS b 
		WHERE a.id=b.mberuf GROUP BY a.berufe ORDER BY anzahl DESC", 0 );
		echo "<br>";
		db_table( "SELECT a.berufe as Second_Berufe, COUNT(b.sberuf) as anzahl FROM prefix_raid_berufe AS a, prefix_raid_chars AS b 
		WHERE a.id=b.sberuf GROUP BY a.berufe ORDER BY anzahl DESC", 0 );
		echo "<br>";
		db_table( "SELECT a.rang as Ränge, COUNT(b.rang) as anzahl FROM prefix_raid_rang AS a, prefix_raid_chars AS b 
		WHERE a.id=b.rang GROUP BY a.rang ORDER BY a.id DESC", 0 );
	break;
}

copyright();
$design->footer();
?>