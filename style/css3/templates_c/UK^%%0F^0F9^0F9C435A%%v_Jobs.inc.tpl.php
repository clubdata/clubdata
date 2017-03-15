<?php /* Smarty version 2.6.10, created on 2015-06-21 23:30:11
         compiled from jobs/v_Jobs.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'jobs/v_Jobs.inc.tpl', 4, false),)), $this); ?>
<div class="vMain">
<div class="content equalheight">
  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Payments",'boxtitle' => ((is_array($_tmp='Search for payments')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'JobsSearchForPayments','boxhelp' => ((is_array($_tmp='Search for payments and displays related members')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Fees' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Fees",'boxtitle' => ((is_array($_tmp='Search for fees')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'JobsSearchForFees','boxhelp' => ((is_array($_tmp='Search for fees and display related members')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=jobs&view=EndOfYear",'boxtitle' => ((is_array($_tmp='End of Year Updates')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'JobsEndOfYearUpdates','boxhelp' => ((is_array($_tmp="Tasks which must run at the end of each year, like processing canceled members or inserting new fees")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   <?php endif; ?>
</div>
</div>