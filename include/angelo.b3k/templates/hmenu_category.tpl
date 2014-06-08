<div class="btn-group">
    <a class="btn btn-default" href="{$url}-0">{$name}</a>
    {foreach $hmenu as $i}
        {if !empty($i.category_name)}
        <a class="btn btn-info" href="{$url}-{$i.category_id}"><i class="fa fa-dot-circle-o"></i> <b>{$i.category_name}</b></a>
        {/if}
    {/foreach} 
</div>