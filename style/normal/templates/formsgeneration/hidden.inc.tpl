<!--hidden-->
{* Walk on all entries in formsgeneration input array *}
{foreach name=tabs key=schluessel item=button from=$form->inputs}
    {if ($button.TYPE == "hidden") }
        {$schluessel}
        {hiddeninput name="$schluessel"}
    {/if}
{/foreach}
<!--hiddenend-->
