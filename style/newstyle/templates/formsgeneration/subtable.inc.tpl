{counter assign=checkCount start=0}
<input type='HIDDEN' NAME='SET{$tableName}' VALUE='1'>
<table class=invisible width='100%' border='1' bgcolor='#FFFFF0'>
<tr>
    {foreach key=schluessel item=edit from=$form->inputs}
        {if $edit.SubForm == "$tableName" && (substr($schluessel,0,2) != 'p_')}
            <td style='width: 25%' nowrap>{input name="$schluessel"}{label for="$schluessel"}</td>
            {counter}
        {/if}
        {if ( $checkCount > 0 && $checkCount % 4 == 0)}
            </tr><TR>
        {/if}
    {/foreach}
    {if ( $checkCount % 4 != 0)}
        <td style='width: 25%' nowrap>&nbsp;</td>
        {counter}
    {/if}
    {if ( $checkCount % 4 != 0)}
        <td style='width: 25%' nowrap>&nbsp;</td>
        {counter}
    {/if}
    {if ( $checkCount % 4 != 0)}
        <td style='width: 25%' nowrap>&nbsp;</td>
        {counter}
    {/if}
</TR>
</table>
