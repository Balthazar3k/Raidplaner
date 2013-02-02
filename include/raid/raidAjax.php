<?php
define ( 'main' , TRUE );

require_once("../includes/config.php");
require_once("../includes/func/db/mysql.php");

db_connect();

switch( $_GET['ajaxAction'] )
{	case "itemNAME":
		$search = urldecode($_GET['item']);
		$limit = $_GET['limit'];
		$JSON = array();
		$res = db_query("SELECT id, name, class FROM prefix_raid_items WHERE name LIKE '".$search."%' LIMIT ".$limit);
		while( $row = db_fetch_assoc( $res ) )
		{	$JSON[] = "{\"id\": \"".$row['id']."\", \"name\": \"".$row['name']."\", \"classen\": \"".$row['class']."\" }";
		}
		
		echo "[".implode(",\n ", $JSON)."]";
	break;
	case "itemID":
		$search = urldecode($_GET['bid']);
		$JSON = array();
		$res = db_query("SELECT id, name, class FROM prefix_raid_items WHERE bid='".$search."'");
		while( $row = db_fetch_assoc( $res ) )
		{	$JSON[] = "{\"id\": \"".$row['id']."\", \"name\": \"".$row['name']."\", \"classen\": \"".$row['class']."\" }";
		}
		
		echo "[".implode(",\n ", $JSON)."]";
	break;
	case "installItems":
		$url = 'itemlist.gz';
	
		if($file = @file_get_contents($url))
		{	$file = gzuncompress($file);
						
			preg_match_all("/name=\"(.*)\" id=\"([0-9]*)\"/", $file, $tmp );
			
			foreach( $tmp[1] as $k => $v )
			{	$id = $tmp[2][$k];
				@db_query( "INSERT INTO prefix_raid_items ( id,name ) VALUE('".$id."','".utf8_decode($v)."')" );	
			}
		}else
		{	echo "Konnte itemlist.gz <b>nicht</b> Lesen!";
		}
		
		@unlink("itemlist.gz");
	break;
	
	echo "muh";
}

db_close();
?>