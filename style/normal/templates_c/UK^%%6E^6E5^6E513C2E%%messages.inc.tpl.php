<?php /* Smarty version 2.6.10, created on 2008-11-22 12:57:57
         compiled from messages.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'messages.inc.tpl', 6, false),)), $this); ?>
<?php $this->assign('errLvl', $this->_tpl_vars['APerr']->getErrorLvlTxt());  $this->assign('errText', $this->_tpl_vars['APerr']->getMessages()); ?>
<div class=error>
<table CLASS="<?php echo $this->_tpl_vars['errLvl']; ?>
" cellspacing="0">
<tr>
<th colspan="3" rowspan="1"><?php echo ((is_array($_tmp=$this->_tpl_vars['errLvl'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

</th>
</tr>
<tr>
<td colspan="1" rowspan="1"><br>
</td>
<td>
<?php $_from = $this->_tpl_vars['errText']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aktErr']):
 echo $this->_tpl_vars['aktErr']; ?>
<BR>
<?php endforeach; endif; unset($_from); ?>
</td>
<td colspan="1" rowspan="1"><br>
</td>
</tr>
<tr>
<td colspan="3" rowspan="1"><br>
</td>
</tr>
</table>
</div>
