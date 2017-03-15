<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:07
         compiled from tabulator.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', 'tabulator.inc.tpl', 16, false),array('modifier', 'cat', 'tabulator.inc.tpl', 16, false),array('modifier', 'translate', 'tabulator.inc.tpl', 20, false),)), $this); ?>
<?php $this->assign('tabArr',  $this->_reg_objects['modClass'][0]->getTabulators(array(), $this)); $this->assign('selectedButton',  $this->_reg_objects['modClass'][0]->getCurrentView(array(), $this)); $this->assign('moduleName',  $this->_reg_objects['modClass'][0]->getModuleName(array(), $this));?>
<table class="tabulator" border="0" cellpadding="0" cellspacing="0">
<tr>
<?php $_from = $this->_tpl_vars['tabArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tabs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tabs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['tabKey'] => $this->_tpl_vars['tabItem']):
        $this->_foreach['tabs']['iteration']++;
 if (( ! empty ( $this->_tpl_vars['tabItem'] ) )): ?>
    <?php if (( ($this->_tpl_vars['tabKey']) == ($this->_tpl_vars['selectedButton']) )): ?>
        <?php $this->assign('class', 'tabon'); ?>
        <?php $this->assign('sel', 'Selected'); ?>
    <?php else: ?>
        <?php $this->assign('class', 'taboff'); ?>
        <?php $this->assign('sel', ""); ?>
    <?php endif; ?>
    <td height="28" valign="middle" width="3">
    <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/tab") : smarty_modifier_cat($_tmp, "images/tab")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['sel']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['sel'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "Left.png") : smarty_modifier_cat($_tmp, "Left.png"))), $this);?>

    </td>
    <td valign="middle" nowrap="nowrap"
        background='<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/tab") : smarty_modifier_cat($_tmp, "images/tab")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['sel']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['sel'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "Bg.png") : smarty_modifier_cat($_tmp, "Bg.png")); ?>
'>&nbsp;
        <!-- a href="javascript:doSubmit('<?php echo $this->_tpl_vars['moduleName']; ?>
','<?php echo $this->_tpl_vars['tabKey']; ?>
');"><?php echo ((is_array($_tmp=$this->_tpl_vars['tabItem'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</A -->
        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=<?php echo $this->_tpl_vars['moduleName']; ?>
&view=<?php echo $this->_tpl_vars['tabKey']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['tabItem'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</A>
        &nbsp;
    </TD>
    <td valign="middle" width="3">
    <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/tab") : smarty_modifier_cat($_tmp, "images/tab")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['sel']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['sel'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "Right.png") : smarty_modifier_cat($_tmp, "Right.png"))), $this);?>

    </td>
    <td width="3" class="tabsp">
        &nbsp;
    </td>
<?php endif;  endforeach; endif; unset($_from); ?>
</tr>
</table>