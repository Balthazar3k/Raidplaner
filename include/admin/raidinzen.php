<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");

$design->header();

echo "<br>";
if( !RaidPermission(0, TRUE) ){ echo "don't Permission"; $design->footer(); exit(); }

RaidErrorMsg();
aRaidMenu();

$imgPath = "include/raidplaner/images/dungeon/";

$tpl = new tpl ( 'raid/raidinzen.htm',1 );

if( $_POST['img'] == 'true' ){
    if( $_FILES['imgUp']['size'] > 0 ){
        $_POST['img'] = ascape(str_replace(" ", "", $_FILES['imgUp']['name']));
        $stat = @move_uploaded_file($_FILES['imgUp']['tmp_name'], $imgPath.$_POST['img']);
    }else{
        $stat = FALSE;
    }
}else{
    $stat = TRUE;
}

switch($menu->get(1)){
    
    case "new":
        unset($_POST['id']);
        if(  $raid->db()->insert('raid_inzen')->fields($_POST)->init() ){
            wd('admin.php?raidinzen','Neuer eintrag war erfolgreich!');
        }else{
            wd('admin.php?raidinzen','Neuer eintrag war <b>nicht</b> erfolgreich!', 10);
        }
    break;
    
    case "edit":
        if( $stat and $raid->db()->update('raid_inzen')->where(array('id' => getPost('id')))->fields($_POST)->init() ){
            wd('admin.php?raidinzen','Update war erfolgreich!');
        }else{
            wd('admin.php?raidinzen','Update war <b>nicht</b> erfolgreich!', 10);
        }
    break;
    case "del":
        if( $menu->get(3) == "TRUE" ){
            if( $raid->db()->delete('raid_inzen')->where(array('id' => $menu->get(2)))->init() ){
                wd('admin.php?raidinzen','L&ouml;schen war erfolgreich!');
            }else{
                wd('admin.php?raidinzen','L&ouml;schen war <b>nicht</b> erfolgreich!');
            }
        }else if( $menu->get(3) == "" ){
            $inze = $raid->db()->select('name')->from('raid_inzen')->where(array('id' => $menu->get(2)))->cell();
            echo $raid->confirm()
                ->message("Den Dungeon \"".$inze."\" wirklich L&ouml;schen?")
                ->onTrue("admin.php?raidinzen-del-".$menu->get(2)."-TRUE")
                ->onFalse("admin.php?raidinzen")
                ->html('L&ouml;schen');
        }
    break;
	case "delImg":
		if( @unlink( $imgPath.$_GET['img'] ) ){
			wd('admin.php?raidinzen','Bild L&ouml;schen war erfolgreich!');
		}else{
			wd('admin.php?raidinzen','Bild L&ouml;schen war <b>nicht</b> erfolgreich!');
		}
	break;
	default:
		if( $menu->get(1) == '' ){
                    $out['pfad'] = "admin.php?raidinzen-new";
                    $out['name'] = $out['maxbosse'] = $out['small'] = "";
                    $out['level'] = drop_down_menu("prefix_raid_level" , "level", "", "");
                    $out['info'] = drop_down_menu("prefix_raid_info" , "info", "", "");
                    $out['grpsize'] = drop_down_menu("prefix_raid_grpsize" , "grpsize", "", "");
                    $out['img'] = img_popup( $imgPath, 'img');
                    $out['regeln'] = "";			
		}else{
                    $res = db_query("SELECT * FROM prefix_raid_inzen WHERE id=".$menu->get(1)." LIMIT 1" );
                    $out = db_fetch_object( $res );
                    $out->pfad = "admin.php?raidinzen-edit";
                    $out->level = drop_down_menu("prefix_raid_level" , "level", $out->level, "");
                    $out->info = drop_down_menu("prefix_raid_info" , "info", $out->info, "");
                    $out->grpsize = drop_down_menu("prefix_raid_grpsize" , "grpsize", $out->grpsize, "");
                    $out->img = img_popup( $imgPath, 'img', $out->img);
		}
		
		$tpl->set_ar_out( $out, 0 );
		
		$res = db_query("
                    SELECT 
                        a.id, a.small, a.name, a.img, a.maxbosse, 
                        b.level, 
                        c.grpsize, 
                        d.id AS iid, d.info  
                    FROM prefix_raid_inzen AS a
                        LEFT JOIN prefix_raid_level AS b ON a.level=b.id 
                        LEFT JOIN prefix_raid_grpsize AS c ON a.grpsize=c.id 
                        LEFT JOIN prefix_raid_info AS d ON a.info=d.id
                    ORDER BY iid ASC, b.level DESC
                ");
		
		if( db_num_rows( $res ) ){
			while( $row = db_fetch_object( $res ) ){
				$c1iid = $row->iid;
				if( $row->iid != $c2iid ){ $tpl->set_out("msg","<b><div align='left'>".$row->info."</div></b>", 2); }
				$row->classe = cssClass( $row->classe );
				$img = ( file_exists( $imgPath.$row->img ) ? "<font color='green'>".$row->img."</font>" : "<font color='red'>Bild Existiert nicht!</font>");
				$row->imginfo = ( $row->img != NULL ? $img : "<font color='blue'>Kein Bild</font>" );
				$row->img = ( is_file( $imgPath.$row->img ) ? "<img width='30' src='".$imgPath.$row->img."'>" : "" );
				$row->raids = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen=".$row->id),0);
				$row->bkills = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_bosscounter WHERE grpid=".$row->id),0);
				$row->del = "<a href='admin.php?raidinzen-del-".$row->id."'><img src='include/images/icons/del.gif'></a>";
				$row->edit = "<a href='admin.php?raidinzen-".$row->id."'><img src='include/images/icons/edit.gif'></a>";
				$tpl->set_ar_out($row, 1);
				$c2iid = $c1iid;
			}
		}else{
			$tpl->set_out( "msg", "Keine DKP Gruppen Vorhanden!", 2 );
		}
		
		$tpl->out(3);
		$countFiles = CountFiles( $imgPath );
		if( $countFiles != 0 ){
			$open = opendir( $imgPath );
			while( $img = readdir( $open ) ){
				if( $img != "." and $img != ".." ){
					$del = aLink("<img src='include/images/icons/del.gif'>","raidinzen-delImg&img=".$img,1);
					$tpl->set_ar_out( array( "class" => "Cnorm", "img" => aLink($img,$imgPath.$img,2), "del" => $del),4);
				}
			}
			closedir();
		}else{
			$tpl->set_out( "msg", "Keine DKP Gruppen Bilder Vorhanden!", 5 );
		}
	
		$tpl->out(6);
	break;
}

copyright();

$design->footer();

?>