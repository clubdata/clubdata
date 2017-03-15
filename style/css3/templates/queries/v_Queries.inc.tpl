<div class="vMain">
<div class="content equalheight">
  {if ( !isMember() ) }
    {if ( getUserType(VIEW, "Member") ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDES_PHP?mod=queries&view=MemberSummary"
               boxtitle="Member summary"|lang 
               boxid="QueriesMemberSummary"          
               boxhelp="Number of members per member type"|lang}
    {/if}
    {if ( getUserType(VIEW, "Member") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=queries&view=Statistics"
               boxtitle="Statistics"|lang 
               boxid="QueriesStatistics"          
               boxhelp="Several statistics, like number of paying members etc."|lang}
    {/if}
   {/if}
    {if ( getUserType(VIEW, "Member") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=queries&view=AddressLists"
               boxtitle="Addresslists"|lang 
               boxid="QueriesAddresslists"          
               boxhelp="Different address lists, e.g. for public use"|lang}
    {/if}
<!--   {if ( isMember() ) }
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   {/if}
 -->
</div>
</div>