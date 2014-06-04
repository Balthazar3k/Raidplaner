<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
<div style="margin: 25px; color: #000;">
    <div style="float: left;">
        <img src="http://nigg.howald-design.ch/include/hpbilder/shop/onlineshop.png" border="0">
    </div>
    <br style="clear: both;">
    <br style="clear: both;">
    
    <div style="float: left;">
        <div style=" background-color: #95c0ad; border: 1px solid rgba(0,0,0,0.3); -webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px;padding: 25px" >
            <p style="font-size:16px;font-family: 'Ubuntu', sans-serif;font-weight: bold;">Ihre Anschrift:</p>
            </br>
                {if $address.address_company}{$address.address_company}<br>{/if}
                {$address.address_first_name}
                {$address.address_last_name}<br>
                {$address.address_street} {$address.address_street_nr}<br>
                {$address.address_zipcode} {$address.address_place}<br><br>
                {$address.address_phone}
        </div>
    </div>

    <div style="float:right">
        <div style=" background-color: #CCC; border: 1px solid rgba(0,0,0,0.3); -webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px;padding: 25px">
            Nigg's Hofladen<br>
            Auelen 1<br>
            FL - 9496 Balzers<br>
            <br><hr>
            Tel: +423 384 24 86<br>
            Fax +423 384 24 93<br>
        </div>
    </div>

    <br style="clear: both;">
    
    <h4>
        Rechnungsnummer: {$order_id}, Kundennummer: {$smarty.session.authid}
    </h4>
	
	Zahlungstyp: {$payment_type.title}<br>
	Bestellungstyp: {$order_type.title}<br><br>

        <table width="100%" border="1" cellpadding="5" cellspacing="0" style="font-family: 'Ubuntu', sans-serif;">
        <thead>
            <tr bgcolor="#95c0ad">
                <td align="right">Art.-Nr.</td>
                <td>Artikel</td>
                <td align="center">Preis - Menge</td>
                <td align="center">MwSt.</td>
                <td align="center">Menge</td>
                <td align="center">Preis</td>
            </tr>
        </thead>
        <tbody>
        {foreach $article as $i}
            <tr id="article{$i.article_id}">
                <td bgcolor="#d4d5d6" align="right">{$i.article_id}</td>
                <td><b>{$i.article_name}</b></td>
                <td align="center">{$i.article_grossprice|price} f&uuml;r {$i.article_amount}{$i.unit_short} </td>
                <td>{$i.article_tax}%</td>
                <td align="center"><b>{$smarty.session.shop.cart[$i.article_id].user_amount}</b> {$i.unit_unit}</td>
                <td align="center">{$smarty.session.shop.cart[$i.article_id].user_price|price}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
            <tr bgcolor="#95c0ad">
                <td colspan="4"><span class="small"><i>alle Angaben inkl. MwSt.</i></span> </td>
                <td align="right">Total</td>
                <td align="center"><b>{$smarty.session.shop.price|price}</b></td>
            </tr>
        </tfoot>
    </table>
            <div style="font-size:16px;font-family: 'Ubuntu', sans-serif;font-weight: bold;">
                Besten Dank f�r Ihre Bestellung bei www.hofladen.li
                <br style="clear: both;"> 
                Sobald der Status Ihrer Bestellung sich �ndern werden Sie per Mail eine Info erhalten.
                <br style="clear: both;">
                Wir freuen uns, Sie schon bald wieder in unserem Shop begr��en zu d�rfen!
                <br style="clear: both;">
            </div>
</div>