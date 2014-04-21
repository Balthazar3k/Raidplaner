<div class="pull-left mr">
    <form action="admin.php?raidclasses-saveClass-{$data.edit.id}" method="post">
        <table width="100%" cellspacing="1" cellpadding="5" class="border">
            <thead class="Chead">
                <tr>
                    <th colspan="3">Klassen Liste</th>
                </tr>
                <tr>
                    <td align="right">ID</td>
                    <td>Name</td>
                    <td></td>
                </tr>
            </thead>

            <tbody class="Cnorm">
                {foreach from=$data.class item=class}
                    {assign var="maxID" value=$data.class|count}
                    <tr style="{$class.style}">
                        <td align="right">{$class.id}</td>
                        <td>
                            <img align="absmiddle" src="include/raidplaner/images/class/class_{$class.id}.jpg">
                            <b>{$class.klassen}</b>
                        </td>
                        <td class="btn-group btn-group-xs">
                            <a class="btn btn-success" href="admin.php?raidclasses-class-{$class.id}"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-danger {if $data.class[$maxID-1].id != $class.id}btn-default disabled{/if}" href="admin.php?raidclasses-removeClass-{$class.id}"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>

            <thead class="Chead">
                <tr>
                    <th colspan="3">Neue Klasse</th>
                </tr>
            </thead>

            <tbody class="Cmite">
                <tr>
                    <td colspan="3">
                        <input type="text" name="klassen" placeholder="Name" value="{$data.edit.klassen}"><br />
                        <textarea name="style" cols="40" rows="10" placeholder="CSS">{$data.edit.style}</textarea>
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