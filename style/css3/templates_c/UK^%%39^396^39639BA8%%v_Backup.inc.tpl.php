<?php /* Smarty version 2.6.10, created on 2015-06-14 10:53:13
         compiled from admin/v_Backup.inc.tpl */ ?>
<div class="vMain">
<div class="row">
  <div class="col_1_1">
    <div class="description">
    <?php echo $this->_tpl_vars['mainform']; ?>

    </div>
  </div>
</div>
<?php if (is_object ( $this->_tpl_vars['listObj'] )): ?>
<div class="row">
  <div class="col_1_1">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'list.inc.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
</div>
<?php endif; ?>