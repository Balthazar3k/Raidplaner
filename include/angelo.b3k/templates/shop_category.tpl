{foreach $category as $item}
<a href="index.php?shop-article-{$item.category_id}">
    <div class="col-lg-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <img class="img-thumbnail" src="{if file_exists($item.category_image)}{$item.category_image}{else}include/angelo.b3k/images/placeholder.png{/if}">
                <h4 class="text-center">{$item.category_name}</h4>
            </div>
        </div>
    </div>
</a>
{/foreach}