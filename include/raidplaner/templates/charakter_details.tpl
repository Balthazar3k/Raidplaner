<h1 align="center">{$charakter.name} {$charakter.level}</h1>

<table width="100%" cellpadding="5" cellspacing="1" class="border">
    <tr class="Chead">
        <th colspan="2">Charakter Details</th>
    </tr>
    
    <tr class="Cnorm">
        <td width="30%" align="right">Gildenrang:</td>
        <td width="70%">{$charakter.rank_name}</td>
    </tr>
    
    <tr class="Cnorm">
        <td align="right">Klasse:</td>
        <td>{$charakter.class_name}</td>
    </tr>
    
    <tr class="Cnorm">
        <td align="right">Rasse:</td>
        <td>{$charakter.race_name}</td>
    </tr>
    
    <tr class="Cnorm">
        <td align="right">1. Skillung:</td>
        <td>{$charakter.s1}</td>
    </tr>
    
    <tr class="Cnorm">
        <td align="right">2. Skillung:</td>
        <td>{$charakter.s2}</td>
    </tr>
    
    <tr class="Cnorm">
        <td align="right">Registriert:</td>
        <td>{$charakter.regist|date_format:'%a %d.%m.%Y %H:%m'}</td>
    </tr>
    
    <tr class="Chead">
        <th colspan="2">Raid Zeiten</th>
    </tr>
    
    {foreach from=$times key=k item=val}
    <tr  class="Cdark">
        <td align="right" style="color: #FFF; {if in_array($val.id, $charakter.times)}background-color: darkgreen;{else}background-color: darkred;{/if}">
            <input type="checkbox" disabled="disabled" {if in_array($val.id, $charakter.times)}checked="checked"{/if} />
        </td>
        
        <td>{$val.weekday} um {$val.start} bis {$val.end}</td>  
    </tr>
    {/foreach}
    
    <tr class="Chead">
        <th colspan="2">Weitere Charaktere</th>
    </tr>
    
    {foreach from=$ownCharakters key=k item=val}
        {if $val.id != $charakter.id}
        <tr class="Cnorm">
            <td align="right"><a href="index.php?chars-details-{$val.id}"><b>{$val.name}</b></a> {$val.level}</td>
            <td> {$val.race_name}|{$val.class_name}</td>  
        </tr>
        {/if}
    {/foreach}
</table>