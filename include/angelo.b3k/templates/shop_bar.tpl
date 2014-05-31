<div class="col-lg-12">
    <div class="input-group">
        <span class="input-group-btn ">
            <a class="btn btn-success" href="?shop-article-0"><i class="fa fa-mail-reply-all"></i> Zur&uuml;ck</a>
            <a class="btn btn-info" href="?shop"><i class="fa fa-inbox"></i>&nbsp;Produkte</a>
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