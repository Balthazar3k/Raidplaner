<div style="margin: 25px;">
    <div style="float: left;">
        {if $address.address_company}{$address.address_company}<br>{/if}
        {$address.address_first_name}
        {$address.address_last_name}<br>
        {$address.address_street} {$address.address_street_nr}<br>
        {$address.address_zipcode} {$address.address_place}<br><br>
        {$address.address_phone}
    </div>

    <div style="float: right;">
        Nigg's Hofladen<br>
        Auelen 1<br>
        FL - 9496 Balzers<br>
        <br>
        Tel: +423 384 24 86<br>
        Fax +423 384 24 93<br>
    </div>

    <br style="clear: both;">
    
    <h4>
        Rechnungsnummer: {$order_id}
    </h4>

    <table width="100%" border="1" cellpadding="5">
        <thead>
            <tr>
                <td align="right">Art.-Nr.</td>
                <td>Artikel</td>
                <td align="center">Preis - Menge</td>
                <td align="center">Menge</td>
                <td align="center">Preis</td>
            </tr>
        </thead>
        <tbody>
        {foreach $article as $i}
            <tr id="article{$i.article_id}">
                <td align="right">{$i.article_id}</td>
                <td><b>{$i.article_name}</b></td>
                <td align="center">{$i.article_grossprice|price} f&uuml;r {$i.article_amount}{$i.unit_short} </td>
                <td align="center"><b>{$smarty.session.shop.cart[$i.article_id].user_amount}</b> {$i.unit_unit}</td>
                <td align="center">{(($smarty.session.shop.cart[$i.article_id].user_amount / $i.article_amount) * $i.article_grossprice|round:2)|price}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfood>
            <tr>
                <td colspan="3"><span class="small"><i>alle Angaben inkl. MwSt.</i></span> </td>
                <td align="right">Total</td>
                <td align="center"><b>{$smarty.session.shop.price|price}</b></td>
            </tr>
        </tfood>
    </table>
</div>