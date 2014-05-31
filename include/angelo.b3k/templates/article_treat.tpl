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
                <tr {if $menu[3] == 'edit' && $menu[4] == $i.article_id}class="info"{/if} data-view="{$i.article_name}">
                    <td class="text-right">{$i.article_id}</td>
                    <td><b>{$i.article_name}</b></td>
                    <td class="text-center">{if !empty($i.article_image)}<i class="fa fa-check-square"></i>{else}{/if}</td>
                    <td class="text-center">{$i.article_number} {$i.unit_short}</td>
                    <td class="text-center">{$i.article_amount} {$i.unit_short}</td>
                    <td class="text-center">{$i.article_netprice|price}</td>
                    <td class="text-center success"><b>{$i.article_grossprice|price}</b></td>
                    <td class="text-center">{if !empty($i.article_tax)}{$i.article_tax}% | +{$i.article_taxprice|price}{/if}</td>
                    {if !empty($i.article_discount)}<td class="text-center danger">{$i.article_discount}% | -{$i.article_discountprice|price}</td>{else}<td></td>{/if}
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

<div id="articleForm" class="col-lg-4">
    {if !empty($menu) && $menu[2] != '0'}
    <div class="panel panel-default">
        <div class="panel-heading">Artikel erstellen/bearbeiten</div>
        <div class="panel-body">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="admin.php?shop-article-{$menu[2]}-save-{$edit.res.article_id}">
            <div class="col-lg-12 well-sm">
                <div class="input-group">
                    <input 
                        name="article_name"
                        class="form-control"
                        placeholder="Name des Artikel"
                        value="{$edit.res.article_name}"
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Name"
                        data-content="Erstellen Sie ein Artikelname f&uuml;r die Ware"
                    >
                    <span class="input-group-addon"><i class="fa fa-info-circle"></i> </span>
                </div><br />

                <div class="input-group">
                <textarea
                    name="article_description"
                    class="form-control"
                    placeholder="Artikel beschreibung"
                    data-container="body" 
                    data-toggle="popover"
                    data-trigger="hover"
                    data-placement="top" 
                    data-title="Beschreibung"
                    data-content="Hier k&ouml;nnen Sie eine artikel Beschreibung erstellen"
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
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Bild Hochladen"
                        data-content="Hier k&ouml;nnen Sie ein Bild f&uuml;r Ihren Artikel Hochladen."
                    >
                    <span class="input-group-addon"><i class="fa fa-info-circle"></i> </span>
                </div>

            </div>
            <div class="col-lg-6 well-sm">
                <legend>Mengen</legend>

                <div class="input-group">
                    <span class="input-group-addon" tooltip="Mengeneinheit"><i class="fa fa-list-alt"></i> </span>
                    <select name="article_unit" class="form-control"
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Einheit"
                        data-content="Welche einheit hat der Artikel"
                    >
                        {foreach $units as $u}
                            <option value="{$u.unit_id}"{if $u.unit_id==$edit.res.article_unit}selected="selected"{/if}>{$u.unit_unit}</option>
                        {/foreach}
                    </select>
                </div><br />


                <div class="input-group">
                    <span class="input-group-addon" tooltip="Gesamte Menge"><i class="fa fa-tag"></i> </span>
                    <input 
                        name="article_number"
                        class="form-control"
                        placeholder="Menge Vorr&auml;tig 2000kg"
                        value="{$edit.res.article_number}"
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Gesamte Menge"
                        data-content="Wie viel von den G&uuml;ter sind vorr&auml;tig!"
                    >
                </div><br />

                <div class="input-group">
                    <span class="input-group-addon" tooltip="Mengeneinheit"><i class="fa fa-tags"></i> </span>
                    <input 
                        name="article_amount"
                        class="form-control"
                        placeholder="Verkaufs Menge a 5kg Kiste"
                        value="{$edit.res.article_amount}"
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Verkaufsmenge"
                        data-content="In welchen Mengen sollen die G&uuml;ter verkauft werden"
                    >
                </div>
            </div>
            <div class=" col-lg-6 well-sm">
                <legend>Preis</legend>

                <div class="input-group">
                    <input 
                        type="number"
                        name="article_discount"
                        class="form-control"
                        placeholder="Rabatt"
                        value="{$edit.res.article_discount}"
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Rabatt"
                        data-content="Rabatt als 1-3 stellige Zahl angeben, als Prozent, diese werden sp&auml;ter automatisch vom Nettopreis abgerechnet. Es ist nicht n&ouml;tig ein Prozentzeichen anzuh&auml;ngen."
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
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Mehrwertsteuer"
                        data-content="Artikel haben verschiedene Steuer s&auml;tze, k&ouml;nnen somit hier Deffiniert werden. Als 1-2 stellige Zahl ohne Prozentzeichen"
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
                        data-container="body" 
                        data-toggle="popover"
                        data-trigger="hover"
                        data-placement="top" 
                        data-title="Nettopreis"
                        data-content="Hier k&ouml;nnen Sie den Nettopreis eingeben (z.B. 13.50 oder einfach nur 1 f&uuml;r ein 1,00 {'currency'|config} Nettopreis). Die W&auml;hrung wird automatisch gesetzt."
                    >
                    <span class="input-group-addon" tooltip="Nettopreis"><b>{'currency'|config}</b></span>
                </div>
            </div>

            <div class="col-lg-12">
                <input class="form-control btn btn-success" type="submit" value="Speichern">
            </div>
        </form>
        {else}
            <div class="alert alert-warning"><i class="fa fa-info-circle fa-2x pull-left"></i> Bitte w&auml;hlen Sie eine Kategorie aus!<br>um ein Artikel zu erstellen.</div>
        {/if}
    </div>
</div>

<br style="clear: both;">