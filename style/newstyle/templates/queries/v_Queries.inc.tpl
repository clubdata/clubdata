<table class="vMain" width="100%" cellspacing="0" cellpadding="0" height="500" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD valign="top">
<CENTER>
<TABLE width="100%" >
<TR>
  {if ( !isMember() ) }
    <TD style="vertical-align: top" width="33%">
    {if ( getUserType(VIEW, "Member") ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDES_PHP?mod=queries&view=MemberSummary"
               boxtitle="Member summary"|lang 
               boxhelp="Number of members per member type"|lang}
    {/if}
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    {if ( getUserType(VIEW, "Member") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=queries&view=Statistics"
               boxtitle="Statistics"|lang 
               boxhelp="Several statistics, like number of paying members etc."|lang}
    {/if}
    </TD>
    <td>&nbsp;</td>
   {/if}
    <TD style="vertical-align: top" width="33%">
    {if ( getUserType(VIEW, "Member") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=queries&view=AddressLists"
               boxtitle="Addresslists"|lang 
               boxhelp="Different address lists, e.g. for public use"|lang}
    {/if}
    </TD>
  {if ( isMember() ) }
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   {/if}
</TR>
</TABLE>
</CENTER>
</TD>
<TD class="light_border_right"></TD>
</TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>
