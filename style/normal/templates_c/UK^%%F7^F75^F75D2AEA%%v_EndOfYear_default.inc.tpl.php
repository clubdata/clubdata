<?php /* Smarty version 2.6.10, created on 2008-11-22 12:57:29
         compiled from jobs/v_EndOfYear_default.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'jobs/v_EndOfYear_default.inc.tpl', 16, false),)), $this); ?>
<INPUT TYPE='HIDDEN' NAME='State' VALUE='<?php echo $this->_tpl_vars['NewState']; ?>
'>
<?php if (isset ( $this->_tpl_vars['cancelList'] )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'list.inc.tpl', 'smarty_include_vars' => array('listObj' => $this->_tpl_vars['cancelList'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif (isset ( $this->_tpl_vars['insertFees'] )):  echo $this->_tpl_vars['insertFees']; ?>

<?php elseif (isset ( $this->_tpl_vars['directDebit'] )):  echo $this->_tpl_vars['directDebit']; ?>

<?php else: ?>
<TABLE WIDTH="95%" class="listTable">
<TR>
    <TD class="Description" >New membership period</TD>
    <TD CLASS='Daten'><INPUT NAME='EOY_PERIOD' VALUE='' SIZE='4' MAXLENGTH='4'></TD>
</TR>
<TR>
    <TD class="Description" >Process cancelled memberships</TD>
    <TD CLASS='Daten'><?php echo smarty_function_html_options(array('name' => 'EOY_PROCCANCELLED','options' => $this->_tpl_vars['YesNoSelection'],'selected' => 'YES'), $this);?>
</TD>
</TR>
<TR>
    <TD class="Description" >Insert new membership fees (batch)</TD>
    <TD CLASS='Daten'><?php echo smarty_function_html_options(array('name' => 'EOY_PROCCANCELLED','options' => $this->_tpl_vars['YesNoSelection'],'selected' => 'YES'), $this);?>
</TD>
</TR>
<TR>
    <TD class="Description" >Insert payments by direct debit</TD>
    <TD CLASS='Daten'><?php echo smarty_function_html_options(array('name' => 'EOY_PROCDIRECTDEB','options' => $this->_tpl_vars['YesNoSelection'],'selected' => 'YES'), $this);?>
</TD>
</TR>
</TABLE>
<?php endif; ?>