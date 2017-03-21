<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>{$HEAD_Title}</title>
    <meta http-equiv="content-type" content="text/html; charset={$HEAD_Encoding}">
    <LINK REL=StyleSheet HREF="style/newstyle/style.css" TYPE="text/css" MEDIA=screen>
    <LINK REL=StyleSheet HREF="style/newstyle/css/jquery.cluetip.css" TYPE="text/css" MEDIA=screen>
    <LINK REL=StyleSheet HREF="style/newstyle/css/style_help.css" TYPE="text/css" MEDIA=screen>
    <SCRIPT TYPE="text/javascript" SRC="bower_components/jquery/dist/jquery.min.js" CHARSET="UTF8"></SCRIPT>
<!--
@todo: Find a tooltip script for jquery which loads tooltips via Ajax
-->
    <script type="text/javascript" src="javascript/jquery.hoverIntent.js" ></script>
    <script type="text/javascript" src="javascript/jquery.cluetip.js" ></script>
    <SCRIPT TYPE="text/javascript" SRC="javascript/modalbox.js" CHARSET="UTF8"></SCRIPT>
{if isset($personalSettings.SHOW_TOOLTIP) && $personalSettings.SHOW_TOOLTIP == 0 }
<script type="text/javascript">
	show_tooltip = false;
</script>
{/if}
    <SCRIPT TYPE="text/javascript" SRC="javascript/js_main.js" CHARSET="UTF8"></SCRIPT>

    {$javascript}
</head>
<BODY>
<div id='mask' class='close_modal'></div>
<TABLE width="100%" border="0">
    <TR>
        <TD ROWSPAN="1" style="width: 2cm;" align="center" valign="top">
            &nbsp;
        </TD>
        <TD COLSPAN="1">
        {include file=$headerInclude curAction=$moduleName}
        </TD>
    </TR>
    <TR>
        <TD ROWSPAN="1" style="width: 2.5cm;" align="center" valign="top">
        {include file='navigator.inc.tpl'}
        </TD>
        <TD ROWSPAN="1" align="center" valign="top">
            {$formDefinition|default:'<FORM NAME="TABFORM" action="$INDEX_PHP" enctype="multipart/form-data" method="POST">'}
            <INPUT TYPE=HIDDEN NAME=view VALUE='{$currentView}'>
            <INPUT TYPE=HIDDEN NAME=mod VALUE='{$moduleName}'>
            {if isset($MemberID)}
                {if is_array($MemberID)}
                {foreach from=$MemberID item=aktMemberID}
                    <INPUT TYPE=HIDDEN NAME='MemberID[]' VALUE='{$aktMemberID}'>
                {/foreach}
                {else}
                    <INPUT TYPE=HIDDEN NAME='MemberID' VALUE='{$MemberID}'>
                {/if}
            {/if}
            {$hiddenform}
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <TR>
                <TD>
                {include file=$tabulatorInclude}
                </TD>
            </TR>
{if count($form->inputs) > 0 }
            <TR>
                <TD width="100%" COLSPAN="1">
                    {$buttonbar}
               </TD>
            </TR>
{/if}
            {if $APerr->hasMessage()}
            <TR>
                <TD WIDTH="100%" COLSPAN="1">
                {include file='messages.inc.tpl'}
               </TD>
            </TR>
            {/if}
            <TR>
                <TD WIDTH="100%" COLSPAN="1">
                    {include file=$mainSectionInclude}
                </td>
            </tr>
            </table>
        </FORM>
        </TD>
    </TR>
</TABLE>
</BODY>
</html>
