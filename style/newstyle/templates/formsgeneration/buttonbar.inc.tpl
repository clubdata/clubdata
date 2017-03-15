<!--buttonbar-->
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD width="10" class="light_border_left"></TD>
<TD>
{if count($form->inputs) > 0 }
<table CLASS=Buttonbar>
<TR>
    {* Walk on all entries in formsgeneration input array *}
    {foreach name=tabs key=schluessel item=button from=$form->inputs}
        {* If SubForm equals to buttonbar, process it here *}
        {if $button.SubForm == 'buttonbar'}
            {* If type is NOT hidden generate a data field, else just output it *}
            {if ($button.TYPE == "button" || $button.TYPE == "submit") }
                <TD class='clear'><div class='BUTTON'><span>{input name="$schluessel"}</span></div></TD>
            {elseif ($button.TYPE != "hidden") }
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
<HR style="margin:0px;">
{/if}
</TD>
<TD width="13" class="light_border_right"></TD>
</TR>
</TABLE>
<!--buttonbarende-->
