<div class="vMain">
<input type='hidden' name='State' value='{$NewState}'>
{if isset($cancelList)}
{include file='list.inc.tpl' listObj=$cancelList}
{elseif isset($insertFees)}
{$insertFees}
{elseif isset($directDebit)}
{$directDebit}
{else}
  <div class="table">
	  <div class="tablerow">
	    <div class="tablecol-content Description">{lang New membership period}</div>
	    <div class="tablecol-max Daten"><input name='EOY_PERIOD' value='' size='4' maxlength='4'></div>
	  </div>
	  
	  <div class="tablerow">
	    <div class="tablecol-content Description">{lang Process cancelled memberships}</div>
	    <div class="tablecol-max Daten">{html_options name='EOY_PROCCANCELLED' options=$YesNoSelection selected='YES'}</div>
	  </div>
	
	  <div class="tablerow">
	    <div class="tablecol-content Description">{lang Insert new membership fees (batch)}</div>
	    <div class="tablecol-max Daten">{html_options name='EOY_PROCFEES' options=$YesNoSelection selected='YES'}</div>
	  </div>
	
	  <div class="tablerow">
	    <div class="tablecol-content Description">{lang Insert payments by direct debit}</div>
	    <div class="tablecol-max Daten">{html_options name='EOY_PROCDIRECTDEB' options=$YesNoSelection selected='YES'}</div>
	  </div>
	</div>
</div>
{/if}
