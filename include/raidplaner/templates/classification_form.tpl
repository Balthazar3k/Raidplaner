<form action="admin.php?raidclasses-saveClass-{$data.edit.id}" method="post">
    <table>
        <thead class="Chead">
            <tr>
                <th colspan="2">Klassen Liste</th>
            </tr>
            <tr>
                <td align="right">ID</td>
                <td>Name</td>
            </tr>
        </thead>

        <tbody class="Cnorm">
            {foreach from=$data.class item=class}
            <tr>
                <td align="right">{$class.id}</td>
                <td>
                    <img align="absmiddle" src="include/raidplaner/images/wowklein/{$class.klassen}.gif">
                    <a href="admin.php?raidclasses-class-{$class.id}"><b>{$class.klassen}</b></a>
                </td>
            </tr>
            {/foreach}
        </tbody>

        <thead class="Chead">
            <tr>
                <th colspan="2">Neue Klasse</th>
            </tr>
            <tr>
                <td></td>
                <td>Name</td>
            </tr>
        </thead>

        <tbody class="Cmite">
            <tr>
                <td></td>
                <td><input type="text" name="klassen" placeholder="Name" value="{$data.edit.klassen}"></td>
            </tr>
        </tbody>

        <tfoot class="Cdark">
            <tr>
                <td></td>
                <td><input type="submit" value="Speichern" /></td>
            </tr>
        </tfoot>
    </table>
</form>
{debug}