<?php /* Smarty version 2.6.10, created on 2011-01-05 17:39:00
         compiled from help/v_Help.inc.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <meta http-equiv="CONTENT-TYPE" content="text/html; charset=<?php echo $this->_tpl_vars['HEAD_Encoding']; ?>
" />
        <title></title>
        <meta name="AUTHOR" content="Franz Domes" />
        <meta name="CREATED" content="20080212;15063100" />
        <link rel="StyleSheet" href="style/newstyle/css/style_help.css" type="text/css" media="screen" />
</head>
<body class="help">
<?php if (empty ( $this->_tpl_vars['mode'] ) || $this->_tpl_vars['mode'] != 'tooltip'): ?>
<h1><?php echo $this->_tpl_vars['head']; ?>
</h1>
<?php endif;  echo $this->_tpl_vars['hlpTxt']; ?>

<p />
<?php if (empty ( $this->_tpl_vars['mode'] ) || $this->_tpl_vars['mode'] != 'tooltip'): ?>
<div style="text-align: center">
<a href="javascript:window.close();">Fermez</a>
</div>
<?php endif; ?>

</body>
</html>