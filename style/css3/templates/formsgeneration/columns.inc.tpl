{counter assign=checkCount start=0}
{assign var=title_old value=''}
<input type='HIDDEN' NAME='SETColumns' VALUE='1'>
<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
<tr>
    {foreach key=schluessel item=edit from=$form->inputs}
        {if ( strncmp($edit.SubForm,'buttonbar',9)) } {* Ignore buttonbar *}
            {if ( $edit.SubForm != $title_old)}
                {if (!empty($title_old))}
                    </tr>
                    {counter assign=checkCount start=0}
                {/if}
                {assign value=$edit.SubForm var=title_old}
                {if !empty($heads.$title_old)}
                    <th colspan="4" class="title">{$heads.$title_old}</th></tr><tr>
                {/if}
            {/if}
            {if (substr($schluessel,0,2) != '__' && substr($schluessel,0,2) != 'p_')}
                {if $edit.TYPE != 'hidden'}
                    <td style='width: 25%' nowrap>{input name="$schluessel"}{label for="$schluessel"}</td>
                    {counter}
                {/if}
            {/if}
            {if ( $checkCount > 0 && $checkCount % 4 == 0)}
                </tr><TR>
            {/if}
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
