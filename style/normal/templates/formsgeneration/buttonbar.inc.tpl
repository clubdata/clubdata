<!--buttonbar-->
<table CLASS=Bottombar>
<TR>
    {* Walk on all entries in formsgeneration input array *}
    {foreach name=tabs key=schluessel item=button from=$form->inputs}
        {* If SubForm equals to buttonbar, process it here *}
        {if $button.SubForm == 'buttonbar'}
            {* If type is NOT hidden generate a data field, else just output it *}
            {if ($button.TYPE != "hidden") }
                <TD>{input name="$schluessel"}</TD>
            {/if}
        {/if}
    {/foreach}
<TD CLASS=FILL>&nbsp;</TD>
    {* Walk on all entries in formsgeneration input array *}
    {foreach name=tabs key=schluessel item=button from=$form->inputs}
        {* If SubForm equals to buttonbar_right, process it here *}
        {if $button.SubForm == 'buttonbar_right'}
            {* If type is NOT hidden generate a data field, else ignore it *}
            { if ($button.TYPE != "hidden") }
                <TD>{input name="$schluessel"}</TD>
            {/if}
        {/if}
    {/foreach}
</TR>
</TABLE>
<!--buttonbarende-->
