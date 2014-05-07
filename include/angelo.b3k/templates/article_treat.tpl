{debug}

<div class="col-lg-6">
    <ul class="list-group">
        <li class="list-group-item list-group-item-info">
            <div class="pull-left"><i class="fa fa-list fa-lg"></i> <b>Kategorie</b></div>
            <div class="pull-right">
                <a class="btn btn-success btn-xs" href="?shop-category-{$menu[2]}"><i class="fa fa-plus-circle"></i> </a>
            </div>
            <br style="clear: both;" />
        </li>
        <a class="list-group-item list-group-item-warning" href="?shop-article-{$smarty.session.shop.last_category}">
            <div class="col-lg-6"><i class="fa fa-reply"></i> Zur&uuml;ck</div>
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
    
    {if $menu[2] != '0'}
    <div class="panel panel-success">
        <div class="panel-heading">
            {if $article|is_array}
                <b>{$article|count}</b> Artikel<br>
            {else}
                Keine Artikel Vorhanden
            {/if}
        </div>
        <div class="panel-body">
            Hier sind alle Artikel der Kategorie <b>...</b>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Art.-Nr.</th>
                    <th>Name</th>
                    <th class="text-right">Menge</th>
                    <th>Einheit</th>
                    <th>Netto</th>
                    <th>Brutto</th>
                </tr>
            </thead>
            <tbody>
                {foreach $article as $i}
                <tr>
                    <td class="text-right">{$i.article_id}</td>
                    <td>{$i.article_name}</td>
                    <td class="text-right">{$i.article_number}</td>
                    <td>{$i.unit_unit}</td>
                    <td>{$i.article_netprice}</td>
                    <td>{$i.article_grossprice}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        
    </div>
    {/if}
</div>

<div class="col-lg-3">
    {if $menu[2] != '0' || $menu[2] != ''}
    <legend>Formular</legend>
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="admin.php?shop-article-{$menu[2]}-save-{$edit.res.article_id}">
        <div class="form-group">
            <div class="col-lg-12">
                <input 
                    name="article_name"
                    class="form-control"
                    placeholder="Name der neuen Kategorie"
                    value="{$edit.res.article_name}"
                >
            </div> 
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea
                    name="article_description"
                    class="form-control"
                    placeholder="Beschreibung der Kategorie"
                    >{$edit.res.article_description}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input 
                    name="article_image"
                    type="file" 
                    class="form-control"
                    placeholder="Image"
                >
            </div> 
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input class="form-control" type="submit" value="Speichern">
            </div> 
        </div>
    </form>
    {else}
        <div class="alert alert-warning"><i class="fa fa-info-circle fa-2x pull-left"></i> Bitte w&auml;len Sie eine Kategorie aus!<br>um ein Artikel zu erstellen.</div>
    {/if}
</div>

<br style="clear: both;">