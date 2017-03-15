{modClass->getTabulators assign="tabArr"}
{modClass->getCurrentView assign="selectedButton"}
{modClass->getModuleName assign="moduleName"}
<table class="tabulator" border="0" cellpadding="0" cellspacing="0">
<tr>
{foreach name=tabs key=tabKey item=tabItem from=$tabArr}
{if (!empty($tabItem)) }
    {if ("$tabKey" == "$selectedButton")}
        {assign var="class" value="tabon"}
        {assign var="sel" value="Selected"}
    {else}
        {assign var="class" value="taboff"}
        {assign var="sel" value=""}
    {/if}
    <td height="28" valign="middle" width="3">
    {html_image file=$STYLE_DIR|cat:"images/tab"|cat:$sel|cat:"Left.png"}
    </td>
    <td valign="middle" nowrap="nowrap"
        background='{$STYLE_DIR|cat:"images/tab"|cat:$sel|cat:"Bg.png"}'>&nbsp;
        <!-- a href="javascript:doSubmit('{$moduleName}','{$tabKey}');">{$tabItem|translate}</A -->
        <a href="{$INDEX_PHP}?mod={$moduleName}&view={$tabKey}">{$tabItem|translate}</A>
        &nbsp;
    </TD>
    <td valign="middle" width="3">
    {html_image file=$STYLE_DIR|cat:"images/tab"|cat:$sel|cat:"Right.png"}
    </td>
    <td width="3" class="tabsp">
        &nbsp;
    </td>
{/if}
{/foreach}
</tr>
</table>
