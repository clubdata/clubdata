{assign value=0 var=AttrSeen}
{assign value=0 var=MailingSeen}
<div class='listTable table'>
    {foreach key=schluessel item=edit from=$form->inputs}
        { if ($edit.TYPE != "hidden") }
            {if $edit.SubForm == 'Attributes'}
                {if $AttrSeen != 1}
                    <div class="tablecol-equal Description">{$edit.SubForm}:</div>
                    <div class="tablecol-max Data">
                    {include file="formsgeneration/subtable.inc.tpl" form=$form tableName=$edit.SubForm}
                    </div>
                    {assign value=1 var=AttrSeen}
                {/if}
            {elseif $edit.SubForm == 'Mailingtypes'}
                {if $MailingSeen != 1}
                    <div class="tablecol-equal Description">{$edit.SubForm}:</div>
                    <div class="tablecol-max Data">
                    {include file="formsgeneration/subtable.inc.tpl" form=$form tableName=$edit.SubForm}
                    </div>
                    {assign value=1 var=MailingSeen}
                {/if}
            {elseif $edit.SubForm == '' && (substr($schluessel,0,2) != 'p_')}
            <div class="tablerow">
                {if (substr($schluessel,-7) == '_DELETE') }
                  <div class="tablecol-equal Description">{label for="$schluessel"}:</div>
                  <div class="tablecol-max Data">{input name="$schluessel"}</div>
                  <div class="tablecol-1-8 Data">
                  {if (!empty($edit.ApplicationData))}
                    {if ( substr($edit.ApplicationData,-4) == '.jpg' || substr($edit.ApplicationData,-4) == '.png' ||
                         substr($edit.ApplicationData,-4) == '.gif' || substr($edit.ApplicationData,-5) == '.jpeg')}
                      <img src="{image_path mode=small img=$edit.ApplicationData}" alt=""><BR>{$edit.ApplicationData}
                    {else}
                      <a href="{image_path img=$edit.ApplicationData}">{$edit.ApplicationData}</a>
                    {/if}
                  {/if}
                  </div>
                {else}
                  <div class="tablecol-equal Description">{label for="$schluessel"}:</div>
                  <div class="tablecol-max Data">{input name="$schluessel"}</div>
                {/if}
            </div>
            {/if}
        {/if}
    {/foreach}
</div>


<!-- <table border='0' class='listTable' width='100%'>
    <colgroup><COL width='1%'><COL width='10%'><COL width='89%'></colgroup>
    {foreach key=schluessel item=edit from=$form->inputs}
        { if ($edit.TYPE != "hidden") }
            {if $edit.SubForm == 'Attributes'}
                {if $AttrSeen != 1}
                    <td class="Description">{$edit.SubForm}:</td>
                    <td colspan="2" class="Data">
                    {include file="formsgeneration/subtable.inc.tpl" form=$form tableName=$edit.SubForm}
                    </td>
                    {assign value=1 var=AttrSeen}
                {/if}
            {elseif $edit.SubForm == 'Mailingtypes'}
                {if $MailingSeen != 1}
                    <td class="Description">{$edit.SubForm}:</td>
                    <td colspan="2" class="Data">
                    {include file="formsgeneration/subtable.inc.tpl" form=$form tableName=$edit.SubForm}
                    </td>
                    {assign value=1 var=MailingSeen}
                {/if}
            {elseif $edit.SubForm == '' && (substr($schluessel,0,2) != 'p_')}
            <tr>
                {if (substr($schluessel,-7) == '_DELETE') }
                  <td class="Description">{label for="$schluessel"}:</td>
                  <td class="Data">{input name="$schluessel"}</td>
                  <td class="Data">
                  {if (!empty($edit.ApplicationData))}
                    {if ( substr($edit.ApplicationData,-4) == '.jpg' || substr($edit.ApplicationData,-4) == '.png' ||
                         substr($edit.ApplicationData,-4) == '.gif' || substr($edit.ApplicationData,-5) == '.jpeg')}
                      <img src="{image_path mode=small img=$edit.ApplicationData}" alt=""><BR>{$edit.ApplicationData}
                    {else}
                      <a href="{image_path img=$edit.ApplicationData}">{$edit.ApplicationData}</a>
                    {/if}
                  {/if}
                  </td>
                {else}
                  <td class="Description">{label for="$schluessel"}:</td>
                  <td colspan="2" class="Data">{input name="$schluessel"}</td>
                {/if}
            </tr>
            {/if}
        {/if}
    {/foreach}
</table>


 -->