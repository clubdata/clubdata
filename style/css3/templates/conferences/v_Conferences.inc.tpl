<div class="vMain">
<div class="content equalheight">
    {if ( getUserType(VIEW, "Conferences") ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDES_PHP?mod=conferences&view=List&InitView=1"               
               boxtitle="List of Conferences"|lang
               boxid="ConferencesListOfConferences"
               boxhelp="List all conferences"|lang}
    {/if}
    {if ( getUserType(VIEW, "Conferences") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=search&view=Conferences"
               boxtitle="Search for conferences"|lang
               boxid="ConferencesSearchForConferences"
               boxhelp="Search for conferences by any column"|lang}
    {/if}
    {if ( getUserType(INSERT, "Conferences") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=conferences&view=Add"
               boxtitle="Add a new conference"|lang
               boxid="ConferencesNewConference"
               boxhelp="Here you can add a new conference"|lang}
    {/if}
</div>
</div>