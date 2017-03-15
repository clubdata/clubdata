<?php /* Smarty version 2.6.10, created on 2015-06-14 22:41:03
         compiled from queries/v_AddressLists.inc.tpl */ ?>
<div class="vMain">
    <?php echo $this->_tpl_vars['adressSelection']; ?>

<?php if (! empty ( $this->_tpl_vars['AddressList'] )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'list.inc.tpl', 'smarty_include_vars' => array('listObj' => $this->_tpl_vars['AddressList'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</div>