<?php 

function addgrp($menu){
	
	db_query('INSERT INTO prefix_raid_zeitgruppen (name) VALUES ("'.$_POST["Gruppenname"].'")');
	echo 'Gruppe '.$_POST["Gruppenname"].' wurde erstellt';
}

function addchars($menu){
	$stmt = 'INSERT INTO prefix_raid_zeitgruppen_chars (grpid,charid) VALUES';
	$trennzeichen = '';
	$auswahl = $_POST["Charauswahl"];

	foreach ($auswahl as $value){
		$stmt = $stmt.$trennzeichen.'('.$_POST["grpid"].','.$value.')';
			$trennzeichen = ',';
	}	
	db_query($stmt);
	echo 'Benutzer hinzugef&uuml;gt';
}

function removechars($menu){
	$auswahl = $_POST["Grpcharauswahl"];

	foreach ($auswahl as $value){
		db_query('DELETE FROM prefix_raid_zeitgruppen_chars WHERE id = '.$value);
	}	
	
	echo 'Benutzer entfernt';
}

function selgrp($menu){
	echo'<BR>&nbsp;<BR>';

	$sql_res =  db_query( 'SELECT * FROM prefix_raid_zeitgruppen ORDER BY name ASC');

	echo '		<SELECT NAME="Gruppenauswahl" onChange="MM_jumpMenu(\'parent\',this,0)">
			<OPTION selected>Auswahl:</OPTION>';
	while( $row = db_fetch_assoc( $sql_res )){
		echo '	
		 	<OPTION VALUE="admin.php?raidzeiten-gruppen-'.$row["ID"].'-'.$row["Name"].'">'.$row["Name"].'</OPTION>
			';
	}
	echo'		</SELECT>';

	echo'<BR>&nbsp;<BR>';
}

function showchars($menu){
	$sql_res =  db_query( 'SELECT * FROM prefix_raid_chars ORDER BY name ASC');
	echo'<BR>&nbsp;<BR>';
	echo'<FORM NAME="addchars"  method="post" action="admin.php?raidzeiten-gruppen-addchars">';
	echo'<INPUT TYPE=HIDDEN NAME="grpid" VALUE='.$menu->get(2).'></INPUT>';
	echo '		<SELECT NAME="Charauswahl[]" SIZE=20 multiple>	';
	
	while( $row = db_fetch_assoc( $sql_res )){
		echo '	
		 	<OPTION VALUE="'.$row["id"].'">'.$row["name"].'</OPTION>
			';
	}

	echo '		</SELECT>';
	echO '<BR>&nbsp;<BR><INPUT TYPE=SUBMIT VALUE="hinzuf&uuml;gen"></INPUT>';
	echo '</FORM>';
	echo'<BR>&nbsp;<BR>';

}

function showgrpchars($menu){
	$stmt = 'SELECT prefix_raid_zeitgruppen_chars.id, prefix_raid_chars.name FROM prefix_raid_zeitgruppen_chars, prefix_raid_chars WHERE prefix_raid_zeitgruppen_chars.charid=prefix_raid_chars.id AND prefix_raid_zeitgruppen_chars.grpid='.$menu->get(2).'  ORDER BY prefix_raid_chars.name ASC';

	$sql_res =  db_query( $stmt);
		
	echo'<BR>&nbsp;<BR>';
	echo'<FORM NAME="removechars"  method="post" action="admin.php?raidzeiten-gruppen-removechars">';
	echo'<INPUT TYPE=HIDDEN NAME="grpid" VALUE='.$menu->get(2).'></INPUT>';
	echo '		<SELECT NAME="Grpcharauswahl[]" SIZE=20 multiple>	';
	
	while( $row = mysql_fetch_assoc( $sql_res )){
			echo '	
		 	<OPTION VALUE="'.$row["id"].'">'.$row["name"].'</OPTION>
			';
	}

	echo '		</SELECT>';
	echO '<BR>&nbsp;<BR><INPUT TYPE=SUBMIT VALUE="entfernen"></INPUT>';
	echo '</FORM>';
	echo'<BR>&nbsp;<BR>';
}

function newgrp($menu){
	echo '
		<BR>&nbsp;<BR>
		<FORM NAME="NeueGruppe"  method="post" action="admin.php?raidzeiten-gruppen-add"> 
			Gruppe <INPUT TYPE=TEXT NAME="Gruppenname" LENGTH=20></INPUT> 
			<INPUT TYPE=SUBMIT VALUE="erstellen"></INPUT>
		</FORM>
	';

}

function editgrp($menu){
	echo '<TABLE BORDER=0><TR><TD COLSPAN=3 ALIGN = CENTER>Gruppe: '.$menu->get(3).'</TD></TR><TR><TD>';
	showchars($menu);
	echo '</TD><TD valign=CENTER>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '</TD><TD>';
	showgrpchars($menu);
	echo'</TD></TR></TABLE>';
}

function gruppen($menu){
	echo '<a href="admin.php?raidzeiten">Zeiten</A>&nbsp;&nbsp;<a href="admin.php?raidzeiten-gruppen">Gruppen</A><BR>&nbsp;<BR>';
	//$test =  $menu->get(2);
	//echo '|||'.$test.'|||';
	switch($menu->get(2)){
		case "add": addgrp($menu);
		break;
		case "addchars": addchars($menu);
		break;
		case "removechars": removechars($menu);
		break;
		case "" : selgrp($menu);newgrp($menu);
		break;
		default: editgrp($menu);
	}

}

function gruppenzeiten ($menu){
	echo '<a href="admin.php?raidzeiten">Zeiten</A>&nbsp;&nbsp;<a href="admin.php?raidzeiten-gruppen">Gruppen</A><BR>&nbsp;<BR>';
	$auswahl = $_POST["Gruppenauswahl"];
	echo 'Gruppen: (';
	$trennzeichen = '';
	foreach ($auswahl as $value){
		$sql_res =  db_query( 'SELECT Name FROM prefix_raid_zeitgruppen where ID='.$value);	
		$row = db_fetch_assoc( $sql_res );
		echo $trennzeichen.$row["Name"];
		$trennzeichen = ',';
	}	
	echo ')<BR>';

	echo '<TABLE BORDER=1>';
	echo '<TR><TD>Zeit</TD><TD>So</TD><TD>Mo</TD><TD>Di</TD><TD>Mi</TD><TD>Do</TD><TD>Fr</TD><TD>Sa</TD></TR>';
	$sql_res =  db_query( 'SELECT * FROM prefix_raid_zeit');
	while( $row = db_fetch_assoc( $sql_res )){
		echo '<TR><TD>'.$row[zeit].'</TD>';
		for ($i=0;$i<7;$i++){
			$trennzeichen = '';
			echo '<TD>(';
			foreach ($auswahl as $value){
				$sql_res2 =  db_query( 'SELECT COUNT(*) FROM prefix_raid_kalender, prefix_raid_zeitgruppen_chars  WHERE prefix_raid_zeitgruppen_chars.charid=prefix_raid_kalender.cid AND zid = '.$row["id"].' AND wid='.$i.' AND grpid = '.$value);	
				$count = db_result( $sql_res2 );
				echo $trennzeichen;
				echo '<A HREF="admin.php?raidzeiten-details-'.$row["id"].'-'.$i.'-'.$value.'">'.$count.'</A>';
				$trennzeichen = ',';
			}
			echo ')</TD>';
		}
		echo '</TR>';
	}
	echo '</TABLE>';


}

function details ($menu){
	echo '<a href="admin.php?raidzeiten">Zeiten</A>&nbsp;&nbsp;<a href="admin.php?raidzeiten-gruppen">Gruppen</A><BR>&nbsp;<BR>';
	$zid = $menu->get(2);
	$wid = $menu->get(3);
	$gid = $menu->get(4);
	$stmt = 'SELECT prefix_raid_chars.name FROM prefix_raid_kalender, prefix_raid_zeitgruppen_chars, prefix_raid_chars WHERE prefix_raid_kalender.cid = prefix_raid_zeitgruppen_chars.charid AND prefix_raid_kalender.cid= prefix_raid_chars.id AND prefix_raid_zeitgruppen_chars.grpid = '.$gid.' AND prefix_raid_kalender.zid='.$zid.' AND prefix_raid_kalender.wid = '.$wid.' ORDER BY prefix_raid_chars.name ASC';
	$sql_res =  db_query($stmt);
	while( $row = db_fetch_assoc( $sql_res )){
		echo $row["name"];
		echo '<BR>';
	}

}

function zeiten($menu){
	echo '<a href="admin.php?raidzeiten">Zeiten</A>&nbsp;&nbsp;<a href="admin.php?raidzeiten-gruppen">Gruppen</A><BR>&nbsp;<BR>';
	echo'<BR>&nbsp;<BR>';

	$sql_res =  db_query( 'SELECT * FROM prefix_raid_zeitgruppen ORDER BY name ASC');

	echo'<FORM NAME="showtimes"  method="post" action="admin.php?raidzeiten-gruppenzeiten">';
	echo '		<SELECT NAME="Gruppenauswahl[]" SIZE=5 multiple> ';
	while( $row = db_fetch_assoc( $sql_res )){
		echo '	
		 	<OPTION VALUE='.$row["ID"].'>'.$row["Name"].'</OPTION>
			';
	}
	echo'		</SELECT>';

	echo '<BR>&nbsp;<BR><INPUT TYPE = SUBMIT VALUE="anzeigen"></INPUT>';
	echo '</FORM>';
	echo'<BR>&nbsp;<BR>';
}

defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );
$design = new design ( 'Admins Area', 'Admins Area', 2 );
require_once("include/includes/func/b3k_func.php");
$design->addheader($raidHeader);
$design->header();
$page = "admin.php?raidzeiten".$menu->get(1);

echo'
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
';
aRaidMenu();

switch ($menu->get(1)){
	case "gruppen": gruppen($menu);
	break;
	case "gruppenzeiten": gruppenzeiten($menu);
	break;
	case "details": details($menu);
	break;
	default: zeiten($menu);
	break;
}


$design->footer();
?>
