<?php /* Smarty version 2.6.10, created on 2015-06-21 23:28:40
         compiled from search/v_Search.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'search/v_Search.inc.tpl', 4, false),)), $this); ?>
<div class="vMain">
<div class="content equalheight">
    <?php if (( getUserType ( 'Create' , 'Email' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Email",'boxtitle' => ((is_array($_tmp='Send email')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'SearchSendEmail','boxhelp' => ((is_array($_tmp='Select members which like to receive emails and send emails to them')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (( getUserType ( 'Create' , 'Infoletter' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Infoletter",'boxtitle' => ((is_array($_tmp='Send Infoletter')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'SearchSendInfoletter','boxhelp' => ((is_array($_tmp='Select members which do want receive infos per letter and send infoletter to them')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Invoice",'boxtitle' => ((is_array($_tmp='Send Invoice')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'SearchSendInvoice','boxhelp' => ((is_array($_tmp='Select members which adopted for invoices and generate database for massletter')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
     <?php endif; ?>
</div>
</div>