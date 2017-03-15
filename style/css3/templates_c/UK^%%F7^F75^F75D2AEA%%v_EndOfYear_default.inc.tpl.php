<?php /* Smarty version 2.6.10, created on 2015-06-14 18:26:59
         compiled from jobs/v_EndOfYear_default.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'jobs/v_EndOfYear_default.inc.tpl', 18, false),)), $this); ?>
<div class="vMain">
<input type='hidden' name='State' value='<?php echo $this->_tpl_vars['NewState']; ?>
'>
<?php if (isset ( $this->_tpl_vars['cancelList'] )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'list.inc.tpl', 'smarty_include_vars' => array('listObj' => $this->_tpl_vars['cancelList'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif (isset ( $this->_tpl_vars['insertFees'] )):  echo $this->_tpl_vars['insertFees']; ?>

<?php elseif (isset ( $this->_tpl_vars['directDebit'] )):  echo $this->_tpl_vars['directDebit']; ?>

<?php else: ?>
  <div class="table">
	  <div class="tablerow">
	    <div class="tablecol-content Description">New membership period</div>
	    <div class="tablecol-max Daten"><input name='EOY_PERIOD' value='' size='4' maxlength='4'></div>
	  </div>
	  
	  <div class="tablerow">
	    <div class="tablecol-content Description">Process cancelled memberships</div>
	    <div class="tablecol-max Daten"><?php echo smarty_function_html_options(array('name' => 'EOY_PROCCANCELLED','options' => $this->_tpl_vars['YesNoSelection'],'selected' => 'YES'), $this);?>
</div>
	  </div>
	
	  <div class="tablerow">
	    <div class="tablecol-content Description">Insert new membership fees (batch)</div>
	    <div class="tablecol-max Daten"><?php echo smarty_function_html_options(array('name' => 'EOY_PROCFEES','options' => $this->_tpl_vars['YesNoSelection'],'selected' => 'YES'), $this);?>
</div>
	  </div>
	
	  <div class="tablerow">
	    <div class="tablecol-content Description">Insert payments by direct debit</div>
	    <div class="tablecol-max Daten"><?php echo smarty_function_html_options(array('name' => 'EOY_PROCDIRECTDEB','options' => $this->_tpl_vars['YesNoSelection'],'selected' => 'YES'), $this);?>
</div>
	  </div>
	</div>
</div>
<?php endif; ?>