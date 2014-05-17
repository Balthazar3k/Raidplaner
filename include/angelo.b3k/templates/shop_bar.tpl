<div class="col-lg-12">
    <div class="input-group">
        <span class="input-group-btn">
            <a class="btn btn-default" href="?shop-article-0"><i class="fa fa-mail-reply-all"></i> Zur&uuml;ck</a>
        </span>
        <span class="input-group-addon"><i class="fa fa-search fa-lg search-icon"></i></span>
        <input type="text" data-search="index.php?shop-ajax-search" data-set="#article" class="form-control" name="search" placeholder="Suchen">
        <span class="input-group-btn">
            <a class="btn btn-success" href="?shop-shoppingcart">
                <i class="fa fa-shopping-cart fa-lg"></i>&nbsp; &nbsp;
                <b id="articleNum">{$cart.articleNum}</b>x Artikel |  
                <b id="priceSum">{$cart.priceSum}</b> {'currency'|config}
            </a>
        </span>
    </div>
</div>
<br /><br />