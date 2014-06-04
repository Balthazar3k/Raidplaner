<div id="article">
    {if !empty($article) }
    <div class="btn-group btn-default pull-right">
        <a class="btn btn-info" href="index.php?shop-article-{$menu[2]}"><i class="fa fa-reply-all"></i> Weiter Einkaufen gehen</a>
        <a class="btn btn-default" href="index.php?shop-shoppingcart-clear"><i class="fa fa-shopping-cart"></i> Warenkorb Leeren</a>
    </div><br style="clear: both;" />
    <hr>
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Warenkorb</b> | {$article|count} Artikel
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-right">Art.-Nr.</th>
                    <th>Artikelname</th>
                    <th class="text-center">Kategorie</th>
                    <th class="text-center">Brutto - Menge</th>
                    <th class="text-center table-bordered" colspan="4">&Auml;nderungen</th>
                    <th class="text-center">MwSt.</th>
                    <th class="text-right">Preis</th>
                </tr>
            </thead>
            <tbody>
            {foreach $article as $i}
                <tr id="article{$i.article_id}">
                    <td class="text-right">{$i.article_id}</td>
                    <td><b>{$i.article_name}</b></td>
                    <td class="small text-center">{$i.category_name}</td>
                    <td class="text-center small">{$i.article_grossprice|price} f&uuml;r {$i.article_amount}{$i.unit_short} </td>
                    <td class="text-center table-bordered"><a class="btn btn-success btn-xs" href="index.php?shop-shoppingcart-recalc&data=p&{$i|http_build_query}#article-{$i.article_id}"><i class="fa fa-plus-circle"></i></a></td>
                    <td class="text-center table-bordered"><b>{$smarty.session.shop.cart[$i.article_id].user_amount}</b> {$i.unit_unit}</td>
                    <td class="text-center table-bordered"><a class="btn btn-warning btn-xs"  href="index.php?shop-shoppingcart-recalc&data=m&{$i|http_build_query}#article-{$i.article_id}"><i class="fa fa-minus-circle"></i></a></td>
                    <td class="text-center table-bordered"><a class="btn btn-danger btn-xs"  href="index.php?shop-shoppingcart-delete-{$i.article_id}"><i class="fa fa-trash-o"></i></a></td>
                    <td class="text-center">{$i.article_tax}%</td>
                    <td class="text-center info">{$smarty.session.shop.cart[$i.article_id].user_price|price} {'currency'|config}</td>
                </tr>
            {/foreach}
            </tbody>
            <tfood>
                <tr>
                    <td colspan="8"><span class="small"><i>alle Angaben inkl. MwSt.</i></span> </td>
                    <td class="text-right" >Total</td>
                    <td class="text-center"><b>{$smarty.session.shop.price|price} {'currency'|config}</b></td>
                </tr>
            </tfood>
        </table>        
    </div>
    <a class="btn btn-success pull-right" href="index.php?shop-order">Bestellen</a>
    {else}
        <br style="clear: both;" />
        <div class="alert alert-info">
            <i class="fa fa-info-circle fa-2x pull-left"></i> 
            <p class="pull-left">
                Es sind keine Artikel im Warenkorb<br />
                <a class="btn btn-default" href="index.php?shop-article-{$menu[2]}"><i class="fa fa-reply-all"></i> Einkaufen gehen</a>
            </p>
            <br style="clear: both;" />
        </div>
    {/if}
</div>
