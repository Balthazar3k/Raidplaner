<div id="article">
    {if !empty($article) } {debug}
    <a class="btn btn-success" href="index.php?shop-article-{$menu[2]}"><i class="fa fa-reply-all"></i> Weiter Einkaufen gehen</a><br /><br />
    <div class="col-lg-12">
        <ul class="list-group">
            {foreach $article as $i}
            <li class="list-group-item">
                <div class="col-lg-2">
                    <img class="img-thumbnail" src="
                         {if file_exists($i.article_image)}
                             {$i.article_image}
                         {elseif file_exists($i.category_image)}
                             {$i.category_image}
                         {else}
                             include/angelo.b3k/images/placeholder.png
                         {/if}
                    ">
                </div>
                <div class="col-lg-6">
                    <h3 style="margin-top: 5px;">{$i.article_name} <b class="small">- {$i.category_name}</b></h3>
                    <p>  
                        <hr>
                        {$i.article_description|truncate:128}
                    </p>
                </div>
                <div class="col-lg-4">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-info text-center"><b>{$i.article_grossprice|price}</b> - {$i.article_amount} {$i.unit_unit}</li>
                        {if $i.article_discount != 0}
                            <li class="list-group-item list-group-item-success text-center">
                                <b>Rabatt: {$i.article_discount}% | <span style="text-decoration: line-through;">{$i.article_taxnetprice|price}</span></b>
                            </li>
                        {/if}
                        <li class="list-group-item">
                            <form id="calcPrice">
                                <input type="hidden" name="article_id" value="{$i.article_id}" />
                                <input type="hidden" name="article_amount" value="{$i.article_amount}" />
                                <input type="hidden" name="article_grossprice" value="{$i.article_grossprice}" />
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <a href="index.php?shop-shoppingcart-recalc&data=p&{$i|http_build_query}" class="btn btn-default">&nbsp;<i class="fa fa-plus-circle"></i></a>
                                        <a href="index.php?shop-shoppingcart-recalc&data=m&{$i|http_build_query}" class="btn btn-default"><i class="fa fa-minus-circle"></i>&nbsp;</a>
                                    </div>
                                    <input type="text" class="form-control text-right" name="user_amount" value="{$smarty.session.shop.cart[$i.article_id].user_amount}">
                                    <span class="input-group-addon">{$i.unit_short}</span>
                                    <span class="input-group-addon">{(($smarty.session.shop.cart[$i.article_id].user_amount / $i.article_amount) * $i.article_grossprice|round:2)|price}</span>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
                <br style="clear: both;" />
            </li>
            {/foreach}
            <li class="list-group-item">
                <div class="col-lg-8"></div>
                <div class="col-lg-4">
                    <ul class="list-group">
                        <li class="list-group-item text-center">Gesamt: </li>
                    </ul>
                </div>
                <br style="clear: both;" />
            </li>
        </ul>
    </div>
    {else}
        <br style="clear: both;" />
        <div class="alert alert-info">
            <i class="fa fa-info-circle fa-2x pull-left"></i> 
            <p class="pull-left">
                Es sind keine Artikel im Warenkorb<br />
                <a class="btn btn-default" href="index.php?shop-article-{$menu[2]}"><i class="fa fa-reply-all"></i> Einkaufen gehen</a>
            </p>
            <br style="clear: both;" />
        </div>
    {/if}
</div>
