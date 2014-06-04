<style type="text/css">
    body, table{
        font-size: 12px;
    }
</style>

<script>
{literal}
    window.onload=function(){
        window.print();
    };
{/literal}
</script>

<div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Kommissionsauftrag - {$data.order.order_id}</b>
        </div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <td>
                        <i class="fa fa-home fa-2x"></i> <b>Adresse</b>
                        <hr style="margin-bottom: 2px; margin-top: 4px;">
                        {$data.order.address_company}<br />
                        {$data.order.address_last_name}, {$data.order.address_first_name}<br />
                        {$data.order.address_street} {$data.order.address_street_nr}<br />
                        {$data.order.address_zipcode} {$data.order.address_place}<br />
                        <br />
                        <i class="fa fa-phone-square fa-lg"></i> {$data.order.address_phone}<br />
                    </td>
                    <td>
                        <i class="fa fa-info-circle fa-2x"></i> <b>Information</b>
                        <hr style="margin-bottom: 2px; margin-top: 4px;">
                        Bestellt am:
                        <b>{$data.order.order_date|date_format:'%a %d.%m.%Y um %H:%M Uhr'}</b><br /><br />
                        
                        Auftrags Nr.:
                        <b>{$data.order.order_id}</b><br />
                        
                        Zahlungstyp:
                        <b>{$payment_type[$data.order.order_payment].type}</b><br />

                        Bestelltyp:
                        <b>{$order_type[$data.order.order_type].type}</b><br />
                    </td>
                </tr>
            </table>
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
                {assign var='sum_price' value='0'}
                {foreach $data.article as  $i}
                    {$sum_price=$sum_price+$i.order.user_price}
                    <tr>
                        <td class="text-center">
                            
                        </td>
                        <td class="text-right">{$i.article.article_id}</td>
                        <td>
                            <b>{$i.article.article_name}</b>
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
                    <td class="text-center"><b>{$sum_price|price}  {'currency'|config}</b></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>             