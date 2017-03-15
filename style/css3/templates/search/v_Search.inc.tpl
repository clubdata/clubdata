<div class="vMain">
<div class="content equalheight">
    {if ( getUserType("Create",'Email') ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=search&view=Email"
               boxtitle="Send email"|lang
               boxid="SearchSendEmail"          
               boxhelp="Select members which like to receive emails and send emails to them"|lang}
    {/if}
    {if ( getUserType("Create",'Infoletter') ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=search&view=Infoletter"
               boxtitle="Send Infoletter"|lang 
               boxid="SearchSendInfoletter"          
               boxhelp="Select members which do want receive infos per letter and send infoletter to them"|lang}
    {/if}
    {if ( getUserType(VIEW,'Payments') ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=search&view=Invoice"
               boxtitle="Send Invoice"|lang 
               boxid="SearchSendInvoice"          
               boxhelp="Select members which adopted for invoices and generate database for massletter"|lang}
     {/if}
</div>
</div>