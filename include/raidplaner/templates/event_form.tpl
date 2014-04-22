<form id="standart" name="form" method="post" action="{$data.event_path}">
    <table width="50%" border="0" cellspacing="1" cellpadding="2" class='border'>
        <tr class='Chead' align="center"> 
            <td>Status</td>
            <td>Leader</td>
            <td>Gruppe</td>
            <td>Dungeon</td>
            <td>Zeit</td>
        </tr>

        <tr class='Cmite'> 
            <td align="center">
                <select name="status" size="8">
                        {html_options options=$data.status}
                </select>
            </td>
            <td align="center">
                <select name="leader" size="8">
                        {html_options options=$data.leader}
                </select>
            </td>
            <td align="center">
                <select name="group" size="8">
                        {html_options options=$data.group}
                </select>
            </td>
            <td align="center">
                <select name="dungeon" size="8">
                        {html_options options=$data.dungeon}
                 </select>
            </td>
            <td align="center">
                <select name="time" size="8">
                {foreach $times as $i}
                        <option hide=".timeManual" value="{$i.id}">{$i.info}</option>
                {/foreach}
                        <option toggle=".timeManual" value="0">Zeit Manuel festlegen</option>
                 </select>
            </td>
        </tr>
        <tr class='Cnorm'> 
            <td colspan="6">
                Datum
            </td>
        </tr>
        <tr class='Cnorm hide timeManual' tooltip="Sie k&ouml;nnen mit dem Mausrad die Zeit einstellen."> 
            <td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">
                <input id="timeWheel" type="text" name="inv" value="18:00" size="5">
                <input id="timeWheel" type="text" name="pull" value="18:15" size="5">
                <input id="timeWheel" type="text" name="end" value="22:00" size="5">
                <input id="intWheel" type="text" name="lock" value="2" size="2">
            </td>
        </tr>
        <tr class='Cdark'> 
            <td id="removeCode" colspan="6" style="padding-top: 6px;" align="center">{$bbcode}</td>
        </tr>
        <tr class='Cnorm'>
            <td colspan="6"><textarea name="txt" cols="110" rows="8" id="txt"></textarea></td>
        </tr>
        <tr class='Cdark'> 
            <td colspan="6" align="center">
              <input type="submit" value="Speichern">
              <input type="reset" value="Zur&uuml;cksetzen" />
            </td>
        </tr>
    </table>
</form>