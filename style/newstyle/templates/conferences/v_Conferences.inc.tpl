<table class="vMain" width="100%" cellspacing="0" cellpadding="0" height="500" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD valign="top">
<CENTER>
<TABLE width="100%" >
<TR>
    <TD style="vertical-align: top" width="33%">
    {if ( getUserType(VIEW, "Conferences") ) }
      {include file="general/box.inc.tpl"
               boxlink="$INDES_PHP?mod=conferences&view=List&InitView=1"
               boxtitle="List of Conferences"|lang
               boxhelp="List all conferences"|lang}
    {/if}
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    {if ( getUserType(VIEW, "Conferences") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=search&view=Conferences"
               boxtitle="Search for conferences"|lang
               boxhelp="Search for conferences by any column"|lang}
    {/if}
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    {if ( getUserType(INSERT, "Conferences") ) }
      {include file="general/box.inc.tpl" 
               boxlink="$INDEX_PHP?mod=conferences&view=Add"
               boxtitle="Add a new conference"|lang
               boxhelp="Here you can add a new conference"|lang}
    {/if}
    </TD>
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
