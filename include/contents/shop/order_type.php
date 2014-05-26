<?php
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

$design = new design ( $title , $hmenu );
$design->header();

order_progressbar();

$tpl->assign('type', order_type());
$tpl->display('order_type.tpl');

$design->footer();
?>