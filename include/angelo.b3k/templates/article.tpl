<div class="col-lg-12">
    <div class="input-group">
        <div class="input-group-btn">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Kategorien <span class="caret"></span></button>
            <ul class="dropdown-menu">
                
                {foreach $category as $i}
                <li><a href="?shop-article-{$i.category_id}">{$i.category_name}</a></li>
                {/foreach}
                {if $menu[2] != '' || $menu[2] != 0}
                <li class="divider"></li>
                <li><a href="?shop-article-0"><i class="fa fa-mail-reply-all"></i> Zur&uuml;ck</a></li>
                {/if}
            </ul>
        </div>
        <span class="input-group-addon"><i class="fa fa-search fa-lg"></i></span>
        <input type="text" class="form-control" placeholder="Suchen">
        <span class="input-group-addon">
            <i class="fa fa-shopping-cart fa-lg"></i> 3
        </span>
        <span class="input-group-addon">
            18,32 {'currency'|config}
        </span>
    </div>
</div>
<br /><br />
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
                         <!-- PLACEHOLDER -->
                     {/if}
                ">
            </div>
            <div class="col-lg-6">
                <h3 style="margin-top: 5px;">{$i.article_name}</h3>
                <p>
                    {$i.article_description|truncate:128}
                </p>
            </div>
            <div class="col-lg-4">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-info text-center"><b>{$i.article_grossprice|price}</b> {$i.unit_text}</li>
                    {if $i.article_discount != 0}
                        <li class="list-group-item list-group-item-success text-center">
                            <b>Rabatt: {$i.article_discount}% | <span style="text-decoration: line-through;">{$i.article_taxnetprice|price}</span></b>
                        </li>
                    {/if}
                    <li class="list-group-item">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <a class="btn btn-default" href="">&nbsp;<i class="fa fa-plus-circle"></i></a>
                                <a class="btn btn-default" href=""><i class="fa fa-minus-circle"></i>&nbsp;</a>
                            </div>
                            <input type="number" class="form-control text-center" value="1">
                            <span class="input-group-addon">{$i.unit_short}</span>
                            <div class="input-group-btn">
                                <a class="btn btn-success" href="">&nbsp;<i class="fa fa-shopping-cart"></i>&nbsp;</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <br style="clear: both;" />
        </li>
        {/foreach}
    </ul>
</div>
{debug}
