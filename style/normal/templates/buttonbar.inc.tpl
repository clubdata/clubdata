<!--buttons-->
<table CLASS=Bottombar>
<TR>
{foreach name=tabs item=button from=$buttonArr}

{if $button.type == 'image'}
<TD>
<a href="{$button.link|default:''}">
{html_image file=$STYLE_DIR|cat:"images/"|cat:$button.file alt=$button.name border="0"}
</a>
</TD>
{elseif $button.type == 'text'}
<TD>
{$button.label}
</TD>
{elseif $button.type == 'input'}
<TD>
<INPUT CLASS="TEXT" TYPE="TEXT" {$button.javascript|default:''} NAME={$button.name}
         SIZE="10" value={$button.default|default:''}>
</TD>
{elseif $button.type == 'button' || $button.type == 'submit' || $button.type == 'reset'}
<TD>
<BUTTON NAME={$button.name|default:''} TYPE={$button.type|default:''} {$button.javascript|default:''}
          value="{$button.value|default:''}">{$button.label|default:''}</BUTTON>
</TD>
{elseif ( $button.type == 'selection' )}
<TD>
<SELECT CLASS="BUTTON" SIZE="1" {$button.javascript} NAME="{$button.name}">
{foreach key=selKey item=selItem from=$button.value}
    <OPTION VALUE="{$selKey}">{$selItem}</OPTION>
{/foreach}
</SELECT>
</TD>
{/if}
{/foreach}
<TD CLASS=FILL>&nbsp;</TD>
{foreach name=tabs item=button from=$buttonArr}
{if $button.type == 'right_image'}
<TD>
<a href="{$button.link|default:''}">
{html_image file=$STYLE_DIR|cat:"images/"|cat:$button.file alt=$button.name border="0"}
</a>
</TD>
{elseif $button.type == 'right_button'}
<TD>
<BUTTON NAME="{$button.name|default:''}" TYPE={$button.type|default:''} {$button.javascript|default:''}
          value="{$button.default|default:''}">{$button.label|default:''}</BUTTON>
</TD>
{/if}
{/foreach}
</TR>
</TABLE>
{foreach name=tabs item=button from=$buttonArr}
{if $button.type == 'hidden'}
<INPUT TYPE="HIDDEN" NAME="{$button.name|default:''}" value="{$button.default|default:''}">
{/if}
{/foreach}
