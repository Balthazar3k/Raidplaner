<?php 
#   Copyright by: Balthazar3k
#   Support: Balthazar3k.funpic.de

defined ('main') or die ( 'no direct access' );

$design = new design ( $title , $hmenu );
$design->header();

function show_Spanish($n, $m)
{
    return("Die Zahl $n heißt auf Spanisch  $m");
}

function map_Spanish($n, $m)
{
    return(array($n => $m));
}

class test {
    protected $b = array(
        "uno" => "Bananas", 
        "dos" => "Bananas", 
        "tres" => "Bananas", 
        "cuatro" => "Bananas", 
        "cinco" => "Bananas"
    );
    
    public function maskedValues(){
        $this->b = array_map("mysql_real_escape_string", $this->b);
        return $this->b;
    }
}

$c = new Test();
print_r($c->maskedValues());

$article_id = array();
foreach ($_SESSION['shop']['cart'] as $key => $val){
    $article_id[] = $val['article_id'];
}

if(is_array($article_id)) {
    $article = $core->db()->queryRows(standart_article_sql() . "
        WHERE a.article_id IN(".implode(',', $article_id).");
    ");
}



$tpl->assign('article', $article);
$tpl->display('shoppingcart.tpl');
$core->func()->ar($article_id, $_SESSION['shop'], $article);

$design->footer();
?>