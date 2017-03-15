<div class="vMain">
	<div class="row-smallmargin">
		<div class="col-equal Description">
		    {"Conference to subscribe"|lang}:
		    <input type="hidden" name="SubscriptionID" value="{$SubscriptionID}">
		</div>
		<div class="col-equal Data">
		    {html_options id=ConferenceID name=ConferenceID options=$subscription selected=$subscriptionSelected}
		</div>
	</div>
	<div class="row-smallmargin">
		<div class="col-equal Description">
		    {"Number of participants"|lang}:
		    <input type="hidden" name="SubscriptionID" value="{$SubscriptionID}">
		</div>
		<div class="col-equal Data">
      <input id="numPart" type="text" onkeydown="changeColorOnKey(this)" onblur="changeColorIfChanged(this, '')" maxlength="50" value="{$numPersons}" name="numPart"/>
		</div>
	</div>
</div>
