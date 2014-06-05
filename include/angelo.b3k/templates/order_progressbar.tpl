<div class="bs-wizard col-lg-12" style="border-bottom:0;">

    <div class="col-xs-3 bs-wizard-step {if isset($smarty.session.shop.order.order_type)}complete{elseif ($smarty.session.shop.order|count)-1 >= 1}active{else}disabled{/if}">
      <div class="text-center bs-wizard-stepnum">Schritt 1</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center"><b>Bestellart</b><hr>Bitte w&aumlhlen Sie die gew&uuml;nschten Bestellart aus.</div>
    </div>

    <div class="col-xs-3 bs-wizard-step {if isset($smarty.session.shop.order.order_payment)}complete{elseif ($smarty.session.shop.order|count)-1 >= 2}active{else}disabled{/if}"><!-- complete -->
      <div class="text-center bs-wizard-stepnum">Schritt 2</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center"><b>Zahlungsmethode</b><hr>Wie m&ouml;chten Sie Bezahlen?</div>
    </div>

    <div class="col-xs-3 bs-wizard-step {if isset($smarty.session.shop.order.order_address)}complete{elseif ($smarty.session.shop.order|count)-1 >= 3}active{else}disabled{/if}"><!-- complete -->
      <div class="text-center bs-wizard-stepnum">Schritt 3</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center"><b>Adresse</b><hr>W&auml;hlen Sie oder erstellen Sie Ihre Adresse.<br>Die Adresse wird auch ben&ouml;tigt f&uuml;r Selbstabholer.</div>
    </div>

    <div class="col-xs-3 bs-wizard-step {if isset($smarty.session.shop.order.order_confirm)}complete{elseif ($smarty.session.shop.order|count)-1 >= 4}active{else}disabled{/if}"><!-- active -->
      <div class="text-center bs-wizard-stepnum">Schritt 4</div>
      <div class="progress"><div class="progress-bar"></div></div>
      <a href="#" class="bs-wizard-dot"></a>
      <div class="bs-wizard-info text-center"><b>Best&auml;tigen</b><hr>Best&auml;tigen Sie Ihre Bestellung in dem Sie die AGB aktzeptieren und die Bestellung abschiken.</div>
    </div>
</div><br />