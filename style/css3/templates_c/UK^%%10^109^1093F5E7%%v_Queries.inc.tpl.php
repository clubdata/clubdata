<?php /* Smarty version 2.6.10, created on 2015-06-21 23:30:14
         compiled from queries/v_Queries.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'queries/v_Queries.inc.tpl', 5, false),)), $this); ?>
<div class="vMain">
<div class="content equalheight">
  <?php if (( ! isMember ( ) )): ?>
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDES_PHP'])."?mod=queries&view=MemberSummary",'boxtitle' => ((is_array($_tmp='Member summary')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'QueriesMemberSummary','boxhelp' => ((is_array($_tmp='Number of members per member type')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=Statistics",'boxtitle' => ((is_array($_tmp='Statistics')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'QueriesStatistics','boxhelp' => ((is_array($_tmp="Several statistics, like number of paying members etc.")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
   <?php endif; ?>
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=AddressLists",'boxtitle' => ((is_array($_tmp='Addresslists')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'QueriesAddresslists','boxhelp' => ((is_array($_tmp="Different address lists, e.g. for public use")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
<!--   <?php if (( isMember ( ) )): ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   <?php endif; ?>
 -->
</div>
</div>