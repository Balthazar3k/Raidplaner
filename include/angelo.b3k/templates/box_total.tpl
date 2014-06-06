<div class="col-lg-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Einnahmen</b>
        </div>
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td class="text-right">Offen</td>
                    <td class="warning text-center"><b>{$sum.new|price} {'currency'|config}</b></td>
                </tr>
                <tr>
                    <td class="text-right">Bearbeitung</td>
                    <td class="info text-center"><b>{$sum.process|price} {'currency'|config}</b></td>
                </tr>
                <tr>
                    <td class="text-right">Erledigt</td>
                    <td class="success text-center"><b>{$sum.done|price} {'currency'|config}</b></td>
                </tr>
                <tr>
                    <td class="text-right">TOTAL</td>
                    <td class="text-center"><b>{$sum.total|price} {'currency'|config}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>