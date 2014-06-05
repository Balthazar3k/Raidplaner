<div class="col-lg-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>
                {if $i.order_process == 0 }
                    Neue Bestellungen
                {elseif $i.order_process == 1}
                    In Bearbeitung
                {elseif $i.order_process == 2}
                    Alte Bestellungen
                {/if}
            </b>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-right"></th>
                    <th class="text-right"></th>
                    <th class="text-center">Datum</th>
                    <th class="text-center">Best.Nr.</th>
                    <th class="text-center">Kdn.Nr.</th>
                    <th class="text-center">Zahlung</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Artikel Anzahl</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                {assign var='sum_price' value='0'}
                {foreach $orders as $i}
                    {$sum_price=$sum_price+$i.order_price}
                <tr>
                    <td class="text-center">
                        <a href="admin.php?shop-print-{$i.order_id}" target="_blank"><i class="fa fa-print fa-2x"></i> </a>
                    </td>
                    <td class="text-center">
                        <a href="admin.php?shop-details-{$i.order_id}">
                            <i class="fa fa-edit fa-2x"></i>
                        </a>
                    </td>
                    <td class="text-center">{$i.order_date|date_format:'%a %d.%m.%Y um %H:%M Uhr'}</td>
                    <td class="text-center">{$i.order_id}</td>
                    <td class="text-center">{$i.order_user} | {$i.order_name}</td>
                    <td class="text-center">{$payment_type[$i.order_payment].type}</td>
                    <td class="text-center">{$order_type[$i.order_type].type} | {$order_type[$i.order_type].city}</td>
                    <td class="text-center">{$i.num}</td>
                    <td class="text-center info"><b>{$i.order_price|price} {'currency'|config}</b></td>
                </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">Summe aller auftr&auml;ge</th>
                    <th class="text-right">Total:</th>
                    <th class="text-center success">{$sum_price|price} {'currency'|config}</th>
                </tr>
            </foot>
        </table>
    </div>
</div>