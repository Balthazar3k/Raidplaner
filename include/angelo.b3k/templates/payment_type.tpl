<div class="alert alert-info">
    <i class="fa fa-info-circle fa-2x pull-left"></i> 
    <div class="pull-left">
        Wie m&ouml;chten Sie Bezahlen?<br>
    </div>
    <br style="clear: both;" />
</div>

{foreach $type as $k => $i}
    {if $smarty.session.authright <= $i.permission}
    <div class="col-lg-4">
        <div class="thumbnail">
            <h4 class="text-center">{$i.title}</h4>
            <hr>
            <div class="btn-group btn-group-justified">	
                <a class="btn btn-success btn-justified" href="index.php?shop-order-payment-{$k}"><b>{$i.type}</b></a>
            </div>
            <hr>
            <p class="text-center well well-sm small" style="margin-bottom: 0px!important">{$i.message}</p>
        </div>
    </div>
    {/if}
{/foreach}