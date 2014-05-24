<div class="alert alert-info">
    <i class="fa fa-info-circle fa-2x pull-left"></i> 
    <div class="pull-left">
        W&auml;hlen Sie bitte aus ob die Bestellung, als Selbstabholer oder Lieferung bearbeitet werden soll.<br>
        Bedenken Sie bitte das bei einer Hauslieferung, zus&auml;tzliche Kosten anfallen.
    </div>
    <br style="clear: both;" />
</div>

{foreach $type as $k => $i}
    <div class="col-lg-4">
        <div class="thumbnail">
            <h4 class="text-center">{$i.title}</h4>
            <hr>
            <div class="btn-group btn-group-justified">	
                <a class="btn btn-success btn-justified" href="index.php?shop-order-type-{$k}"><b>{$i.type} {if !empty($i.city)} - {$i.city}{/if}</b></a>
            </div>
            <hr>
            <p class="text-center well well-sm small" style="margin-bottom: 0px!important">{$i.message}</p>
        </div>
    </div>
{/foreach}