<div class="alert alert-info">
    <i class="fa fa-info-circle fa-2x pull-left"></i> 
    <div class="pull-left">
        W&auml;hlen Sie bitte Ihre Adresse aus, die wird auch f&uuml;r eine Selbstabholung ben&ouml;tigt.<br>
        Bedenken Sie bitte das bei einer Hauslieferung, zus&auml;tzliche Kosten anfallen.
    </div>
    <br style="clear: both;" />
</div>

<div class="col-lg-6">
    {foreach $address as $k => $i}
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 4px!important;">
                    <a href="index.php?shop-order-address-{$i.address_id}">
                        <div class="alert alert-info" style="margin-bottom: 4px!important;">
                            {if $i.address_company}{$i.address_company}<br>{/if}
                            {$i.address_first_name}
                            {$i.address_last_name}<br>
                            {$i.address_street} {$i.address_street_nr}<br>
                            {$i.address_zipcode} {$i.address_place}<br><br>
                            {$i.address_phone}
                        </div>
                    </a>
                    <div class="btn-group btn-group-justified">	
                        <a class="btn btn-success" href="index.php?shop-order-address-{$i.address_id}">Benutzen</a>
                    </div><br />
                    <div class="btn-group btn-group-justified">	
                        <a class="btn btn-success" href="index.php?shop-order-editAddress-{$i.address_id}"><i class="fa fa-edit"></i> </a>
                        <a class="btn btn-danger" href="index.php?shop-order-deleteAddress-{$i.address_id}"><i class="fa fa-trash-o"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    {/foreach}
</div>

<div class="col-lg-6">
    <form class="form" action="index.php?shop-order-saveAddress{if !empty($edit.address_id)}-{$edit.address_id}{/if}" method="post" />
        <div class="panel panel-default">
            <div class="panel-heading">Adresse Erstellen</div>   
            <div class="panel-body">
                
                {if is_array($status)}
                <div class="alert alert-danger">
                    <i class="fa fa-info-circle pull-left"></i>
                    <div class="pull-left small">Alle Felder sind Pflicht, bitte f&uuml;llen Sie alle Rot eingerahmten felder aus.</div>
                    <br style="clear: both;" />
                </div>
                {/if}

                <div class="col-lg-12">
                    
                    <div class="form-group {if is_array($status) && !$status.address_company}has-error{/if}">
                        <label for="address_company">Firma</label>
                        <input class="form-control" type="text" placeholder="Firmenname" name="address_company" value="{$edit.address_company}" />
                    </div>
                    
                    <div class="form-group {if is_array($status) && !$status.address_last_name}has-error{/if}">
                        <label for="address_last_name">Nachname</label>
                        <input class="form-control" type="name" placeholder="Nachname" name="address_last_name" value="{$edit.address_last_name}" />
                    </div>
                    
                    <div class="form-group {if is_array($status) && !$status.address_first_name}has-error{/if}">
                        <label>Vorname</label>
                        <input class="form-control" type="name" placeholder="Vorname" name="address_first_name" value="{$edit.address_first_name}" />
                    </div>
                    
                </div>

                <label class="col-xs-9">Strasse</label>
                <label class="col-xs-3">Haus-Nr.</label>
                <div class="col-xs-9">
                    <div class="form-group {if is_array($status) && !$status.address_street}has-error{/if}">
                        <input class="form-control" type="name" placeholder="Strasse" name="address_street" value="{$edit.address_street}" />
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group {if is_array($status) && !$status.address_street_nr}has-error{/if}">
                        <input class="form-control " type="name" placeholder="Hausnummer" name="address_street_nr" value="{$edit.address_street_nr}" />
                    </div>
                </div>

                <label class="col-xs-4">PLZ</label>
                <label class="col-xs-8">Stadt</label>
                <div class="col-xs-4">
                    <div class="form-group {if is_array($status) && !$status.address_zipcode}has-error{/if}">
                        <input class="form-control" type="name" placeholder="PLZ" name="address_zipcode" value="{$edit.address_zipcode}" />
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="form-group {if is_array($status) && !$status.address_place}has-error{/if}">
                        <input class="form-control " type="name" placeholder="Stadt" name="address_place" value="{$edit.address_place}" />
                    </div>
                </div>
                <br style="clear: both;" /><br><br>

                <div class="col-lg-12">
                    <div class="form-group {if is_array($status) && !$status.address_phone}has-error{/if}">
                        <label>Telefon / Mobil</label>
                        <input class="form-control" type="name" placeholder="Telefonnummer" name="address_phone" value="{$edit.address_phone}" />
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <button class="btn btn-info" type="submit">Speichern</button>
            </div>
        </div>
    </form>
</div>