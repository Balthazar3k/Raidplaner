{debug}
<div class="col-lg-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Bestellung</b>
        </div>
        <div class="panel-body">
            <div class="col-lg-4">
                <b>Adresse</b>
                <hr style="margin-bottom: 2px; margin-top: 4px;">
                {$data.order.address_company}<br />
                {$data.order.address_last_name}, {$data.order.address_first_name}<br />
                {$data.order.address_street} {$data.order.address_street_nr}<br />
                {$data.order.address_zipcode} {$data.order.address_place}<br />
                <br />
                <i class="fa fa-phone-square fa-lg"></i> {$data.order.address_phone}<br />
            </div>
            <div class="col-lg-4">
                <b>Information</b>
                <hr style="margin-bottom: 2px; margin-top: 4px;">
                Bestellt am:
                <b>{$data.order.order_date|date_format:'%a %d.%m.%Y um %H:%M Uhr'}</b><br /><br />
                
                Zahlungstyp:
                <b>{$payment_type[$data.order.order_payment].type}</b><br />
                
                Bestelltyp:
                <b>{$order_type[$data.order.order_type].type}</b><br />
            </div>
            <div class="col-lg-4">
                <b>Optionen</b>
                <hr style="margin-bottom: 2px; margin-top: 4px;">
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-right">Art.Nr.</th>
                    <th>Art.Name</th>
                    <th class="text-center">Einheit</th>
                    <th class="text-center">Menge</th>
                    <th class="text-center">Einheitspreis</th>
                    <th class="text-center">Gesamt Preis</th>
                </tr>
            </thead>
            <tbody>
                {foreach $data.article as  $i} fa-square
                <tr>
                    <td class="text-center success">
                        <i class="fa fa-check-square fa-2x"></i>
                        <i class="fa fa-square fa-2x"></i>
                    </td>
                    <td class="text-right">{$i.article.article_id}</td>
                    <td>
                        <b>{$i.article.article_name}</b>
                        <div class="small text-justify">{$i.article.article_description|truncate:128:'...':true}</div>
                    </td>
                    <td class="text-center">{$i.article.article_amount} {$i.article.unit_short}</td>
                    <td class="text-center">{$i.order.user_amount} {$i.article.unit_short}</td>
                    <td class="text-center">{$i.article.article_grossprice|price} {'currency'|config}</td>
                    <td class="text-center"><b>{$i.order.user_price|price} {'currency'|config}</b></td>
                </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center">xxx</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>