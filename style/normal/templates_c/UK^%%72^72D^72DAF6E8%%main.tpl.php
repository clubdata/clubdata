<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:07
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main.tpl', 25, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title><?php echo $this->_tpl_vars['HEAD_Title']; ?>
</title>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->_tpl_vars['HEAD_Encoding']; ?>
">
    <LINK REL=StyleSheet HREF="style/normal/style.css" TYPE="text/css" MEDIA=screen>
    <SCRIPT TYPE="text/javascript" SRC="javascript/js_main.js" CHARSET="UTF8"></SCRIPT>
    <?php echo $this->_tpl_vars['javascript']; ?>

</head>
<BODY>
<TABLE width="100%" border="0">
    <TR>
        <TD ROWSPAN="1" style="width: 2cm;" align="center" valign="top">
            &nbsp;
        </TD>
        <TD style="height: 1cm;" COLSPAN="1">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'headline.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </TD>
    </TR>
    <TR>
        <TD ROWSPAN="1" style="width: 2cm;" align="center" valign="top">
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
$this->_smarty_include(array('smarty_include_tpl_file' => 'tabulator.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </TD>
            </TR>
            <TR>
                <TD WIDTH="100%" COLSPAN="1" class="tabar">
<!--                <?php if (empty ( $this->_tpl_vars['buttonbar'] )): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'buttonbar.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php else: ?>-->
                    <?php echo $this->_tpl_vars['buttonbar']; ?>

<!--                <?php endif; ?>-->
               </TD>
            </TR>
            <?php if ($this->_tpl_vars['APerr']->hasMessage()): ?>
            <TR>
                <TD WIDTH="100%" COLSPAN="1" class="tabar">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'messages.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
               </TD>
            </TR>
            <?php endif; ?>
            <TR>
                <TD WIDTH="100%" COLSPAN="1" class="tabox">
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