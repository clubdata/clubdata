<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset={$HEAD_Encoding}">

    <title>{$HEAD_Title}</title>

    <link rel="stylesheet" href="style/css3/css/style.css" type="text/css" media="screen">
    <link rel="stylesheet" href="style/css3/css/jquery.cluetip.css" type="text/css" media="screen">
    <link rel="stylesheet" href="style/css3/jscript/jquery-ui-1.11.4.custom/jquery-ui.min.css" type="text/css" media="screen">
    <link rel="stylesheet" href="style/css3/css/style_help.css" type="text/css" media="screen">
    <link rel="stylesheet" href="style/css3/css/box.css" type="text/css" media="screen">
    <link rel="stylesheet" href="style/css3/css/tabulator.css" type="text/css" media="screen">
</head>
<body>
    <div id="mask" class="close_modal"></div>

    <div id="page-wrap">
        <div class="row">
            <div class="main-nav">&nbsp;</div>
            <div class="main-content">{include file=$headerInclude curAction=$moduleName}</div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="main-nav">{include file='navigator.inc.tpl'}</div>
            <div class="main-content">
                {$formDefinition|default:'<FORM NAME="TABFORM" action="$INDEX_PHP" enctype="multipart/form-data" method="POST">'}

                <input type="hidden" name="view" value="{$currentView}">
                <input type="hidden" name="mod" value="{$moduleName}">

                {if isset($MemberID)}
                    {if is_array($MemberID)}
                        {foreach from=$MemberID item=aktMemberID}
                            <input type="hidden" name="MemberID[]" value="{$aktMemberID}">
                        {/foreach}
                    {else}
                        <input type="hidden" name="MemberID" value="{$MemberID}">
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
        </div>
    </div>

    <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js" charset="utf8"></script>
    <!--
    @todo: Find a tooltip script for jquery which loads tooltips via Ajax
    -->
    <script type="text/javascript" src="style/css3/jscript/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <script type="text/javascript" src="bower_components/jquery-hoverintent/jquery.hoverIntent.js"></script>
    <script type="text/javascript" src="javascript/jquery.cluetip.js"></script>
    <script type="text/javascript" src="javascript/modalbox.js" charset="utf8"></script>

    {if isset($personalSettings.SHOW_TOOLTIP) && $personalSettings.SHOW_TOOLTIP == 0}
    <script type="text/javascript">
        show_tooltip = false;
    </script>
    {/if}

    <script type="text/javascript" src="javascript/js_main.js" charset="utf8"></script>
    <script type="text/javascript" src="style/css3/jscript/js_css3.js"></script>

    {$javascript}
</BODY>
</html>
