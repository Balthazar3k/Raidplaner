<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

$design = new design ( $title , $hmenu );
$design->header();

$tpl->assign('type', payment_type());
$tpl->display('payment_type.tpl');

$design->footer();
?>