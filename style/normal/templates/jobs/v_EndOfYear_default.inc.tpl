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