<div class="pull-left">
    <form action="admin.php?raidclasses-saveClassification-{$data.edit.id}" method="post">
        <table width="100%" cellspacing="1" cellpadding="5" class="border">
            <thead class="Chead">
                <tr>
                    <th colspan="3">Spezialiesierung</th>
                </tr>
                <tr>
                    <td> </td>
                    <td>Name</td>
                    <td><i class="fa fa-search-plus"></i> </td>
                </tr>
            </thead>

            <tbody class="Cnorm">
                {assign "id" "0"}
                {assign "num" "1"}
                {foreach from=$data.classification item=class}
                    
                    
                    {if $id != $class.class_id}
                        <tr style="{$class.style}">
                            <td colspan="3">
                                <img align="absmiddle" src="include/raidplaner/images/class/class_{$class.class_id}.jpg">
                                <b>{$class.class_name}</b>
                            </td>  
                        </tr>
                        {assign var="id" $class.class_id}
                        {assign "num" "1"}
                    {/if}
                    
                    <tr>
                        <td style="{$class.style}">{$num}.</td>
                        <td>
                            <div class="pull-left"><b>{$class.name}</b></div>
                            <div class="pull-right btn-group btn-group-xs">
                                <a class="btn btn-success" href="admin.php?raidclasses-classification-{$class.id}"><i class="fa fa-edit"></i></a>
                                <a class="btn btn-warning" href="admin.php?raidclasses-removeClassification-{$class.id}"><i class="fa fa-trash-o"></i></a>                      
                            </div>
                        </td>
                        <td class="btn-group btn-group-xs">
                            <a class="btn btn-success" href="admin.php?raidclasses-search-p{$class.id}"><i class="fa fa-plus-circle"></i></a>
                            <a class="btn btn-default {if $class.search == 0}disabled{/if}" href="admin.php?raidclasses-search-o{$class.id}"><b>{$class.search}</b></a>
                            <a class="btn btn-danger {if $class.search == 0}disabled{/if}"  href="admin.php?raidclasses-search-m{$class.id}"><i class="fa fa-minus-circle"></i></a>
                        </td>    
                    </tr>
                        
                    {assign "num" $num+1}
                {/foreach}
            </tbody>

            <thead class="Chead">
                <tr>
                    <th colspan="3">Neue Klasse</th>
                </tr>
            </thead>

            <tbody class="Cmite">
                <tr>
                    <td></td>
                    <td>
                        <select name="class_id">
                            <option></option>
                            {foreach $data.class as $item}
                            <option value="{$item.id}" {if $item.id == $data.edit.class_id}selected="selected"{/if}>{$item.klassen}</option>
                            {/foreach}
                        </select>
                        <input type="text" name="name" placeholder="Name" value="{$data.edit.name}">
                    </td>
                    <td>
                        <input type="text" name="search" placeholder="0" value="{$data.edit.search}" maxlength="2" size="2" class="text-center">
                    </td>
                </tr>
            </tbody>

            <tfoot class="Cdark">
                <tr>
                    <td colspan="3"><input type="submit" value="Speichern" /></td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
                    
<br style="clear: both;" />