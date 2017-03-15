<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <meta http-equiv="CONTENT-TYPE" content="text/html; charset={$HEAD_Encoding}" />
        <title></title>
        <meta name="AUTHOR" content="Franz Domes" />
        <meta name="CREATED" content="20080212;15063100" />
        <link rel="StyleSheet" href="style/newstyle/css/style_help.css" type="text/css" media="screen" />
</head>
<body class="help">
{if empty($mode) || $mode != 'tooltip' }
<h1>{$head}</h1>
{/if}
{$hlpTxt}
<p />
{if empty($mode) || $mode != 'tooltip' }
<div style="text-align: center">
<a href="javascript:window.close();">{lang Close}</a>
</div>
{/if}

</body>
</html>
