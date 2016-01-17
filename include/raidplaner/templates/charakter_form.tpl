<form name="form" method="post" action="{$data.path}">
    <table width="100%" border="0" cellspacing="1" cellpadding="5" class="border">
        <tr> 
            <th colspan="2" class="Chead"><i class="fa fa-user fa-lg"></i> {$data.title}</th>
        </tr>
        <tr class="Cnorm">
            <td nowrap="nowrap">Charakter Name:</td>
            <td><input type="text" name="charakter[name]" placeholder="Name" value="{$charakter.name}" /></td>
        </tr>

        <tr class="Cmite"> 
            <td nowrap="nowrap">Level</td>
            <td><input type="text" name="charakter[level]" placeholder="100" maxlength="3" value="{$charakter.level}" /></td>
        </tr>
        
        <tr class="Cnorm"> 
            <td nowrap="nowrap">Rasse</td>
            <td>
                <select id="klassen" name="charakter[rassen]">
                    <option>...</option>
                    {foreach from=$data.rassen key=k item=val}
                    <option value="{$val.id}" {if $val.id == $charakter.race_id}selected="selected"{/if}>{$val.rassen}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        
	<tr class="Cmite"> 
            <td nowrap="nowrap">Klasse</td>
            <td>
                <select id="klassen" name="charakter[klassen]">
                    <option>...</option>
                    {foreach from=$data.klassen key=k item=val}
                    <option value="{$val.id}" {if $val.id == $charakter.class_id}selected="selected"{/if}>{$val.klassen}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        
        <tr class="Cnorm">
            <td nowrap="nowrap">Spezialiesirung</td>
            <td id="spz">{$data.spz}</td>
        </tr>
    
        <tr class="Cmite">
            <td nowrap="nowrap">Main Skill:</td>
            <td> {$data.skillgruppe} </td>
        </tr>
    
        <tr class="Cnorm">
            <td nowrap="nowrap">Nachricht</td>
            <td>
                <textarea name="charakter[warum]" cols="50" id="warum" style="width:98%;">{$data.warum}</textarea>
            </td>
        </tr>
        
        <tr class="Cmite"> 
            <td nowrap="nowrap">Realm</td>
            <td><input name="charakter[realm]" type="text" id="realm" value="{$data.realm}" readonly></td>
        </tr>
        
        <tr> 
            <th colspan="2" class="Chead"><i class="fa fa-clock-o fa-lg"></i> Raidzeiten</th>
        </tr>
        
        {foreach from=$data.times key=k item=val}
        <tr class="Cnorm">
            <td align="right"><input name="times[{$val.id}]" type="checkbox" {if isset($charakter.times) && in_array($val.id, $charakter.times)}checked="checked"{/if}></td>
            <td nowrap>{$val.weekday}'s von {$val.start} bis {$val.end} kann ich Raiden</td>
        </tr>
        {/foreach}
        
        <tr class="Cdark"> 
            <td>
               
            </td>
            <td><input type="submit" value="Abschicken"></td>
        </tr>
    </table>
</form>
