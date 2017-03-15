{math equation="100/numCols" numCols=$headArr|@count assign="width"}

{if count($headArr) > 1 }
    {assign var="class" value="multi"}
{else}
    {assign var="class" value="single"}
{/if}

<table width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ul.png" height="10" width="10" border="0"></TD>
    <td class="light_border_upper"></td>
    <td><img src="style/newstyle/images/light_corner_ur.png" width="13" border="0"></td>
</TR>
<TR>
<TD class="light_border_left"></TD>
<TD>
<table class='head' cellspacing="0" cellpadding="0" border='0' id='head-table'>
<TR>
{foreach name=headers item=headerItem from=$headArr}
    {assign var="align" value="CENTER"}
    {if (count($headArr) > 1 && $smarty.foreach.headers.first)}
        {assign var="align" value="LEFT"}
    {elseif (count($headArr) > 1 && $smarty.foreach.headers.last)}
        {assign var="align" value="RIGHT"}
    {/if}
    <TD CLASS="{$class}" WIDTH="{$width}%" ALIGN="{$align}">{$headerItem}</TD>
{/foreach}
</TR>
</TABLE>
</TD>
<TD class="light_border_right"></TD>
</TD>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>


