<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:31
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main.tpl', 41, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title><?php echo $this->_tpl_vars['HEAD_Title']; ?>
</title>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->_tpl_vars['HEAD_Encoding']; ?>
">
    <LINK REL=StyleSheet HREF="style/newstyle/style.css" TYPE="text/css" MEDIA=screen>
    <LINK REL=StyleSheet HREF="style/newstyle/css/jquery.cluetip.css" TYPE="text/css" MEDIA=screen>
    <LINK REL=StyleSheet HREF="style/newstyle/css/style_help.css" TYPE="text/css" MEDIA=screen>
    <SCRIPT TYPE="text/javascript" SRC="javascript/jquery-1.3.2.js" CHARSET="UTF8"></SCRIPT>
<!--
@todo: Find a tooltip script for jquery which loads tooltips via Ajax
-->
    <script type="text/javascript" src="javascript/jquery.hoverIntent.js" ></script>
    <script type="text/javascript" src="javascript/jquery.cluetip.js" ></script>
    <SCRIPT TYPE="text/javascript" SRC="javascript/modalbox.js" CHARSET="UTF8"></SCRIPT>
<?php if (isset ( $this->_tpl_vars['personalSettings']['SHOW_TOOLTIP'] ) && $this->_tpl_vars['personalSettings']['SHOW_TOOLTIP'] == 0): ?>
<script type="text/javascript">
	show_tooltip = false;
</script>
<?php endif; ?>
    <SCRIPT TYPE="text/javascript" SRC="javascript/js_main.js" CHARSET="UTF8"></SCRIPT>

    <?php echo $this->_tpl_vars['javascript']; ?>

</head>
<BODY>
<div id='mask' class='close_modal'></div>
<TABLE width="100%" border="0">
    <TR>
        <TD ROWSPAN="1" style="width: 2cm;" align="center" valign="top">
            &nbsp;
        </TD>
        <TD COLSPAN="1">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['headerInclude'], 'smarty_include_vars' => array('curAction' => $this->_tpl_vars['moduleName'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </TD>
    </TR>
    <TR>
        <TD ROWSPAN="1" style="width: 2.5cm;" align="center" valign="top">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'navigator.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </TD>
        <TD ROWSPAN="1" align="center" valign="top">
            <?php echo ((is_array($_tmp=@$this->_tpl_vars['formDefinition'])) ? $this->_run_mod_handler('default', true, $_tmp, '<FORM NAME="TABFORM" action="$INDEX_PHP" enctype="multipart/form-data" method="POST">') : smarty_modifier_default($_tmp, '<FORM NAME="TABFORM" action="$INDEX_PHP" enctype="multipart/form-data" method="POST">')); ?>

            <INPUT TYPE=HIDDEN NAME=view VALUE='<?php echo $this->_tpl_vars['currentView']; ?>
'>
            <INPUT TYPE=HIDDEN NAME=mod VALUE='<?php echo $this->_tpl_vars['moduleName']; ?>
'>
            <?php if (isset ( $this->_tpl_vars['MemberID'] )): ?>
                <?php if (is_array ( $this->_tpl_vars['MemberID'] )): ?>
                <?php $_from = $this->_tpl_vars['MemberID']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aktMemberID']):
?>
                    <INPUT TYPE=HIDDEN NAME='MemberID[]' VALUE='<?php echo $this->_tpl_vars['aktMemberID']; ?>
'>
                <?php endforeach; endif; unset($_from); ?>
                <?php else: ?>
                    <INPUT TYPE=HIDDEN NAME='MemberID' VALUE='<?php echo $this->_tpl_vars['MemberID']; ?>
'>
                <?php endif; ?>
            <?php endif; ?>
            <?php echo $this->_tpl_vars['hiddenform']; ?>

            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <TR>
                <TD>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tabulatorInclude'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </TD>
            </TR>
<?php if (count ( $this->_tpl_vars['form']->inputs ) > 0): ?>
            <TR>
                <TD width="100%" COLSPAN="1">
                    <?php echo $this->_tpl_vars['buttonbar']; ?>

               </TD>
            </TR>
<?php endif; ?>
            <?php if ($this->_tpl_vars['APerr']->hasMessage()): ?>
            <TR>
                <TD WIDTH="100%" COLSPAN="1">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'messages.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
               </TD>
            </TR>
            <?php endif; ?>
            <TR>
                <TD WIDTH="100%" COLSPAN="1">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['mainSectionInclude'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
            </tr>
            </table>
        </FORM>
        </TD>
    </TR>
</TABLE>
</BODY>
</html>