<div id="article">
    {if !empty($article) }
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
                        <li class="list-group-item list-group-item-info text-center"><b>{$i.article_grossprice|price}</b> - {$i.article_amount} {$i.unit_unit} | {$i.article_tax}% MwSt.</li>
                        {if $i.article_discount != 0}
                            <li class="list-group-item list-group-item-success text-center">
                                <b>Rabatt: {$i.article_discount}% | <span style="text-decoration: line-through;">{$i.article_taxnetprice|price}</span></b>
                            </li>
                        {/if}
                        <li class="list-group-item">
                            <form id="standart" action="index.php?shop-ajax-shoppingCart">
                                <input type="hidden" name="article_id" value="{$i.article_id}" />
                                <input type="hidden" name="article_amount" value="{$i.article_amount}" />
                                <input type="hidden" name="article_grossprice" value="{$i.article_grossprice|price}" /> <!-- {(round(20*$i.article_grossprice)/20)} -->
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <a data-amount="+{$i.article_amount}" class="btn btn-default" href="#">&nbsp;<i class="fa fa-plus-circle"></i></a>
                                        <a data-amount="-{$i.article_amount}" class="btn btn-default" href="#"><i class="fa fa-minus-circle"></i>&nbsp;</a>
                                    </div>
                                    <input type="text" class="form-control text-center" name="user_amount" value="{$i.article_amount}">
                                    <span class="input-group-addon">{$i.unit_short}</span>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-success" href="">&nbsp;<i class="fa fa-shopping-cart"></i>&nbsp;</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
                <br style="clear: both;" />
            </li>
            {/foreach}
        </ul>
    </div>
    {else}
        <br style="clear: both;" />
        <div class="alert alert-info">
            <i class="fa fa-info-circle fa-2x pull-left"></i> 
            <p class="pull-left">
                In dieser Kategorie sind keine Artikel vorhanden,<br>
                bitte w&auml;hlen Sie eine andere Kategorie aus.
            </p>
            <br style="clear: both;" />
        </div>
    {/if}
</div>
