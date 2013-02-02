<?php
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

# DESIGN 
$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");
$design->addheader($raidHeader);
$design->header();

if( !RaidPermission(0, TRUE) ){ echo 'don\'t Permission'; $design->footer(); exit(); }
RaidErrorMsg();
aRaidMenu();

$tpl = new tpl("raid/raiditems.htm", 1);

switch( $menu->get(1) )
{	default:		### List Items! ### INSERT INTO prefix_modules ( url, name, fright ) VALUES('raiditems', 'name', 1);
					if( $menu->getA(2) == 'd' ) { db_query("DELETE FROM prefix_raid_items WHERE id=".$menu->getE(2) ); }
					
					$tpl->set_out('max', db_result(db_query("SELECT COUNT(id) FROM prefix_raid_items"),0), 0);
					
					$limit = 30;  // Limit
					$page = ( $menu->getA(1) == 'p' ? escape($menu->getE(1), 'integer') : 1 );
					$MPL = db_make_sites ($page , '' , $limit , "?raiditems" , 'raid_items' );
					$anfang = ($page - 1) * $limit;
					
					$res = db_query("SELECT id, name, `drop`, class FROM prefix_raid_items ORDER BY `drop` DESC, id ASC LIMIT ".$anfang.", ".$limit);
					while( $row = db_fetch_assoc( $res ) )
					{	$row['name'] = "<a href='http://wowdata.buffed.de/?i=".$row['id']."' target='_blank' class='".$row['class']."'>".$row['name']."</a> ";
						#$row['del'] = aLink("<img src='include/images/icons/del.gif' border='0'>", "raiditems-".$menu->get(1)."-d".$row['id'], 1);
						$js = "onmouseover=\"showItemImage('".$row['id']."');\" onmouseout=\"hideItemImage('".$row['id']."');\"";
						$row['del'] = "<a href=\"admin.php?raiditems-".$menu->get(1)."-d".$row['id']."\" ".$js."><img src='include/images/icons/del.gif' border='0'></a>";
						$row['id'] = nuller( $row['id'] );
						$tpl->set_ar_out( $row, 1);
					}
					
					$tpl->set_out("mpl", $MPL, 2);
					break;
	
	case "new": 	### neue Itemlist
					$pfad = "include/raid/";
					if( @is_writeable($pfad) )
					{	if( !file_exists( $pfad . "itemlist.gz" ) || $menu->get(2) == 1 )
						{	$tpl->out(4);
						}else
						{	$tpl->set_out('msg', 'Es Exestiert noch eine "include/raid/itemlist.gz"!<br />Wollen sie die &Uuml;perschreiben?<br />Ansonsten L&ouml;schen sie die Bitte!<br />'.aLink("JA - &Uuml;berschreiben", "raiditems-new-1", 1), 3);
						}
						
					}else
					{	$tpl->set_out('msg', 'Der Ordner "include/raid/" hat noch keine Schreibrechte.<br />Bitte geben sie ihm CHMOD 777<br />'.aLink("Erneut Pr&uuml;fen", "raiditems-new", 1), 3);
					}
					break;
					
	case "copy": 	### KOPIEREN
					if( @copy("http://wowdata.buffed.de/xml/itemlist.gz", "include/raid/itemlist.gz" ) )
					{	$tpl->set_out('msg', 'Das Kopieren war erfolgreich! '.aLink("weiter", "raiditems-install", 1), 3);
					}else
					{	$tpl->set_out('msg', 'Das Kopieren war <b>nicht</b> erfolgreich! '.aLink("selbst Hochladen", "raiditems-new-1", 1), 3);
					}
					break;
					
	case "install": ### INSTALLIEREN
					if( isset( $_FILES['file'] ) )
					{	if( $_FILES['file']['size'] > 0 AND $_FILES['file']['name'] == "itemlist.gz" )
						{	if( move_uploaded_file( $_FILES['file']['tmp_name'], "include/raid/". $_FILES['file']['name'] ) )
							{	$tpl->set_out("msg", "<br /><span id='installItems'><a href='javascript:ajaxInstallItems(\"include/raid/raidAjax.php?ajaxAction=installItems\",\"installItems\");'>INSTALLIEREN</a></span><br />", 3);
							}else
							{	wd("admin.php?raiditems-new-1", "Das Hochladen war nicht erfolgreich!");
							}
						}else
						{	wd("admin.php?raiditems-new-1", "Es wurde keine Datei ausge&auml;t, oder es war eine Falsche Datei!");
						}
					}else
					{	$tpl->set_out("msg", "<br /><span id='installItems'><a href='javascript:ajaxInstallItems(\"include/raid/raidAjax.php?ajaxAction=installItems\",\"installItems\");'>INSTALLIEREN</a></span><br />", 3);
					}
					break;
}

copyright();
$design->footer();

?>