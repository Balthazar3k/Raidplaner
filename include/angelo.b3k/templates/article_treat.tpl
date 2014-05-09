<div class="col-lg-8">
    <ul class="list-group">
        <li class="list-group-item list-group-item-info">
            <div class="pull-left"><i class="fa fa-list fa-lg"></i> <b>Kategorie</b></div>
            <div class="pull-right">
                <a class="btn btn-success btn-xs" href="?shop-category-{$menu[2]}"><i class="fa fa-plus-circle"></i> </a>
            </div>
            <br style="clear: both;" />
        </li>
        <a class="list-group-item list-group-item-warning" href="?shop-article-{$smarty.session.shop.last_category}">
            <div class="col-lg-6"><i class="fa fa-mail-reply-all"></i> <b>Zur&uuml;ck</b></div>
            <div class="col-lg-6"></div>
            <br style="clear:both" />
        </a>
        {foreach $category as $cat}
        <a class="list-group-item" href="?shop-article-{$cat.category_id}">
            <div class="col-lg-6">{$cat.category_name}</div>
            <div class="col-lg-6"></div>
            <br style="clear:both" />
        </a>
        {/foreach}
    </ul>
    
    {if !empty($menu) && $menu[2] != '0'}
    <div class="panel panel-success">
        <div class="panel-heading">
            {if $article|is_array}
                <b>{$article|count}</b> Artikel<br>
            {else}
                Keine Artikel Vorhanden
            {/if}
        </div>
        <div class="panel-body">
            Hier sind alle Artikel der Kategorie <b>{$article[0].catergory_name}</b>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-right">Art.-Nr.</th>
                    <th>Name</th>
                    <th class="text-center">Bild</th>
                    <th class="text-center">Menge</th>
                    <th class="text-center">Verk.Menge</th>
                    <th class="text-center">Netto</th>
                    <th class="text-center">Brutto</th>
                    <th class="text-center">MwSt.</th>
                    <th class="text-center">Rabatt</th>
                    <th class="text-right">Optionen</th>
                </tr>
            </thead>
            <tbody>
                {foreach $article as $i}
                <tr {if $menu[3] == 'edit' && $menu[4] == $i.article_id}class="info"{/if}>
                    <td class="text-right">{$i.article_id}</td>
                    <td><b>{$i.article_name}</b></td>
                    <td class="text-center">{if !empty($i.article_image)}<i class="fa fa-check-square"></i>{else}{/if}</td>
                    <td class="text-center">{$i.article_number} {$i.unit_short}</td>
                    <td class="text-center">{$i.article_amount} {$i.unit_short}</td>
                    <td class="text-center">{$i.article_netprice|price} {$i|shop}</td>
                    <td class="text-center success"><b>{$i.article_netprice|tax:$i.article_tax}</b></td>
                    <td class="text-center">{if !empty($i.article_tax)}{$i.article_tax}%{/if}</td>
                    {if !empty($i.article_discount)}<td class="text-center danger">{$i.article_discount}% | {($i.article_netprice|discount:$i.article_discount)|tax:$i.article_tax}</td>{else}<td></td>{/if}
                    <td>
                        <div  class="btn-group pull-right">
                            <a class="btn btn-success btn-xs" href="?shop-article-{$menu[2]}-edit-{$i.article_id}"><i class="fa fa-edit"></i> </a>
                            <a class="btn btn-danger btn-xs" href="?shop-article-{$menu[2]}-delete-{$i.article_id}"><i class="fa fa-trash-o"></i> </a>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        
    </div>
    {/if}
</div>

<div class="col-lg-4">
    {if !empty($menu) && $menu[2] != '0'}
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="admin.php?shop-article-{$menu[2]}-save-{$edit.res.article_id}">
        <div class="col-lg-12 well-sm">
            <legend>Produkt</legend>

            <div class="input-group">
                <input 
                    name="article_name"
                    class="form-control"
                    placeholder="Name des Artikel"
                    value="{$edit.res.article_name}"
                >
                <span class="input-group-addon"><i class="fa fa-info-circle"></i> </span>
            </div><br />
                
            <div class="input-group">
            <textarea
                name="article_description"
                class="form-control"
                placeholder="Artikel beschreibung"
                >{$edit.res.article_description}</textarea>
                <span class="input-group-addon"><i class="fa fa-info-circle"></i> </span>
            </div><br />
                
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-upload"></i> <i class="fa fa-picture-o"></i> </span>
                <input 
                    name="article_image"
                    type="file" 
                    class="form-control"
                    placeholder="Image"
                >
                <span class="input-group-addon"><i class="fa fa-info-circle"></i> </span>
            </div>

        </div>
        <div class="col-lg-6 well-sm">
            <legend>Mengen</legend>
            
            <div class="input-group">
                <span class="input-group-addon" tooltip="Mengeneinheit"><i class="fa fa-list-alt"></i> </span>
                <select name="article_unit" class="form-control">
                    {foreach $units as $u}
                        <option value="{$u.unit_id}"{if $u.unit_id==$edit.res.article_unit}selected="selected"{/if}>{$u.unit_unit}</option>
                    {/foreach}
                </select>
            </div><br />
            
            
            <div class="input-group">
                <span class="input-group-addon" tooltip="Mengeneinheit"><i class="fa fa-tag"></i> </span>
                <input 
                    type="number"
                    name="article_number"
                    class="form-control"
                    placeholder="Menge Vorr&auml;tig 2000kg"
                    value="{$edit.res.article_number}"
                >
            </div><br />
            
            <div class="input-group">
                <span class="input-group-addon" tooltip="Mengeneinheit"><i class="fa fa-tags"></i> </span>
                <input 
                    type="number"
                    name="article_amount"
                    class="form-control"
                    placeholder="Verkaufs Menge a 5kg Kiste"
                    value="{$edit.res.article_amount}"
                >
            </div>
        </div>
        <div class=" col-lg-6 well-sm">
            <legend>Preis</legend>
            
            <div class="input-group">
                <input 
                    name="article_discount"
                    class="form-control"
                    placeholder="Rabatt"
                    value="{$edit.res.article_discount}"
                >
                    <span class="input-group-addon" tooltip="Mehrwertsteuer"><b>%</b></span>
            </div>
            
            <br />
            <div class="input-group">
                <input 
                    type="number"
                    name="article_tax"
                    class="form-control"
                    placeholder="Mehrwertsteuer"
                    value="{$edit.res.article_tax}"
                >
                <span class="input-group-addon" tooltip="Mehrwertsteuer"><b>%</b></span>
            </div>
            
            <br />
            <div class="input-group">
                <input 
                    name="article_netprice"
                    class="form-control"
                    placeholder="Nettopreis"
                    value="{$edit.res.article_netprice}"
                >
                <span class="input-group-addon" tooltip="Nettopreis"><b>{'currency'|config}</b></span>
            </div>
        </div>
            
        <div class="col-lg-12 well-sm">
            <input class="form-control btn-success" type="submit" value="Speichern">
        </div>
    </form>
    {else}
        <div class="alert alert-warning"><i class="fa fa-info-circle fa-2x pull-left"></i> Bitte w&auml;hlen Sie eine Kategorie aus!<br>um ein Artikel zu erstellen.</div>
    {/if}
</div>

<br style="clear: both;">