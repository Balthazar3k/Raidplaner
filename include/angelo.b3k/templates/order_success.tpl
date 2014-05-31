{if $order_status}
    <div class="alert alert-success">
        <i class="fa fa-check-circle fa-3x pull-left"></i>
        <div class="pull-left">
            Vielen dank f&uuml;r Ihren einkauf.
            
            Ihnen wird in k&uuml;rze eine Best&auml;tiguns Mail &uuml;ber Ihren einkauf geschickt.
        </div>
        <br class="clearboth" />
    </div>
{else}
    <div class="alert alert-danger">
        <i class="fa fa-check-circle fa-3x pull-left"></i>
        <div class="pull-left">
            Es ist ein Fehler aufgetreten, bitte versuchen Sie es Sp&auml;ter erneut.
        </div>
        <br class="clearboth" />
    </div>
{/if}