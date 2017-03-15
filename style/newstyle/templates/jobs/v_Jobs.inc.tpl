<table class="vMain" width="100%" cellspacing="0" cellpadding="0" height="500" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD valign="top">
<CENTER>
<TABLE width="100%" >
<TR>
    <TD style="vertical-align: top" width="33%">
  {if ( getUserType(VIEW, "Payments") ) }
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=search&view=Payments"
             boxtitle="Search for payments"|lang 
             boxhelp="Search for payments and displays related members"|lang}
   {/if}
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
  {if ( getUserType(VIEW, "Fees") ) }
    {include file="general/box.inc.tpl" 
             boxlink="$INDEX_PHP?mod=search&view=Fees"
             boxtitle="Search for fees"|lang 
             boxhelp="Search for fees and display related members"|lang}
  {/if}
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
  {if ( getUserType(VIEW, "Payments") ) }
    {include file="general/box.inc.tpl" 
             boxlink="$INDEX_PHP?mod=jobs&view=EndOfYear"
             boxtitle="End of Year Updates"|lang 
             boxhelp="Tasks which must run at the end of each year, like processing canceled members or inserting new fees"|lang}
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
