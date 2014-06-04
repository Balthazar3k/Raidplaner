<div class="col-lg-8">
    <div class="panel panel-{if $data.order.order_process==0}warning{elseif $data.order.order_process==1}info{elseif $data.order.order_process==2}success{/if}">
        <div class="panel-heading">
            <b>Bestellung</b>
        </div>
        <div class="panel-body">
            <div class="col-lg-3">
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
            <div class="col-lg-5">
                <b>Optionen</b>
                <hr style="margin-bottom: 2px; margin-top: 4px;">
                <div class="btn-group btn-group-justified">
                    <a href="admin.php?shop-print-{$menu[2]}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> Druckansicht</a>
                </div><br />
                
                Bestellstatus &auml;ndern!
                <div class="btn-group btn-group-justified">
                    {if $data.order.order_process!=0}<a href="admin.php?shop-details-{$menu[2]}-p0" class="btn btn-warning">Neue Bestellung</a>{/if}
                    {if $data.order.order_process!=1}<a href="admin.php?shop-details-{$menu[2]}-p1" class="btn btn-info">Bearbeitung</a>{/if}
                    {if $data.order.order_process!=2}<a href="admin.php?shop-details-{$menu[2]}-c2" class="btn btn-success">Erledigt</a>{/if}
                    {if $data.order.order_process!=3}<a href="admin.php?shop-details-{$menu[2]}-p3" class="btn btn-danger">Storno</a>{/if}
                </div>
                {if $data.order.order_process==3}<p><br /><a href="admin.php?shop-details-{$menu[2]}-d{$data.order.order_id}" class="btn btn-danger">L&oumlschen</a></p>{/if}
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-right">Art.Nr.</th>
                    <th>Art.Name</th>
                    <th class="text-center">Einheit</th>
                    <th class="text-center">Einheitspreis</th>
                    <th class="text-center">Menge</th>
                    <th class="text-center">MwSt.</th>
                    <th class="text-center">Gesamt Preis</th>
                </tr>
            </thead>
            <tbody>
                {assign var='sum_price' value='0'}
                {foreach $data.article as  $i}
                    {$sum_price=$sum_price+$i.order.user_price}
                    <tr>
                        <td class="text-center">
                            {if $i.order.order_process>=1}
                                <a class="btn btn-success btn-xs" href="admin.php?shop-details-{$menu[2]}-a0-{$i.article.article_id}"><i class="fa fa-check-square fa-2x"></i></a>
                            {else}
                                <a class="btn btn-info btn-xs" href="admin.php?shop-details-{$menu[2]}-a1-{$i.article.article_id}"><i class="fa fa-square fa-2x"></i></a>
                            {/if}
                        </td>
                        <td class="text-right">{$i.article.article_id}</td>
                        <td>
                            <b>{$i.article.article_name}</b>
                            <div class="small text-justify">{$i.article.article_description|truncate:128:'...':true}</div>
                        </td>                       
                        <td class="text-center">{$i.order.user_amount} {$i.article.unit_short}</td>
                        <td class="text-center">{$i.article.article_grossprice|price} {'currency'|config}</td>
                        <td class="text-center">{$i.article.article_amount} {$i.article.unit_short}</td>
                        <td class="text-center">{$i.article.article_tax}%</td>
                        <td class="text-center"><b>{$i.order.user_price|price} {'currency'|config}</b></td>
                    </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6"></td>
                    <td class="text-right">Total:</td>
                    <td class="text-center"><b>{$sum_price|price}  {'currency'|config}</b></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>