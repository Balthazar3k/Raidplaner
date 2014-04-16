<?php 
defined ('main') or die ( 'no direct access' );

require_once("include/includes/func/b3k_func.php");

if( isset( $_POST['kid'] ))
{	exit(classSpecialization($_POST['kid']));
}

$title = $allgAr['title'].' :: Chars';
$hmenu = 'Chars';
$design = new design ( $title , $hmenu );
$design->header();

RaidErrorMsg();

$img_del = "<img src='include/images/icons/del.gif' border='0'>";
$img_edit = "<img src='include/images/icons/edit.gif' border='0'>";

$kalout .= $_SESSION['authid'] ."=". $uid_s;
switch($menu->get(1)){
	
    case "del":
        $name = $raid->charakter($menu->get(2))->name();

        if( $menu->get(3) != "true" ){

            echo $raid->confirm()
                ->message("Wirklich alle daten von \"".$name."\" L&ouml;schen?")
                ->onTrue('index.php?'.$_SERVER['QUERY_STRING'].'-true')
                ->onFalse('index.php?bewerbung')
                ->html('L&ouml;schen');

        }

        if( $menu->get(3) == "true" ){
            if( $raid->charakter()->owner($menu->get(2)) || $raid->permission()->delete('charakter', $message) ){

                $raid->charakter()->delete($menu->get(2));
                wd('index.php?chars',$name.' wurde erfolgreich gel&ouml;scht!', 1);
            }else{
                wd('index.php?chars',$name.' wurde "<b>NICHT</b>" erfolgreich gel&ouml;scht!', 3);
            }
        }

    break;
	   
    case "form":
        button("Zur&uuml;ck","",8);
        $raid->charakter()->form('Charakter Formular', 'index.php?chars-save-'.$menu->get(2), $raid->charakter($menu->get(2))->get()); 
    break;
    
    case "save":

        $charakter = array(
            'rang' => 2,
            'user' => $_SESSION['authid']
        );
        
        $charakter = array_merge($_POST, $charakter);

        if( $raid->charakter($menu->get(2))->save($charakter) ){
            wd("index.php?chars","Charakter ".$charakter['name']." wurde erfolgreich gespeichert!", 3);
        }else{
            wd("index.php?chars","Charakter ".$charakter['name']." wurde <b>nicht</b> erfolgreich gespeichert!", 3);
        }

    break;
    
	#### Raid Tage
	case "raidtage";
		@db_query("DELETE FROM prefix_raid_kalender WHERE cid=".$menu->get(2) );
		foreach( $_POST as $key => $value ){
			list( $zeit, $wochtag ) = explode("-", $key);
			if( $zeit != 0 ){
				db_query('INSERT INTO `prefix_raid_kalender` (`cid`, `zid`, `wid`) VALUES (\''.$menu->get(2).'\', \''.$zeit.'\', \''.$wochtag.'\');');
			}
		}
		wd('index.php?chars--show-'.$menu->get(2),'Raidtage wurde ge�ndert');
	break;
	
	case "show":
		$tpl = new tpl ('raid/CHARS_DETAILS.htm');
		button("Zur&uuml;ck","",8);
		$row = db_fetch_assoc(db_query("SELECT 
                        a.name, a.teamspeak, a.mberuf, a.mskill, a.sberuf, a.sskill, a.raiden, a.warum, a.pvp, a.skillgruppe, a.level,
                        a.s1, a.s2, a.s3, a.realm, a.user, a.punkte, a.id, a.rlname, 
                        b.id as klassenid, b.klassen,  
                        d.id as rangid,
                        d.rang, 
                        f.name AS username, 
                        e.rassen
                 FROM prefix_raid_chars AS a 
                        LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                        LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
                        LEFT JOIN prefix_raid_rassen AS e ON a.rassen = e.id 
                        LEFT JOIN prefix_user AS f ON a.user = f.id 
                 WHERE a.id = ".$menu->get(2) ) );
		
		$row['TITEL'] = "Deatails von ". $row['name'];
		$row['username'] = "<a href='index.php?user-details-".$row['user']."'>".$row['username']."</a>";
		$row['alter'] = alter(db_result(db_query("SELECT gebdatum FROM prefix_user WHERE id='".$row['user']."'"),0));
		$row['teamspeak'] = ( $row['teamspeak'] == 1 ? "Vorhanden" : "Kein Teamspeak" );
		$row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['klassenid']);
		$row['skillgruppe'] = skillgruppe(0,$row['skillgruppe']);
		### Raidkalender
		$wochentag = array( 0 => "So", 1 => "Mo", 2 => "Di", 3 => "Mi", 4 => "Do", 5 => "Fr", 6 => "Sa");
		$ctd = count($wochentag); #--- Z�hele Spalten (td)
		$res = db_query("SELECT * FROM prefix_raid_zeit");
		$kalout = '<form id="form" name="form" method="post" action="index.php?chars-raidtage-'.$menu->get(2).'">';
		$kalout .= "<table border='0' cellspacing='1' cellpadding='5' class='border'><tr class='Chead'><td></td>";
		for( $i=0; $i<$ctd; $i++ ){
			$kalout .= "<td><center>".$wochentag[$i]."<center></td>";
		}
		$kalout .= "</tr>";
		while( $rows = db_fetch_assoc($res)){
			if( $Class == $Cnorm ){ $Class = $Cmite; }else{ $Class = $Cnorm; }
			$kalout .= "<tr class='".$Class."'>";
			$kalout .= "<td class='Cdark'><div align='right'>".$rows['zeit']." Uhr</div></td>";
			for( $i=0; $i<$ctd; $i++ ){
				$erf = db_result(db_query("SELECT COUNT(cid) FROM prefix_raid_kalender WHERE cid='".$menu->get(2)."' AND zid='".$rows['id']."' AND wid='". $i."'"),0);
				if( $_SESSION['authid'] == $row['user'] && $row['rangid'] == $_SESSION['charrang'] ){
					$ck = ( $erf == 0 ? '' : 'checked' );
					$color = ( $erf == 0 ? 'darkred' : 'darkgreen' );
					$kalout .= '<td bgcolor="'.$color.'"><input name="'.$rows['id'].'-'.$i.'" type="checkbox" value="1" '.$ck.'></td>';
				}else{
					$color = ( $erf == 0 ? 'darkred' : 'darkgreen' );
					$symb = ( $erf == 0 ? 'X' : 'O' );
					$kalout .= '<td bgcolor="'.$color.'"><center>'.$symb.'</center></td>';
				}
			}
			$kalout .= "</tr>";
		}
		$kalout .= '</table>';
		if( $_SESSION['authid'] == $row['user']  && $row['rangid'] == $_SESSION['charrang'] ){
			$kalout .= '<input type="submit" name="button" id="button" value="Senden" />';
		}
		$kalout .= '</form>';
		
		$row['raid_kalender'] = $kalout;
		$tpl->set_ar_out( $row, 0 );
		
		#### WEITERE CHARAKTERE
		$tpl->out(7);
		
		$res = db_query("
			SELECT 
				a.id, a.name,
				b.level,
				c.klassen,
				d.rassen,
				e.rang
			FROM prefix_raid_chars AS a
			  LEFT JOIN prefix_raid_level AS b ON a.level=b.id
			  LEFT JOIN prefix_raid_klassen AS c ON a.klassen=c.id
			  LEFT JOIN prefix_raid_rassen AS d ON a.rassen=d.id
			  LEFT JOIN prefix_raid_rang AS e ON a.rang=e.id
			WHERE a.user = '".$row['user']."'
			  AND a.id != '".$row['id']."' 
			ORDER BY a.name ASC
		");
		
		while( $charaktere = db_fetch_assoc( $res ) ){
			$tpl->set_ar_out($charaktere, 8);
		}
		
		$tpl->out(9);
		
		### Deine Ausgegeben DKP
		$tpl->set_ar_out( array("TITEL" => "Deine DKP Ausgaben"), 1 );
		$abf = db_query("
		
		SELECT rid, prefix_raid_dkp.date, prefix_raid_gruppen.gruppen, info, dkp, prefix_raid_dkp.id 
		FROM prefix_raid_dkp 
		LEFT JOIN prefix_raid_gruppen ON prefix_raid_dkp.dkpgrp=prefix_raid_gruppen.id 
		WHERE dkp LIKE '-%' AND cid='".$row['id']." 
		ORDER BY date ASC'");
		
		while($row = db_fetch_assoc( $abf )){
			$row['date'] = "<a href='index.php?raidlist-showraid-".$row['rid']."'>" . date("d.m.Y H:i", $row['date']) . "</a>";
			$row['info'] = RaidItems($row['info'], $menu->get(0) );
			$row['CLASS'] = cssClass($row['CLASS']);
			$tpl->set_ar_out( $row, 2 );
		}
		$tpl->out(3);
		
		### RAIDBETEILGUNG
		$tpl->out(4);
		$abf = db_query("SELECT id, gruppen FROM prefix_raid_gruppen WHERE gruppen!='n/a'");
		while( $row = db_fetch_assoc( $abf )){
			$count_all_raids = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_raid WHERE gruppen='".$row['id']."' AND statusmsg=2"),0);
			$count_raids = db_result(db_query("SELECT COUNT(b.id) FROM prefix_raid_anmeldung AS a, prefix_raid_raid AS b WHERE a.grp='".$row['id']."' AND a.char='".$menu->get(2)."' AND a.stat='12' AND a.rid=b.id AND b.statusmsg=2"),0);
			if( $count_raids != 0 ){
				### Prozente Berechnen
				$row['prz'] = pzVortschritsAnzeige($count_raids,$count_all_raids);
				$row['vonbis'] = $count_raids ."/".  $count_all_raids;
				$row['CLASS'] = cssClass($row['CLASS']);
				### Datenausgabe
				$tpl->set_ar_out( $row, 5 );
			}
		}
		
		$tpl->out(6);
		
    break;
	
    default:
        $tpl = new tpl ('raid/CHARS_LIST.htm');

        $c['COUNT_CHARS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars"),0);
        $c['COUNT_MAINS'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang >= 4"),0);
        $c['COUNT_LEVEL'] = db_result(db_query("SELECT COUNT(level) FROM prefix_raid_chars WHERE level = '1'"),0);
        $c['COUNT_EIGENE'] = db_result(db_query("SELECT COUNT(user) FROM prefix_raid_chars WHERE user = '".$_SESSION['authid']."'"),0);
        $c['COUNT_BEWERBER'] = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE rang = 1"),0);

        if( RaidRechte($allgAr['addchar']) ){
                if( $allgAr['maxchars'] > $c['COUNT_EIGENE'] ){
                        $c['USER'] = "[ <a href='index.php?chars-form'>Neuer Char</a> ]";
                }else{
                        $c['USER'] = "Max. ".$c['COUNT_EIGENE']."/".$allgAr['maxchars']." Chars Erreicht";
                }
        }else{
                $c['USER'] = "LINK CLOSED";
        }
        ### Klassen Liste erstellen
        $erg = db_query("SELECT id, klassen FROM prefix_raid_klassen ORDER BY id DESC");
        $l_klassen = "<a href='index.php?chars'>".$img_del."</a> ";
        while( $row = db_fetch_assoc( $erg )){
                $c['list_klassen'] .= "<a href='index.php?chars-".$row['id']."'><img src='include/raidplaner/images/wowklein/".$row['klassen'].".gif' border=0></a> ";
        }
        ### Ausgabe der Daten.
        $tpl->set_ar_out( $c , 0 );
        ### CHARS AUFLISTEN ################################################################################################################

        $sort = ( $menu->get(1) != "" ? "AND a.klassen='".$menu->get(1)." '" : "" );

        $q = $_POST['search'];
        $res = db_query("
            SELECT 
                a.name, a.rang AS rangid, a.s1, a.s2, a.s3, a.realm, a.user, a.punkte, a.id, a.level, 
                b.id as klassenid, b.klassen, 
                d.rang, 
                e.name AS username 
             FROM prefix_raid_chars AS a 
                LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
                LEFT JOIN prefix_user AS e ON a.user = e.id 
             WHERE 
                a.name LIKE '$q%' 
                AND a.rang != 1    
                ".$sort." 
             ORDER BY b.klassen , a.klassen ASC, a.rang DESC, a.name ASC
        ");

        while( $row = db_fetch_assoc( $res )){
            $klassen = $row['klassen'];
            if( $klassen_to_change != $row['klassen'] ){
                    $c_klassen = db_result(db_query("SELECT COUNT(id) FROM prefix_raid_chars WHERE name LIKE '$q%' AND klassen=".$row['klassenid']),0);
                    $c_klassen .= "<a name='".$row['klassen']."'></a>";
                    $tpl->set_ar_out( array( "klass_name" => $row['klassen'], "COUNT_KLASSEN" => $c_klassen ), 1 );
            }

            ### Ausgaben �ndern/Hinzuf�gen
            $row['ARSENAL'] = "<a href='http://eu.battle.net/wow/de/character/".urlencode(utf8_encode($row['realm']))."/".str_replace(" ", "+",urlencode(utf8_encode($row['name'])))."/advanced' target='_blank'>Arsenal</a>";
            $row['CLASS'] = cssClass($row['CLASS']);
            $row['img'] = "<img src='include/raidplaner/images/wowklein/".$row['klassen'].".gif'>";
            $row['name'] = "<a href='index.php?chars-show-".$row['id']."' name='".$row['id']."'>".$row['name']."</a>";
            ### Skillung Auswerten
            $row['sb'] = char_skill($row['s1'],$row['s2'],$row['s3'],$row['klassenid']);
            ### Edit f�r Chareigent�mer
            if( $row['user'] == $_SESSION['authid']){
                    $row['edit'] = "<a href='index.php?chars-form-".$row['id']."'>".$img_edit."</a>";
            }else{
                    $row['edit'] = "";
            }

            $tpl->set_ar_out( $row, 2 );
            $klassen_to_change = $klassen;
        }

        $tpl->out(3);
        $tpl->out(4);
    break;
}

copyright();
$design->footer();
?>
