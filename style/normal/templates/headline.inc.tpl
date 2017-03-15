{math equation="100/numCols" numCols=$headArr|@count assign="width"}

{if count($headArr) > 1 }
    {assign var="class" value="multi"}
{else}
    {assign var="class" value="single"}
{/if}

<table class='head' border='0' id='head-table'>
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

