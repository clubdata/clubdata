{foreach name=headers item=headerItem from=$headArr}
    {assign var="align" value="center"}

    {if (count($headArr) > 1 && $smart.foreach.headers.first)}
        {assign var="align" value="left"}
    {elseif (count($headArr) > 1 && $smarty.foreach.headers.last)}
        {assign var="align" value="right"}
    {/if}

    <span>{$headerItem}</span>
{/foreach}
