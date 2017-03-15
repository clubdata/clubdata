<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{$HEAD_Title}</title>
<meta http-equiv="content-type"
	content="text/html; charset={$HEAD_Encoding}">
<LINK REL=StyleSheet HREF="style/css3/css/style.css" TYPE="text/css"
	MEDIA=screen>
<LINK REL=StyleSheet HREF="style/css3/css/jquery.cluetip.css"
  TYPE="text/css" MEDIA=screen>
<LINK REL=StyleSheet HREF="style/css3/jscript/jquery-ui-1.11.4.custom/jquery-ui.min.css"
  TYPE="text/css" MEDIA=screen>
<LINK REL=StyleSheet HREF="style/css3/css/style_help.css"
	TYPE="text/css" MEDIA=screen>
<LINK REL=StyleSheet HREF="style/css3/css/box.css" TYPE="text/css"
  MEDIA=screen>
<LINK REL=StyleSheet HREF="style/css3/css/tabulator.css" TYPE="text/css"
  MEDIA=screen>
<SCRIPT TYPE="text/javascript" SRC="javascript/jquery-1.11.3.js"
	CHARSET="UTF8"></SCRIPT>
<!--
@todo: Find a tooltip script for jquery which loads tooltips via Ajax
-->
<script type="text/javascript" src="style/css3/jscript/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="javascript/jquery.hoverIntent.js"></script>
<script type="text/javascript" src="javascript/jquery.cluetip.js"></script>
<SCRIPT TYPE="text/javascript" SRC="javascript/modalbox.js"
	CHARSET="UTF8"></SCRIPT>
{if isset($personalSettings.SHOW_TOOLTIP) &&
$personalSettings.SHOW_TOOLTIP == 0 }
<script type="text/javascript">
    show_tooltip = false;
</script>
{/if}
<SCRIPT TYPE="text/javascript" SRC="javascript/js_main.js"
	CHARSET="UTF8"></SCRIPT>
<script type="text/javascript" src="style/css3/jscript/js_css3.js"></script>
{$javascript}
</head>
<BODY>
	<div id='mask' class='close_modal'></div>

	<div id="page-wrap">
		<div class="row">
			<div class="main-nav">&nbsp;</div>
			<div class="main-content">{include file=$headerInclude
				curAction=$moduleName}</div>
		</div>
		<!-- /.row -->

		<div class="row">
			<div class="main-nav">{include file='navigator.inc.tpl'}</div>
			<div class="main-content">
				{$formDefinition|default:'
				<FORM NAME="TABFORM" action="$INDEX_PHP" enctype="multipart/form-data" method="POST">'}
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
	    <div class="row" style="margin-bottom: 0px;">
	      <div class="col-1-1">
	      {include file=$tabulatorInclude}
		    <div class="field-background content-section" style="min-height: 500px;">
		      {if count($form->inputs) > 0 }
			      <div class="row" style="margin-bottom: 0px;">
			        <div class="col-1-1">{$buttonbar}</div>
			      </div>
		      {/if}
		      {if $APerr->hasMessage()}
		        <div class="row">
		          <div class="col-1-1">{include file='messages.inc.tpl'}</div>
		        </div>
		      {/if}
		        <div class="row">
		          <div class="col-1-1">{include file=$mainSectionInclude}</div>
		        </div>
	      </div>
      </div>
	 </div>
  </div>
</BODY>
</html>
