<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

    <title>{$HEAD_Title}</title>

    <link href="bower_components/normalize-css/normalize.css" rel="stylesheet" />
    <link href="style/fresh/css/base.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div class="page-title clearfix">
                {include file=$headerInclude curAction=$moduleName}
            </div>
        </div>
        <div class="page-body">
            <div class="page-sidebar">
                {include file='navigator.inc.tpl'}
            </div>
            <div class="page-content">
                {$formDefinition|default:'<form name="tabform" action="$INDEX_PHP" enctype="multipart/form-data" method="post">'}

                <input type="hidden" name="view" value="{$currentView}" />
                <input type="hidden" name="mod" value="{$moduleName}" />

                {if isset($MemberID)}
                    {if is_array($MemberID)}
                        {foreach from=$MemberID item=aktMemberID}
                            <input type="hidden" name="MemberID[]" value="{$aktMemberID}" / />
                        {/foreach}
                    {else}
                        <input type="hidden" name="MemberID" value="{$MemberID}" />
                    {/if}
                {/if}

                {$hiddenform}

                <div class="tabulator">
                    {include file=$tabulatorInclude}
                </div>

                {if count($form->inputs) > 0}
                    <div class="buttonbar">
                        {$buttonbar}
                    </div>
                {/if}

                {if $APerr->hasMessage()}
                    <div class="messages">
                        {include file='messages.inc.tpl'}
                    </div>
                {/if}

                {include file=$mainSectionInclude}
            </div>
        </div>
    </div>

    {$javascript}
</body>
</html>
