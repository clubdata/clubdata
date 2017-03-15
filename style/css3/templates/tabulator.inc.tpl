{modClass->getTabulators assign="tabArr"}
{modClass->getCurrentView assign="selectedButton"}
{modClass->getModuleName assign="moduleName"}
{if $tabArr|@count > 0 }
<div>
<ul class="tabulatorClass clearfix">
{foreach name=tabs key=tabKey item=tabItem from=$tabArr}
{if (!empty($tabItem)) }
    {if ("$tabKey" == "$selectedButton")}
        {assign var="sel" value="checked"}
    {else}
        {assign var="sel" value=""}
    {/if}
    <li>
	    <input type="radio" name="tabulators" id="tab-{$tabKey}" {$sel} onclick="doAction('{$moduleName}','{$tabKey}');">
	    <label for="tab-{$tabKey}">{$tabItem|translate}</label>
    </li>
{/if}
{/foreach}
</ul>
</div>
{/if}