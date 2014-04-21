<div class="panel panel-default">
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
                    <td width="3%"><img src="include/raidplaner/images/wowklein/{$charakter.class_name}.gif"></td>
                    <td><div align="center">{$charakter.level}</div></td>
                    <td><b>{$charakter.name}</b></td>
                    <td>{$charakter.s1} | {$charakter.s2}</td>
                    <td>{$charakter.regist|date_format:'%a %d.%m.%Y %H:%m'}</td>
                    <td></td>
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

<div class="panel panel-default">
    <div class="panel-heading">Bewerbungen</div>
    <div class="panel-body">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    {$data.application}
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="btn-group btn-group-justified">
                        <a class="btn btn-success" href="index.php?bewerbung-form"><i class="fa fa-file-text"></i> Formular</a>
                        <a class="btn btn-primary" href="index.php?user-regist"><i class="fa fa-user"></i> Regestrieren</a>
                    </div>
                </div>
            </div>        
        </div>
        <div class="col-lg-4">
            <ul class="list-group list-group-vertical">
                {foreach from=$data.class item=class}
                    <li class="list-group-item list-group-item-success list-inline">
                        <img src="include/raidplaner/images/wowklein/{$class.klassen}.gif" align="absmiddle">
                        <b>{$class.klassen}</b>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>