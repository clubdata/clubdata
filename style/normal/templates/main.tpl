<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>{$HEAD_Title}</title>
    <meta http-equiv="content-type" content="text/html; charset={$HEAD_Encoding}">
    <LINK REL=StyleSheet HREF="style/normal/style.css" TYPE="text/css" MEDIA=screen>
    <SCRIPT TYPE="text/javascript" SRC="javascript/js_main.js" CHARSET="UTF8"></SCRIPT>
    {$javascript}
</head>
<BODY>
<TABLE width="100%" border="0">
    <TR>
        <TD ROWSPAN="1" style="width: 2cm;" align="center" valign="top">
            &nbsp;
        </TD>
        <TD style="height: 1cm;" COLSPAN="1">
        {include file='headline.inc.tpl'}
        </TD>
    </TR>
    <TR>
        <TD ROWSPAN="1" style="width: 2cm;" align="center" valign="top">
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
                {include file='tabulator.inc.tpl'}
                </TD>
            </TR>
            <TR>
                <TD WIDTH="100%" COLSPAN="1" class="tabar">
<!--                {if empty($buttonbar)}
                    {include file='buttonbar.inc.tpl'}
                {else}-->
                    {$buttonbar}
<!--                {/if}-->
               </TD>
            </TR>
            {if $APerr->hasMessage()}
            <TR>
                <TD WIDTH="100%" COLSPAN="1" class="tabar">
                {include file='messages.inc.tpl'}
               </TD>
            </TR>
            {/if}
            <TR>
                <TD WIDTH="100%" COLSPAN="1" class="tabox">
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
