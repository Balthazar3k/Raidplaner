<div class="col-lg-6">
    <div class="col-lg-12">
        <a class="btn btn-default" href="?shop-category-{$smarty.session.shop.last_category}"><i class="fa fa-reply"></i> Zur&uuml;ck</a>
        <h4 class="pull-right">
            {if $category|is_array}
                <b>{$category|count}</b> Kategorien<br>
            {else}
                Keine Kategorie Vorhanden
            {/if}
        </h4>
    </div>
    <br style="clear: both;"><br>
    {foreach $category as $item}
        <div class="col-lg-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="admin.php?shop-category-{$item.category_id}">
                        {if $item.category_image != ""}<img class="img-thumbnail" src="{$item.category_image}">{/if}
                        <h4 class="text-center">{$item.category_name}</h4>
                        <hr>
                        <p>
                            {$item.category_description}
                        </p>
                    </a>
                    <div class="btn-group btn-group-justified">
                        <a class="btn btn-success btn-sm" href="admin.php?shop-category-{$edit.id}-edit-{$item.category_id}"><i class="fa fa-edit"></i> </a>
                        <a class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i> </a>
                        <a class="btn btn-danger btn-sm" href="admin.php?shop-category-{$edit.id}-delete-{$item.category_id}"><i class="fa fa-trash-o"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    {/foreach}
</div>

<div class="col-lg-3">
    <legend>Formular</legend>
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="admin.php?shop-category-{$edit.id}-save-{$edit.res.category_id}">
        <!--<div class="form-group">
            <div class="col-lg-12">
                <select
                    name="category_sub"
                    class="form-control"
                    >
                        {foreach from=$category_sub item=sub}
                            {foreach from=$sub item=i}
                                <option value="{$i.category_id}">{$i.category_name}</option>
                            {/foreach}
                        {/foreach}
                </select>
            </div> 
        </div>-->
        <div class="form-group">
            <div class="col-lg-12">
                <input 
                    name="category_name"
                    class="form-control"
                    placeholder="Name der neuen Kategorie"
                    value="{$edit.res.category_name}"
                >
            </div> 
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <textarea
                    name="category_description"
                    class="form-control"
                    placeholder="Beschreibung der Kategorie"
                    >{$edit.res.category_description}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input 
                    name="category_image"
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
</div>

<br style="clear: both;">