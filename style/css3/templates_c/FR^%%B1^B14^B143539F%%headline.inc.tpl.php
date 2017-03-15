<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:31
         compiled from headline.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'headline.inc.tpl', 1, false),array('modifier', 'count', 'headline.inc.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_math(array('equation' => "100/numCols",'numCols' => count($this->_tpl_vars['headArr']),'assign' => 'width'), $this);?>


<?php if (count ( $this->_tpl_vars['headArr'] ) > 1): ?>
    <?php $this->assign('class', 'multi');  else: ?>
    <?php $this->assign('class', 'single');  endif; ?>

<table width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ul.png" height="10" width="10" border="0"></TD>
    <td class="light_border_upper"></td>
    <td><img src="style/newstyle/images/light_corner_ur.png" width="13" border="0"></td>
</TR>
<TR>
<TD class="light_border_left"></TD>
<TD>
<table class='head' cellspacing="0" cellpadding="0" border='0' id='head-table'>
<TR>
<?php $_from = $this->_tpl_vars['headArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['headers'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['headers']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['headerItem']):
        $this->_foreach['headers']['iteration']++;
?>
    <?php $this->assign('align', 'CENTER'); ?>
    <?php if (( count ( $this->_tpl_vars['headArr'] ) > 1 && ($this->_foreach['headers']['iteration'] <= 1) )): ?>
        <?php $this->assign('align', 'LEFT'); ?>
    <?php elseif (( count ( $this->_tpl_vars['headArr'] ) > 1 && ($this->_foreach['headers']['iteration'] == $this->_foreach['headers']['total']) )): ?>
        <?php $this->assign('align', 'RIGHT'); ?>
    <?php endif; ?>
    <TD CLASS="<?php echo $this->_tpl_vars['class']; ?>
" WIDTH="<?php echo $this->_tpl_vars['width']; ?>
%" ALIGN="<?php echo $this->_tpl_vars['align']; ?>
"><?php echo $this->_tpl_vars['headerItem']; ?>
</TD>
<?php endforeach; endif; unset($_from); ?>
</TR>
</TABLE>
</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>

