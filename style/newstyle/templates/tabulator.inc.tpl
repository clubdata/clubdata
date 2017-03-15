{modClass->getTabulators assign="tabArr"}
{modClass->getCurrentView assign="selectedButton"}
{modClass->getModuleName assign="moduleName"}
{if $tabArr|@count > 0 }
<table width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD style="background-color: #FFFFFF; width:10px;" ></TD>
<TD>
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
    <td class={$class} valign="middle" nowrap="nowrap">&nbsp;
        <!-- a href="javascript:doSubmit('{$moduleName}','{$tabKey}');">{$tabItem|translate}</A -->
        <a href="{$INDEX_PHP}?mod={$moduleName}&view={$tabKey}">{$tabItem|translate}</A>
        &nbsp;
    </TD>
    <td class={$class} valign="middle" width="3">
    {html_image file=$STYLE_DIR|cat:"images/tab"|cat:$sel|cat:"Right.png"}
    </td>
    <td width="3" class="tabsp">
        &nbsp;
    </td>
{/if}
{/foreach}
</tr>
</table>
</TD>
<td width="100%">&nbsp;</td>
<td>&nbsp;</td>
</TR>
<TR>
    <TD style="background-color: #FFFFFF; width:10px; font-size:6px;" >&nbsp;</TD>
    <td style="background-color: #a5cbf7;"></td>
    <TD class="light_border_upper"></TD>
    <td><img src="style/newstyle/images/light_corner_ur.png" width="13" border="0"></td>
</TR>
</TABLE>
{else}
<!-- Don't show tabs -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="10"><img src="style/newstyle/images/light_corner_ul.png" height="10" width="10" border="0"></td>
    <td width="100%" height="10" class="light_border_upper"></td>
    <td width="13"><img src="style/newstyle/images/light_corner_ur.png" width="13" height="10" border="0"></td>
</tr>
</table>
{/if}
