<?php 
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );
$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

include("include/includes/func/b3k_func.php");
echo '<script src="include/includes/js/b3k.js" language="JavaScript" type="text/javascript"></script>';

aRaidMenu();

$design->footer();
?>