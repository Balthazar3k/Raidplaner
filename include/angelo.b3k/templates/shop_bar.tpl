<div class="col-lg-12">
    <div class="input-group">
        <span class="input-group-btn ">
            {if $menu[2] == 0 || empty($menu[2])}{else}<a class="btn btn-success" href="javascript:void(0);" onclick="history.back();"><i class="fa fa-mail-reply-all"></i>&nbsp;</a>{/if}
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                &nbsp;<span class="caret"></span>&nbsp;
            </button>
            <ul class="dropdown-menu pull-left">
                <li class="{if $menu[2] == 0 || empty($menu[2])}disabled{else}{/if}" ><a href="javascript:void(0);" onclick="history.back();"><i class="fa fa-mail-reply-all"></i> Zur&uuml;ck</a></li>
                <li><a href="?shop"><i class="fa fa-inbox"></i>&nbsp;Produkte</a></li>
            </ul>
        </span>
        <span class="input-group-addon"><i class="fa fa-search fa-lg search-icon"></i></span>
        <input type="text" data-search="index.php?shop-ajax-search" data-set="#article" class="form-control" name="search" placeholder="Suchen">
        <span class="input-group-btn">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-shopping-cart fa-lg"></i>&nbsp; &nbsp;
                <b id="articleNum">{$cart.articleNum}</b>x Artikel |  
                <b id="priceSum">{$cart.priceSum}</b> {'currency'|config}
                &nbsp; &nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right">
              <li><a href="?shop-shoppingcart">Zum Warenkorb</a></li>
              <li class="divider"></li>
              <li><a href="index.php?shop-ajax-clearShoppingCart"><i class="fa fa-trash"></i> Warenkorb Leeren</a></li>
            </ul>
        </span>
    </div>
</div>
<br /><br />