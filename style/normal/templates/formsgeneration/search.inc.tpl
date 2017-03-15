{*
Template: Search

Variables passed:   $form   input form definition
                    $heads  array of possible headlines

Description:
                Shows the search formular, by sections.
                The format of the names of the input are [Sectionname%]key[_select]
                Only keys without _select are treated. The corresponding _select
                input is used for each key automatically
                The sectionname is separated by the regexp /(([^%]*)%)?.*$/.
                If it changes, a headline is inserted
*}
{assign value='' var=title_old}
<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
    <COLGROUP><COL WIDTH='1%'><COL WIDTH='1%'><COL WIDTH='99%'></COLGROUP>
    {foreach key=schluessel item=edit from=$form->inputs}
        {if (substr($schluessel,0,2) != '__' &&
             substr($schluessel,0,2) != 'p_' &&
             substr($schluessel,-7) != '_select') &&
             strncmp($edit.SubForm,'buttonbar',9)} {* Ignore buttonbar *}

             { if ($edit.TYPE != "hidden") }
                {assign value=$schluessel|regex_replace:"/(([^%]*)%)?.*$/":"\\2" var=title}
                {if ( $title != $title_old) && !empty($heads.$title)}
                    <tr><td colspan="3" class="Daten">{$heads.$title}</td></tr>
                    
                    {assign value=$title var=title_old}
                {/if}
                {assign value=$schluessel|cat:'_select' var=schluesselselect}
                <tr>
                    <TD class="Description">{label for="$schluessel"}:</TD>
                    <td class="Description">{input name="$schluesselselect"}</TD>
                    {if !empty($edit.MULTIPLE) }
                    <td class="DATA">
                    <TABLE><TR>
                        <TD rowspan="2">{input name="$schluessel"}</TD>
                        <TD><input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(1, '{$schluessel|cat:"[]"}');SetComparision('INSelection', '{$schluesselselect}');" value='Select ALL'></TD>
                    </TR>
                    <TR><TD><input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(0, '{$schluessel|cat:"[]"}');SetComparision('INSelection', '{$schluesselselect}');" value='Deselect ALL'></TD></TR>
                    </TABLE>
                    </td>
                    <td class="Data"></td>
                    {else}
                    <td class="Data">{input name="$schluessel"}</td>
                    {/if}
                </tr>
            {/if}
        {/if}
    {/foreach}
</TABLE>


