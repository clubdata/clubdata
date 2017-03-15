<?php /* Smarty version 2.6.10, created on 2015-06-21 23:30:42
         compiled from admin/v_Admin.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'admin/v_Admin.inc.tpl', 3, false),)), $this); ?>
<div class="vMain">
	<div class="content equalheight">
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Users",'boxtitle' => ((is_array($_tmp='Users')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'AdminUsers','boxhelp' => ((is_array($_tmp='Administer Users and their rights')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Configuration",'boxtitle' => ((is_array($_tmp='Configuration')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'AdminConfiguration','boxhelp' => ((is_array($_tmp='Change general configuration settings')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Database",'boxtitle' => ((is_array($_tmp='Database')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'AdminDatabase','boxhelp' => ((is_array($_tmp="Configure misceleanous database tables, line salutations, member types, etc.")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Log",'boxtitle' => ((is_array($_tmp='Log')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'AdminLog','boxhelp' => ((is_array($_tmp='Show log entries')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Backup",'boxtitle' => ((is_array($_tmp='Backup')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'AdminBackup','boxhelp' => ((is_array($_tmp='Backup clubdata database')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
</div>