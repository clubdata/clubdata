<?php /* Smarty version 2.6.10, created on 2015-06-14 10:00:09
         compiled from messages.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'messages.inc.tpl', 6, false),)), $this); ?>
<?php $this->assign('errLvl', $this->_tpl_vars['APerr']->getErrorLvlTxt());  $this->assign('errText', $this->_tpl_vars['APerr']->getMessages()); ?>
<div class="vMain">
   <div class="<?php echo $this->_tpl_vars['errLvl']; ?>
 messages">
        <div class="header">
        <?php echo ((is_array($_tmp=$this->_tpl_vars['errLvl'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

        </div>
        <div class="message">
        <?php $_from = $this->_tpl_vars['errText']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aktErr']):
?>
        <?php echo $this->_tpl_vars['aktErr']; ?>
<BR>
        <?php endforeach; endif; unset($_from); ?>
        </div>
   </div>
</div>