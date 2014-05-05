<?php 
#   Copyright by: Balthazar3k
#   Support: www.Balthazar3k.funpic.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

switch($menu->get(1)){
    case 'save':
    break;
}

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

$articleCategory = $core->db()
        ->select('*')
        ->from('article_category')
        ->rows();


$tpl = $core->smarty();
$tpl->assign('categorys',
    
);



$design->footer();
?>