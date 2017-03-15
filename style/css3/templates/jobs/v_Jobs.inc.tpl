<div class="vMain">
<div class="content equalheight">
  {if ( getUserType(VIEW, "Payments") ) }
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=search&view=Payments"
             boxtitle="Search for payments"|lang 
               boxid="JobsSearchForPayments"
             boxhelp="Search for payments and displays related members"|lang}
   {/if}
  {if ( getUserType(VIEW, "Fees") ) }
    {include file="general/box.inc.tpl" 
             boxlink="$INDEX_PHP?mod=search&view=Fees"
             boxtitle="Search for fees"|lang 
               boxid="JobsSearchForFees"
             boxhelp="Search for fees and display related members"|lang}
  {/if}
  {if ( getUserType(VIEW, "Payments") ) }
    {include file="general/box.inc.tpl" 
             boxlink="$INDEX_PHP?mod=jobs&view=EndOfYear"
             boxtitle="End of Year Updates"|lang 
               boxid="JobsEndOfYearUpdates"
             boxhelp="Tasks which must run at the end of each year, like processing canceled members or inserting new fees"|lang}
   {/if}
</div>
</div>