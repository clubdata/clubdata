<?php /* Smarty version 2.6.10, created on 2015-06-15 00:11:56
         compiled from settings/v_Settings.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'settings/v_Settings.inc.tpl', 3, false),)), $this); ?>
<div class="vMain">
<div class="content equalheight">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=settings&view=Columns",'boxtitle' => ((is_array($_tmp='Select Columns')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxhelp' => ((is_array($_tmp='Select columns which will be displayed in search results')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=settings&view=Personal",'boxtitle' => ((is_array($_tmp='Personal settings')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxhelp' => ((is_array($_tmp="Change your personal settings here. Configure the look and feel of Clubdata for your needs")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>