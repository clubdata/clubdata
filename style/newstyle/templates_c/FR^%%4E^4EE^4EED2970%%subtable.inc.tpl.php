<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:52
         compiled from formsgeneration/subtable.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'formsgeneration/subtable.inc.tpl', 1, false),array('insert', 'formadddatapart', 'formsgeneration/subtable.inc.tpl', 5, false),array('insert', 'formaddinputpart', 'formsgeneration/subtable.inc.tpl', 7, false),array('insert', 'formaddlabelpart', 'formsgeneration/subtable.inc.tpl', 7, false),)), $this); ?>
<?php ob_start();  echo smarty_function_counter(array('assign' => 'checkCount','start' => 0), $this);?>

<input type='HIDDEN' NAME='SET<?php echo $this->_tpl_vars['tableName']; ?>
' VALUE='1'>
<table class=invisible width='100%' border='1' bgcolor='#FFFFF0'>
<tr>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  $_from = $this->_tpl_vars['form']->inputs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['edit']):
 ob_start(); ?>
        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if ($this->_tpl_vars['edit']['SubForm'] == ($this->_tpl_vars['tableName']) && ( substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != 'p_' )):  ob_start(); ?>
            <td style='width: 25%' nowrap><?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start();  $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddlabelpart', 'for' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?></td>
            <?php echo smarty_function_counter(array(), $this);?>

        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['checkCount'] > 0 && $this->_tpl_vars['checkCount'] % 4 == 0 )):  ob_start(); ?>
            </tr><TR>
        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endforeach; endif; unset($_from);  ob_start(); ?>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['checkCount'] % 4 != 0 )):  ob_start(); ?>
        <td style='width: 25%' nowrap>&nbsp;</td>
        <?php echo smarty_function_counter(array(), $this);?>

    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['checkCount'] % 4 != 0 )):  ob_start(); ?>
        <td style='width: 25%' nowrap>&nbsp;</td>
        <?php echo smarty_function_counter(array(), $this);?>

    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['checkCount'] % 4 != 0 )):  ob_start(); ?>
        <td style='width: 25%' nowrap>&nbsp;</td>
        <?php echo smarty_function_counter(array(), $this);?>

    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
</TR>
</table>
<?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this); ?>