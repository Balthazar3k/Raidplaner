<form action="admin.php?raidzeiten-save-{$data.id}" method="POST">    
    <table class="border" cellspacing="1" cellpadding="3" width="40%">
        <thead>
            <tr class="Chead">
                <th colspan="6">Raid Zeiten</th>
            </tr>

            <tr class="Cdark">
                <th>Anz</th>
                <th>Weekday</th>
                <th>Start</th>
                <th>Invite</th>
                <th>Ende</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            {foreach $data.times as $item}
            <tr class="Cnorm" align="center">
                <th width="1%">{$item.num}</th>
                <td>{$item.weekday}</td>
                <td>{$item.start}</td>
                <td>{$item.inv}</td>
                <td>{$item.end}</td>
                <td class="btn-group btn-group-xs" nowrap>
                    <a class="btn btn-default" href="admin.php?raidzeiten-edit-{$item.id}"><i class="fa fa-edit"></i> Bearbeiten</a>
                    <a class="btn btn-danger" href="admin.php?raidzeiten-delete-{$item.id}"><i class="fa fa-trash-o"></i> L&ouml;schen</a>
                </td>
            </tr>
            {/foreach}
            
            <tr class="Chead">
                <th colspan="6">Neue Raidzeit</th>
            </tr>

            <tr class="Cmite" align="center">
                <td colspan="2">
                    <select name="weekday" style="width: 100px;">
                        {foreach $data.weekdays as $item}
                            <option value="{$item}" {if $data.weekday == $item}selected="selected"{/if}>{$item}</option>
                        {/foreach}
                    </select>                
                </td>
                <td><input name="start" type="time" value="{$data.start}"></td>
                <td><input name="inv" type="time"value="{$data.inv}"></td>
                <td><input name="end" type="time"value="{$data.end}"></td>
                <td><input type="submit" value="Speichern"></td>
            </tr>

            <tr class="Chead">
                <th colspan="6">

                </th>
            </tr>
        </tbody>
    </table>
</form>