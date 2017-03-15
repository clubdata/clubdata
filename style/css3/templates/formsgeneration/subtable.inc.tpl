{counter assign=checkCount start=0}
<input type='HIDDEN' NAME='SET{$tableName}' VALUE='1'>
<div class='invisible table' style="background-color: #FFFFF0; border: solid 1px gray; margin-left: 0px;">
    <div class="tablerow">
    {foreach key=schluessel item=edit from=$form->inputs}
        {if $edit.SubForm == "$tableName" && (substr($schluessel,0,2) != 'p_')}
          <div class="tablecol-1-4">{input name="$schluessel"}{label for="$schluessel"}</div>
            {counter}
        {/if}
        {if ( $checkCount > 0 && $checkCount % 4 == 0)}
            </div><div class="tablerow">
        {/if}
    {/foreach}
    {if ( $checkCount % 4 != 0)}
        <div class="tablecol-1-4">&nbsp;</div>
        {counter}
    {/if}
    {if ( $checkCount % 4 != 0)}
        <div class="tablecol-1-4">&nbsp;</div>
        {counter}
    {/if}
    {if ( $checkCount % 4 != 0)}
        <div class="tablecol-1-4">&nbsp;</div>
        {counter}
    {/if}
    </div>
</div>
