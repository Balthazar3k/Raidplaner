<br />
<div id="article">
    <div class="col-lg-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left"><b>{$a.article_name}</b></div>
                <div class="pull-right">{$a.category_name}</div>
                <br class="clear" />
            </div>
            <table class="table table-striped">
                <tbody>
                    {if $a.article_discount != 0}
                    <tr>
                        <td class="text-center" colspan="2">
                            Rabatt: {$a.article_discount}% | <span style="text-decoration: line-through;">{$a.article_taxnetprice|price}</span>
                            <i class="fa fa-arrow-circle-o-right fa-lg"></i> <b class="text-success">{$a.article_grossprice|price} {'currency'|config}</b>
                        </td>
                    </tr>
                    {else}
                    <tr>
                        <td class="text-center" colspan="2">
                            <b>{$a.article_grossprice|price} {'currency'|config}</b> |  
                            {$a.article_amount} {$a.unit_unit}
                        </td>
                    </tr>
                    {/if}
                    <tr>
                        <td class="text-center small" colspan="2">
                            Alle Preisangaben inkl. MwSt.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center small" colspan="2">
                            {$a.article_description}
                        </td>
                    </tr>
                    <tr>
                        <td class="small text-left">Seid dem {$a.article_append|date_format:'%d.%m.%Y'} im Angebot</td>
                        <td class="small text-right">Produkt wurde am {$a.article_update|date_format:'%d.%m.%Y'} ge&auml;ndert</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-5">
        <img class="img-thumbnail" src="{if file_exists($a.article_image)}{$a.article_image}{else}include/angelo.b3k/images/placeholder.png{/if}" />
        <br /><br />
        <form id="standart" action="index.php?shop-ajax-shoppingCart">
            <input type="hidden" name="article_id" value="{$a.article_id}" />
            <input type="hidden" name="article_amount" value="{$a.article_amount}" />
            <input type="hidden" name="article_grossprice" value="{$a.article_grossprice|price}" /> <!-- {(round(20*$a.article_grossprice)/20)} -->
            <div class="input-group">
                <div class="input-group-btn">
                    <a data-amount="+{$a.article_amount}" class="btn btn-default" href="#">&nbsp;<i class="fa fa-plus-circle"></i></a>
                    <a data-amount="-{$a.article_amount}" class="btn btn-default" href="#"><i class="fa fa-minus-circle"></i>&nbsp;</a>
                </div>
                <input type="text" class="form-control text-center" style="padding: 0px;" name="user_amount" value="{$a.article_amount}">
                <span class="input-group-addon">{$a.unit_short}</span>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-success" href="">&nbsp;<i class="fa fa-shopping-cart"></i>&nbsp;</button>
                </div>
            </div>
        </form>
    </div>
</div>
