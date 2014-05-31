<div id="article" class="col-lg-8">
    {if !empty($article) }
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Warenkorb</b> | {$article|count} Artikel
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-right">Art.-Nr.</th>
                    <th>Artikelname</th>
                    <th class="text-center">Preis - Menge</th>
                    <th class="text-center table-bordered" colspan="4">&Auml;nderungen</th>
                    <th class="text-right">Preis</th>
                </tr>
            </thead>
            <tbody>
            {foreach $article as $i}
                <tr id="article{$i.article_id}">
                    <td class="text-right">{$i.article_id}</td>
                    <td><b>{$i.article_name}</b></td>
                    <td class="text-center small">{$i.article_grossprice|price} f&uuml;r {$i.article_amount}{$i.unit_short} </td>
                    <td class="text-center table-bordered"><a class="btn btn-success btn-xs" href="index.php?shop-order-recalc&data=p&{$i|http_build_query}#article-{$i.article_id}"><i class="fa fa-plus-circle"></i></a></td>
                    <td class="text-center table-bordered"><b>{$smarty.session.shop.cart[$i.article_id].user_amount}</b> {$i.unit_unit}</td>
                    <td class="text-center table-bordered"><a class="btn btn-warning btn-xs"  href="index.php?shop-order-recalc&data=m&{$i|http_build_query}#article-{$i.article_id}"><i class="fa fa-minus-circle"></i></a></td>
                    <td class="text-center table-bordered"><a class="btn btn-danger btn-xs"  href="index.php?shop-order-delete-{$i.article_id}"><i class="fa fa-trash-o"></i></a></td>
                    <td class="text-right info">{$smarty.session.shop.cart[$i.article_id].user_price|price}</td>
                </tr>
            {/foreach}
            </tbody>
            <tfood>
                <tr>
                    <td colspan="6"><span class="small"><i>alle Angaben inkl. MwSt.</i></span> </td>
                    <td class="text-right" >Total</td>
                    <td class="text-right"><b>{$smarty.session.shop.price|price}</b></td>
                </tr>
            </tfood>
        </table>        
    </div>
    <form class="form form-horizontal" action="index.php?shop-order-success" method="post">
        <div class="pull-left">
            <label for="agb">
                <input id="agb" type="checkbox" name="agb" value="1" onclick="$('button[type=submit]').toggleClass('disabled');">
                Hier mit Best&auml;tige ich die <a href="index.php?agb">AGB</a>
            </label>
        </div>
        <div class="pull-right">
            <button type="submit" class="btn btn-success disabled">Bestellung abschicken</button>
        </div>
    </form>
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

<div id="article" class="col-lg-4">
    <ul class="list-group list-group-default">
        <a class="list-group-item list-group-item-info text-center" href="index.php?shop-order-reset-order_type">
            <b>{$order.type}</b>
            <i class="fa fa-edit pull-right"></i>
            <br style="clearboth">
        </a>
        <li class="list-group-item text-center">
            <b>{$order.title}</b>
            <hr>
            <p>
                {$order.message}
            </p>
        </li>
        <a class="list-group-item list-group-item-info" href="index.php?shop-order-reset-order_payment">
            <i class="fa fa-dollar fa-lg"></i> <b>Zahlungsmethode: {$payment.type}</b>
            <i class="fa fa-edit pull-right"></i>
            <br style="clearboth">
        </a>
        <li class="list-group-item text-center">
            <b>{$payment.title}</b>
            <hr>
            <p>
                {$payment.message}
            </p>
        </li>
        <a class="list-group-item list-group-item-info" href="index.php?shop-order-reset-order_address">
            <i class="fa fa-home fa-lg"></i> <b>Lieferadresse</b>
            <i class="fa fa-edit pull-right"></i>
            <br style="clearboth">
        </a>
        <li class="list-group-item">
            {if $address.address_company}{$address.address_company}<br>{/if}
            {$address.address_first_name}
            {$address.address_last_name}<br>
            {$address.address_street} {$address.address_street_nr}<br>
            {$address.address_zipcode} {$address.address_place}<br><br>
            {$address.address_phone}
        </li>
        
    </ul>
</div>
