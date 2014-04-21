<div class="panel panel-default">
    <div class="panel-heading">Liste der Bewerber</div>
    <div class="panel-body">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    {$data.application}
                </div>
            </div>       
        </div>
        <div class="col-lg-4">
            <ul class="list-group">
                <li class="list-group-item">Wir Rekrutieren</li>
                {foreach from=$data.class item=class}
                    
                    {if $id != $class.class_id}
                        <li class="list-group-item list-inline" style="{$class.style}">                           
                            <img align="absmiddle" src="include/raidplaner/images/class/class_{$class.class_id}.jpg">
                            <b>{$class.class_name}</b>
                        </li>
                        {assign var="id" $class.class_id}
                        {assign "num" "1"}
                    {/if}
                    
                    <li class="list-group-item">{$class.search}x {$class.name} <div class="pull-right">zur Zeit: <b>{$class.num}</b></div></li>
                        
                    {assign "num" $num+1}
                {/foreach}
            </ul>
        </div>

        <div class="panel-body">
            <div class="btn-group btn-group-justified">
                <a class="btn btn-success" href="index.php?bewerbung-form"><i class="fa fa-file-text"></i> Formular</a>
                <a class="btn btn-primary" href="index.php?user-regist"><i class="fa fa-user"></i> Regestrieren</a>
            </div>
        </div>

    </div>
    <div class="panel-heading">Liste der Bewerber</div>
    <table width="100%" border="0" cellspacing="1" cellpadding="2" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th></th>
                <th>Level</th>                
                <th>Charakter Name</th>
                <th>Skill</th>
                <th>Datum</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {if count($data.candidate) != 0}
                {foreach from=$data.candidate item=charakter}
                <tr>
                    <td width="3%" style="{$charakter.class_style}"><img src="include/raidplaner/images/class/class_{$charakter.class_id}.jpg"></td>
                    <td><div align="center">{$charakter.level}</div></td>
                    <td><a href="index.php?chars-details-{$charakter.id}"><b>{$charakter.name}</b></a></td>
                    <td>{$charakter.s1_name} | {$charakter.s2_name}</td>
                    <td>{$charakter.regist|date_format:'%a %d.%m.%Y %H:%m'}</td>
                    <td style="{$charakter.class_style}"></td>
                </tr>
                {/foreach}
            {else}
            <tr>
                <td colspan="7" class="alert-warning"><i class="fa fa-info-circle fa-lg"></i> Es Liegen zur Zeit keine Bewerbungen vor.</td>
            </tr>
            {/if}
        </tbody>
    </table>
</div>