<?php /* Smarty version 2.6.10, created on 2015-06-15 00:12:03
         compiled from formsgeneration/columns.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'formsgeneration/columns.inc.tpl', 1, false),array('insert', 'formadddatapart', 'formsgeneration/columns.inc.tpl', 6, false),array('insert', 'formaddinputpart', 'formsgeneration/columns.inc.tpl', 20, false),array('insert', 'formaddlabelpart', 'formsgeneration/columns.inc.tpl', 20, false),)), $this); ?>
<?php ob_start();  echo smarty_function_counter(array('assign' => 'checkCount','start' => 0), $this);?>

<?php $this->assign('title_old', ''); ?>
<input type='HIDDEN' NAME='SETColumns' VALUE='1'>
<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
<tr>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  $_from = $this->_tpl_vars['form']->inputs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['edit']):
 ob_start(); ?>
        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( strncmp ( $this->_tpl_vars['edit']['SubForm'] , 'buttonbar' , 9 ) )): ?> <?php ob_start(); ?>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['edit']['SubForm'] != $this->_tpl_vars['title_old'] )):  ob_start(); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( ! empty ( $this->_tpl_vars['title_old'] ) )):  ob_start(); ?>
                    </tr>
                    <?php echo smarty_function_counter(array('assign' => 'checkCount','start' => 0), $this);?>

                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
                <?php $this->assign('title_old', $this->_tpl_vars['edit']['SubForm']); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (! empty ( $this->_tpl_vars['heads'][$this->_tpl_vars['title_old']] )):  ob_start(); ?>
                    <th colspan="4" class="title"><?php echo $this->_tpl_vars['heads'][$this->_tpl_vars['title_old']]; ?>
</th></tr><tr>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != '__' && substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != 'p_' )):  ob_start(); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if ($this->_tpl_vars['edit']['TYPE'] != 'hidden'):  ob_start(); ?>
                    <td style='width: 25%' nowrap><?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start();  $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddlabelpart', 'for' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?></td>
                    <?php echo smarty_function_counter(array(), $this);?>

                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['checkCount'] > 0 && $this->_tpl_vars['checkCount'] % 4 == 0 )):  ob_start(); ?>
                </tr><TR>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
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