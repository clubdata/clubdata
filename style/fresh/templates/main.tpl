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
            {include file=$headerInclude curAction=$moduleName}
        </div>
        <div class="page-body">
            <div class="page-sidebar">
                {include file='navigator.inc.tpl'}
            </div>
            <div class="page-content">

            </div>
        </div>
    </div>
</body>
</html>
