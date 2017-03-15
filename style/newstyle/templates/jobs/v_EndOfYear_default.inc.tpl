<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
<INPUT TYPE='HIDDEN' NAME='State' VALUE='{$NewState}'>
{if isset($cancelList)}
{include file='list.inc.tpl' listObj=$cancelList}
{elseif isset($insertFees)}
{$insertFees}
{elseif isset($directDebit)}
{$directDebit}
{else}
<TABLE WIDTH="95%" class="listTable">
<TR>
    <TD class="Description" >{lang New membership period}</TD>
    <TD CLASS='Daten'><INPUT NAME='EOY_PERIOD' VALUE='' SIZE='4' MAXLENGTH='4'></TD>
</TR>
<TR>
    <TD class="Description" >{lang Process cancelled memberships}</TD>
    <TD CLASS='Daten'>{html_options name='EOY_PROCCANCELLED' options=$YesNoSelection selected='YES'}</TD>
</TR>
<TR>
    <TD class="Description" >{lang Insert new membership fees (batch)}</TD>
    <TD CLASS='Daten'>{html_options name='EOY_PROCCANCELLED' options=$YesNoSelection selected='YES'}</TD>
</TR>
<TR>
    <TD class="Description" >{lang Insert payments by direct debit}</TD>
    <TD CLASS='Daten'>{html_options name='EOY_PROCDIRECTDEB' options=$YesNoSelection selected='YES'}</TD>
</TR>
</TABLE>
{/if}
</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>
