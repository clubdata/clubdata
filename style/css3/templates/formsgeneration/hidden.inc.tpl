<!--hidden-->
{* Walk on all entries in formsgeneration input array *}
{foreach name=tabs key=schluessel item=button from=$form->inputs}
    {if ($button.TYPE == "hidden" || ($button.TYPE == "text" && isset($button.Accessible) && $button.Accessible == "0"))}
        {hiddeninput name="$schluessel"}
    {/if}
{/foreach}
<!--hiddenend-->
