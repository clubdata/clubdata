{assign value=0 var=AttrSeen}
{assign value=0 var=MailingSeen}

<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
    <COLGROUP><COL WIDTH='1%'><COL WIDTH='99%'></COLGROUP>
    {foreach key=schluessel item=edit from=$form->inputs}
        { if ($edit.TYPE != "hidden") }
            {if $edit.SubForm == 'Attributes'}
                {if $AttrSeen != 1}
                    <TD class="Description">{$edit.SubForm}:</TD>
                    <td class="Data">
                    {include file="formsgeneration/subtable.inc.tpl" form=$form tableName=$edit.SubForm}
                    </td>
                    {assign value=1 var=AttrSeen}
                {/if}
            {elseif $edit.SubForm == 'Mailingtypes'}
                {if $MailingSeen != 1}
                    <TD class="Description">{$edit.SubForm}:</TD>
                    <td class="Data">
                    {include file="formsgeneration/subtable.inc.tpl" form=$form tableName=$edit.SubForm}
                    </td>
                    {assign value=1 var=MailingSeen}
                {/if}
            {elseif $edit.SubForm == '' && (substr($schluessel,0,2) != 'p_')}
            <tr>
                <TD class="Description">{label for="$schluessel"}:</TD>
                <td class="Data">{input name="$schluessel"}</td>
            </tr>
            {/if}
        {/if}
    {/foreach}
</TABLE>


