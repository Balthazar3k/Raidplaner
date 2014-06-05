{if !empty($category)}
<div class="col-lg-3">
    <ul class="list-group">
        <li class="list-group-item list-group-item-info"><b>Kategorien</b></li>
    {foreach $category as $item}
        <a class="list-group-item" href="index.php?shop-article-{$item.category_id}">
            {$item.category_name}
        </a>
    {/foreach}
    </ul>
</div><!--<img class="img-thumbnail" src="{if file_exists($item.category_image)}{$item.category_image}{else}include/angelo.b3k/images/placeholder.png{/if}">-->
{/if}
