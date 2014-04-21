<?php 
require_once("include/includes/func/b3k_func.php");

if( !RaidPermission(0, TRUE) ){ echo 'don\'t Permission'; $design->footer(); exit(); }

$cssPfad = 'include/admin/templates/';
$cssFile = 'style.css';

function groupinfos($cid,$id){
	$sql = "
            SELECT
                a.id AS cid,
                a.user AS uid,
                c.id AS grid, 
                b.stammgrp AS grname,
                d.uid AS guid, d.gid AS ggid, d.fid AS gfid 
            FROM prefix_raid_chars AS a 
                LEFT JOIN prefix_raid_stammgrp AS b ON b.id='".$id."'
                LEFT JOIN prefix_groups AS c ON b.stammgrp=c.name
                LEFT JOIN prefix_groupusers AS d ON c.id=d.gid AND a.user=d.uid
            WHERE a.id=".$cid
			;
	$res = db_query( $sql );
	return db_fetch_object( $res );
}

$tpl = new tpl ( 'raid/chars.htm',1 );
$table = new tpl ( 'raid/2zeilen4spalten.htm',1 );

switch($menu->get(1)){
	
	case "editrang":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		
		if(is_admin() or $_SESSION['charrang'] >= $allgAr['char_rang_edit'] and $_SESSION['charrang'] >= $row['rangid'] ){
			setModulrightsForCharRang($menu->get(2),'remove');
			if( db_query("UPDATE prefix_raid_chars SET rang='".$menu->get(3)."' WHERE id='".$menu->get(2)."'") ){
				setModulrightsForCharRang($menu->get(2),'insert');
				wd('admin.php?chars-'.$menu->get(4),'Rang wurde erfolgreich ge&auml;ndert!', 0);
			}else{
				wd('admin.php?chars-'.$menu->get(4),'Rang wurde <b>nicht</b> erfolgreich ge&auml;ndert!');
			}
		}else{
			wd('admin.php?chars-'.$menu->get(4),'no Permission!');	
		}
	break;
	
    case "form":
        button("Zur&uuml;ck","",8);
        $raid->charakter()->form('Charakter Formular', 'index.php?chars-save-'.$menu->get(2), $menu->get(2));
    break;
    
    case "save":

        if( $raid->charakter($menu->get(2))->save($_POST) ){
            wd('admin.php?chars',"Charakter ".$charakter['name']." wurde erfolgreich gespeichert!", 3);
        }else{
            wd('admin.php?chars-details-'.$menu->get(2),"Charakter ".$charakter['name']." wurde <b>nicht</b> erfolgreich gespeichert!", 3);
        }

    break;
	
    case "del":
        defined ('main') or die ( 'no direct access' );
        defined ('admin') or die ( 'only admin access' );
        $design = new design ( 'Admins Area', 'Admins Area', 2 );

        $design->header();

        RaidErrorMsg();
        aRaidMenu();
                
        $name = $raid->charakter($menu->get(2))->name();

        if( $menu->get(3) != "true" ){

            echo $raid->confirm()
                ->message("Wirklich alle daten von \"".$name."\" L&ouml;schen?")
                ->onTrue('admin.php?'.$_SERVER['QUERY_STRING'].'-true')
                ->onFalse('admin.php?chars-details-'.$menu->get(2))
                ->html('L&ouml;schen');

        }

        if( $menu->get(3) == "true" ){
            if( $raid->permission()->delete('charakter', $message) ){

                $raid->charakter()->delete($menu->get(2));
                wd('admin.php?chars',$name.' wurde erfolgreich gel&ouml;scht!', 1);
            }else{
                wd('admin.php?chars',$name.' wurde "<b>NICHT</b>" erfolgreich gel&ouml;scht!', 3);
            }
        }
        
        $design->footer();
    break;
	
	case "addtostamm":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		$ginfo = groupinfos($menu->get(2),$_POST['stammgrp']);
		$count = db_result(db_query("SELECT COUNT(cid) FROM prefix_raid_stammrechte WHERE cid=".$menu->get(2)." AND sid=".$_POST['stammgrp']),0);
		if( $count == 0 ){
		db_query("INSERT INTO prefix_raid_stammrechte (cid, sid, eid) 
				VALUES('".ascape($menu->get(2))."','".ascape($_POST['stammgrp'])."','".ascape($_SESSION['charid'])."');");
		db_query("INSERT INTO `prefix_groupusers`(`uid`, `gid`, `fid`) VALUES('".$ginfo->uid."','".$ginfo->grid."','0');");
			wd('admin.php?chars-details-'.$menu->get(2),'Ist nun in einer Stamm Gruppe', 3);
		}else{
			wd('admin.php?chars-details-'.$menu->get(2),'Char ist bereits Mitglied dieser Gruppe!', 3);
		}
	break;
	
	case "delfromstamm":
		echo "<link rel='stylesheet' type='text/css' href='".$cssPfad.$cssFile."'>\n";
		$ginfo = groupinfos($menu->get(2),$menu->get(3));
		if( db_query("DELETE FROM prefix_raid_stammrechte WHERE cid=".$menu->get(2)." AND sid=".$menu->get(3)."")
			and db_query("DELETE FROM prefix_groupusers WHERE uid=".$ginfo->guid." AND gid=".$ginfo->ggid) ){
			wd('admin.php?chars-details-'.$menu->get(2),'Wurde erfolgreich Gel&ouml;scht', 3);
		}else{
			wd('admin.php?chars-details-'.$menu->get(2),'Wurde <b>nicht</b> erfolgreich Gel&ouml;scht', 3);
		}
	break;
	
	default:
		defined ('main') or die ( 'no direct access' );
		defined ('admin') or die ( 'only admin access' );
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		
		$design->header();
		
		RaidErrorMsg();
		aRaidMenu();
		
		#$classen = array();
		##### SUCHEN
		if( $_SESSION['authmod']['CharsEditKlassen'] != 1 ){
			$res = db_query("SELECT id, klassen FROM prefix_raid_klassen ORDER BY id DESC");
			while( $row = db_fetch_object( $res ) ){
				$classImg .= '<input type="image" name="klassen['.$row->id.']" src="include/raidplaner/images/class/class_'.$row->id.'.jpg" /> ';
				#$classen[$row->id] = $row->klassen;
			}
		
			$tpl->set_out("srcClass",$classImg,0); ### SUCHEN
			
			echo "<br />";
			button("NewChar", "admin.php?chars-new", 0);
			button("CharChangeAccount", "javascript:creatWindow( \"admin.php?extern-CharChangeAccount\", \"CharChangeAccount\", \"500\", \"70\" );", 12);
		}
				
		$search = ( isset( $_POST['search'] ) ? 'WHERE '. $_POST['from'].' LIKE \''.$_POST['search'].'%\' ' : '' );
		
		if( isset( $_POST['klassen'] ) ){
			$key = array_keys( $_POST['klassen'] );
			$search .= "AND b.id = ".$key[0]." ";
		}
		
		$tpl->out(1);
		$sql = "SELECT 
                        a.id, a.name, a.user, a.regist, a.s1, a.s2, a.level, 
                        b.id as kid, b.klassen, 
                        c.id as rid, c.rang, 
                        d.id as uid, d.name as uname, d.gebdatum
                FROM prefix_raid_chars AS a 
                        LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                        LEFT JOIN prefix_raid_rang AS c ON a.rang = c.id
                        LEFT JOIN prefix_user AS d ON a.user = d.id 
                ".$search."
                ".( $_SESSION['authmod']['CharsEditKlassen'] == 1  ? 'WHERE b.id = '.$_SESSION['charklasse'].' ' : '' )."
                ORDER BY c.id DESC, b.id DESC ";
				
				$limit = 18;  // Limit
				$page = ( $menu->getA(1) == 'p' ? escape($menu->getE(1), 'integer') : 1 );
				$MPL = db_make_sites ($page , '' , $limit , "?chars" , 'raid_chars', db_num_rows(db_query($sql)) );
				$anfang = ($page - 1) * $limit;
				
				$sql .= "LIMIT ".$anfang.", ".$limit;
				
		$res = db_query($sql);
		
		if( db_num_rows( $res ) != 0 ){
			while( $row = db_fetch_object( $res )){
				$aRang = $row->rang;
				if( $row->rang != $cRang ){
					$t['class'] = 'Cdark';
					$t['msg'] = "<b>".$row->rang."</b>";
					$tpl->set_ar_out( $t , 3);
				}
				$row->class = cssClass( $row->class );
				$row->img = class_img($row->kid);
				$row->name = aLink( $row->name, "chars-details-".$row->id, 1);
				$row->geb = ( $row->gebdatum != "0000-00-00" ? "[".alter($row->gebdatum)."]" : '');
				$row->uname = aLink( $row->uname, "user-1-".$row->uid , 1);
				if( is_admin() 
					or $_SESSION['charrang'] >= $allgAr['char_rang_edit'] 
					and $_SESSION['charrang'] >= $row->rid 
					and $_SESSION['charid'] != $row->id
					and $_SESSION['authid'] != $row->user)
				{
					$select = '<select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu(\'parent\',this,0)">';
						if( is_admin() ){
							$erg = db_query("SELECT id, rang FROM prefix_raid_rang");
						}else{
							$erg = db_query("SELECT id, rang FROM prefix_raid_rang WHERE id <=".($_SESSION['charrang'] - 1));
						}
					while( $mm = db_fetch_object( $erg )){
						$sel = ( $row->rid == $mm->id ? ' selected' : '' );
						$select .= '<option value="admin.php?chars-editrang-'.$row->id.'-'.$mm->id.'-'.$menu->get(1).'" '.$sel.'>'.$mm->rang.'</option>\n';
					}
					$select .= '</select>';
					$row->rang = $select;
				}else{
					$row->rang = $row->rang;
				} ## Rang Änderungen!
				$row->skill = char_skill($row->s1, $row->s2, $row->s3, $row->kid);
				$row->regist = DateToTimestamp($row->regist);
				$row->regist = DateFormat("D d.m.Y H:i:s", $row->regist, 1) ."  ". agoTimeMsg($row->regist);
				if( is_admin() 
					or $_SESSION['charrang'] >= $allgAr['char_rang_edit'] 
					and $_SESSION['charrang'] >= $row->rid
					and $_SESSION['authmod']['CharsEditKlassen'] != 1)
				{
					$row->edit = aLink( '<img src="include/images/icons/edit.gif">', "chars-details-".$row->id, 1);
					$row->del = aLink( '<img src="include/images/icons/del.gif">', "chars-del-".$row->id, 1);
				}else{
					$row->edit = aLink( '<img src="include/images/icons/edit.gif">', "chars-details-".$row->id, 1);
					$row->del = '';
				}
				$tpl->set_ar_out($row, 2);
				$cRang = $aRang;
			}
		}else{
			$t->class = 'Cnorm';
			$t->msg = "Es wurde kein Char gefunden!";
			$tpl->set_ar_out( $t , 3);
		}
		$tpl->set_out( "MPL", $MPL ,4);	
		
		copyright();
		
		$design->footer();
	break;
	
	case "details":
		## INHALT f�r TD1
		defined ('main') or die ( 'no direct access' );
		defined ('admin') or die ( 'only admin access' );
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		
		$design->header();
		
		RaidErrorMsg();
		aRaidMenu();
		
		button("Zur&uuml;ck", "admin.php?chars", 0);
		
		$table->out(0); #Table bis TD 1
		
                $raid->charakter()->form('Charakter Formular', 'admin.php?chars-save-'.$menu->get(2), $raid->charakter($menu->get(2))->get());
		
		$table->out(1); ## SCHLIEßT TD1 öffnet TD2
                $stamm = (object) array();
		$stamm->pfad = "admin.php?chars-addtostamm-".$menu->get(2);
		$stamm->stammgrp = drop_down_menu("prefix_raid_stammgrp" , "stammgrp", "", "");
		$tpl->set_ar_out($stamm,6);
		
		$res = db_query("
                    SELECT 
                        a.cid, a.sid, a.date, 
                        b.name, 
                        c.stammgrp 
                    FROM prefix_raid_stammrechte AS a
                        LEFT JOIN prefix_raid_chars AS b ON a.eid=b.id 
                        LEFT JOIN prefix_raid_stammgrp AS c ON a.sid=c.id 
                    WHERE a.cid=".$menu->get(2)."
                    ORDER BY a.sid ASC"
                );
		
		if( db_num_rows( $res ) != 0 ){
			while( $row = db_fetch_object( $res )){
				$row->del = aLink("<img src='include/images/icons/del.gif'>","chars-delfromstamm-".$row->cid."-".$row->sid, 1);
				$tpl->set_ar_out($row, 7);
			}
		}else{
			$tpl->set_out("msg","Ist in keiner Stamm Gruppe Mitglied!",8);
		}
		
		
		
		$tpl->out(9);
		$table->out(4); ###
		copyright();
		
		$design->footer();
	break;
	
	case "new":
		defined ('main') or die ( 'no direct access' );
		defined ('admin') or die ( 'only admin access' );
		$design = new design ( 'Admins Area', 'Admins Area', 2 );
		
		$design->header();
		
		RaidErrorMsg();
		aRaidMenu();
                
		$raid->charakter()->form('Charakter Formular', 'admin.php?chars-save');
		
		copyright();
                $design->footer();
	break;
}

?>