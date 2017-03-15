<?php /* Smarty version 2.6.10, created on 2015-06-14 00:58:00
         compiled from headline.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'headline.inc.tpl', 1, false),array('modifier', 'count', 'headline.inc.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_math(array('equation' => "100/numCols",'numCols' => count($this->_tpl_vars['headArr']),'assign' => 'width'), $this);?>

<?php $this->assign('cols', count($this->_tpl_vars['headArr'])); ?>

<?php if (count ( $this->_tpl_vars['headArr'] ) > 1): ?>
    <?php $this->assign('class', 'multi');  else: ?>
    <?php $this->assign('class', 'single');  endif;  $this->assign('class', "col-1-".($this->_tpl_vars['cols'])); ?>
<div class="row field-background headerline">
	<?php $_from = $this->_tpl_vars['headArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['headers'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['headers']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['headerItem']):
        $this->_foreach['headers']['iteration']++;
?>
	    <?php $this->assign('align', 'center'); ?>
	    <?php if (( count ( $this->_tpl_vars['headArr'] ) > 1 && ($this->_foreach['headers']['iteration'] <= 1) )): ?>
	        <?php $this->assign('align', 'left'); ?>
	    <?php elseif (( count ( $this->_tpl_vars['headArr'] ) > 1 && ($this->_foreach['headers']['iteration'] == $this->_foreach['headers']['total']) )): ?>
	        <?php $this->assign('align', 'right'); ?>
	    <?php endif; ?>
      <div class="<?php echo $this->_tpl_vars['class']; ?>
 <?php echo $this->_tpl_vars['align']; ?>
"><span><?php echo $this->_tpl_vars['headerItem']; ?>
</span></div>
	<?php endforeach; endif; unset($_from); ?>
</div>
