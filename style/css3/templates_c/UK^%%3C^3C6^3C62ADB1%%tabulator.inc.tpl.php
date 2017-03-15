<?php /* Smarty version 2.6.10, created on 2015-06-14 20:31:14
         compiled from tabulator.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'tabulator.inc.tpl', 4, false),array('modifier', 'translate', 'tabulator.inc.tpl', 16, false),)), $this); ?>
<?php $this->assign('tabArr',  $this->_reg_objects['modClass'][0]->getTabulators(array(), $this)); $this->assign('selectedButton',  $this->_reg_objects['modClass'][0]->getCurrentView(array(), $this)); $this->assign('moduleName',  $this->_reg_objects['modClass'][0]->getModuleName(array(), $this)); if (count($this->_tpl_vars['tabArr']) > 0): ?>
<div>
<ul class="tabulatorClass clearfix">
<?php $_from = $this->_tpl_vars['tabArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tabs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tabs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['tabKey'] => $this->_tpl_vars['tabItem']):
        $this->_foreach['tabs']['iteration']++;
 if (( ! empty ( $this->_tpl_vars['tabItem'] ) )): ?>
    <?php if (( ($this->_tpl_vars['tabKey']) == ($this->_tpl_vars['selectedButton']) )): ?>
        <?php $this->assign('sel', 'checked'); ?>
    <?php else: ?>
        <?php $this->assign('sel', ""); ?>
    <?php endif; ?>
    <li>
	    <input type="radio" name="tabulators" id="tab-<?php echo $this->_tpl_vars['tabKey']; ?>
" <?php echo $this->_tpl_vars['sel']; ?>
 onclick="doAction('<?php echo $this->_tpl_vars['moduleName']; ?>
','<?php echo $this->_tpl_vars['tabKey']; ?>
');">
	    <label for="tab-<?php echo $this->_tpl_vars['tabKey']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['tabItem'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
    </li>
<?php endif;  endforeach; endif; unset($_from); ?>
</ul>
</div>
<?php endif; ?>