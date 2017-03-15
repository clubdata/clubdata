<?php /* Smarty version 2.6.10, created on 2015-06-21 23:31:30
         compiled from conferences/v_Conferences.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'conferences/v_Conferences.inc.tpl', 4, false),)), $this); ?>
<div class="vMain">
<div class="content equalheight">
    <?php if (( getUserType ( VIEW , 'Conferences' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDES_PHP'])."?mod=conferences&view=List&InitView=1",'boxtitle' => ((is_array($_tmp='List of Conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'ConferencesListOfConferences','boxhelp' => ((is_array($_tmp='List all conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (( getUserType ( VIEW , 'Conferences' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Conferences",'boxtitle' => ((is_array($_tmp='Search for conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'ConferencesSearchForConferences','boxhelp' => ((is_array($_tmp='Search for conferences by any column')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (( getUserType ( INSERT , 'Conferences' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=conferences&view=Add",'boxtitle' => ((is_array($_tmp='Add a new conference')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'ConferencesNewConference','boxhelp' => ((is_array($_tmp='Here you can add a new conference')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
</div>
</div>