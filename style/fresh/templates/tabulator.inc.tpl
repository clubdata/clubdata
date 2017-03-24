{modClass->getTabulators assign="tabArr"}
{modClass->getCurrentView assign="selectedButton"}
{modClass->getModuleName assign="moduleName"}

{if $tabArr|@count > 0}
    {foreach name=tabs key=tabKey item=tabItem from=$tabArr}
        {if (!empty($tabItem))}
            {if ($tabKey == $selectedButton)}
                {assign var="class" value="active"}
            {else}
                {assign var="class" value=""}
            {/if}

            <div class="tabulator-tab {$class}">
                <a href="{$INDEX_PHP}?mod={$moduleName}&view={$tabKey}">{$tabItem|translate}</a>
            </div>
        {/if}
    {/foreach}
{/if}
