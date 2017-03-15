{math equation="100/numCols" numCols=$headArr|@count assign="width"}
{assign var="cols" value=$headArr|@count}

{if count($headArr) > 1 }
    {assign var="class" value="multi"}
{else}
    {assign var="class" value="single"}
{/if}
{assign var="class" value="col-1-$cols"}
<div class="row field-background headerline">
	{foreach name=headers item=headerItem from=$headArr}
	    {assign var="align" value="center"}
	    {if (count($headArr) > 1 && $smarty.foreach.headers.first)}
	        {assign var="align" value="left"}
	    {elseif (count($headArr) > 1 && $smarty.foreach.headers.last)}
	        {assign var="align" value="right"}
	    {/if}
      <div class="{$class} {$align}"><span>{$headerItem}</span></div>
	{/foreach}
</div>

