<table class="vMain" width="100%" cellspacing="0" cellpadding="0" height="500" BORDER="0">
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ul.png" height="10" width="10" border="0"></TD>
    <td class="light_border_upper"></td>
    <td><img src="style/newstyle/images/light_corner_ur.png" width="13" border="0"></td>
</TR>
<TR>
<TD class="light_border_left"></TD>
<TD valign="top">
<TABLE>
<TR>
    {if ( getUserType("Create",'Email') ) }
    <TD style="vertical-align: top">
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=search&view=Email"
               boxtitle="Send email"|lang
               boxhelp="Select members which like to receive emails and send emails to them"|lang}
    </TD>
    <td>&nbsp;</td>
    {/if}
    {if ( getUserType("Create",'Infoletter') ) }
    <TD style="vertical-align: top">
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=search&view=Infoletter"
               boxtitle="Send Infoletter"|lang 
               boxhelp="Select members which do want receive infos per letter and send infoletter to them"|lang}
    </TD>
    <td>&nbsp;</td>
    {/if}
    {if ( getUserType(VIEW,'Payments') ) }
    <TD style="vertical-align: top">
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=search&view=Invoice"
               boxtitle="Send Invoice"|lang 
               boxhelp="Select members which adopted for invoices and generate database for massletter"|lang}
    </TD>
     {/if}
   
</TR>
</TABLE>
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
