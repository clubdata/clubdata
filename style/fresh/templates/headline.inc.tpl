{math equation="100/numCols" numCols=$headArr|@count assign="width"}

{foreach name=headers item=headerItem from=$headArr}
    {assign var="align" value="center"}

    {if (count($headArr) > 1 && $smarty.foreach.headers.first)}
        {assign var="align" value="left"}
    {elseif (count($headArr) > 1 && $smarty.foreach.headers.last)}
        {assign var="align" value="right"}
    {/if}

    <div class="page-title-item" style="width: {$width}%; text-align: {$align};">
        {$headerItem}
    </div>
{/foreach}
