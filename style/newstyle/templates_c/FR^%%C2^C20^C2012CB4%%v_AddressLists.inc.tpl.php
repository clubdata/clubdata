<?php /* Smarty version 2.6.10, created on 2011-01-05 22:55:12
         compiled from queries/v_AddressLists.inc.tpl */ ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
    <?php echo $this->_tpl_vars['adressSelection']; ?>

<?php if (! empty ( $this->_tpl_vars['AddressList'] )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'list.inc.tpl', 'smarty_include_vars' => array('listObj' => $this->_tpl_vars['AddressList'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>