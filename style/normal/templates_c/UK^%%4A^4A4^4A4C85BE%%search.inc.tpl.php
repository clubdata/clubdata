<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:19
         compiled from formsgeneration/search.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'formadddatapart', 'formsgeneration/search.inc.tpl', 18, false),array('insert', 'formaddlabelpart', 'formsgeneration/search.inc.tpl', 33, false),array('insert', 'formaddinputpart', 'formsgeneration/search.inc.tpl', 34, false),array('modifier', 'regex_replace', 'formsgeneration/search.inc.tpl', 25, false),array('modifier', 'cat', 'formsgeneration/search.inc.tpl', 31, false),)), $this); ?>
<?php ob_start();  $this->assign('title_old', ''); ?>
<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
    <COLGROUP><COL WIDTH='1%'><COL WIDTH='1%'><COL WIDTH='99%'></COLGROUP>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  $_from = $this->_tpl_vars['form']->inputs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['edit']):
 ob_start(); ?>
        <?php if (( substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != '__' && substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != 'p_' && substr ( $this->_tpl_vars['schluessel'] , -7 ) != '_select' ) && strncmp ( $this->_tpl_vars['edit']['SubForm'] , 'buttonbar' , 9 )): ?> 
             <?php if (( $this->_tpl_vars['edit']['TYPE'] != 'hidden' )): ?>
                <?php $this->assign('title', ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(([^%]*)%)?.*$/", "\\2") : smarty_modifier_regex_replace($_tmp, "/(([^%]*)%)?.*$/", "\\2"))); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['title'] != $this->_tpl_vars['title_old'] ) && ! empty ( $this->_tpl_vars['heads'][$this->_tpl_vars['title']] )):  ob_start(); ?>
                    <tr><td colspan="3" class="Daten"><?php echo $this->_tpl_vars['heads'][$this->_tpl_vars['title']]; ?>
</td></tr>
                    
                    <?php $this->assign('title_old', $this->_tpl_vars['title']); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
                <?php $this->assign('schluesselselect', ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_select') : smarty_modifier_cat($_tmp, '_select'))); ?>
                <tr>
                    <TD class="Description"><?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddlabelpart', 'for' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?>:</TD>
                    <td class="Description"><?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluesselselect']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?></TD>
                    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (! empty ( $this->_tpl_vars['edit']['MULTIPLE'] )):  ob_start(); ?>
                    <td class="DATA">
                    <TABLE><TR>
                        <TD rowspan="2"><?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?></TD>
                        <TD><input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(1, '<?php echo ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, "[]") : smarty_modifier_cat($_tmp, "[]")); ?>
');SetComparision('INSelection', '<?php echo $this->_tpl_vars['schluesselselect']; ?>
');" value='Select ALL'></TD>
                    </TR>
                    <TR><TD><input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(0, '<?php echo ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, "[]") : smarty_modifier_cat($_tmp, "[]")); ?>
');SetComparision('INSelection', '<?php echo $this->_tpl_vars['schluesselselect']; ?>
');" value='Deselect ALL'></TD></TR>
                    </TABLE>
                    </td>
                    <td class="Data"></td>
                    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  else:  ob_start(); ?>
                    <td class="Data"><?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?></td>
                    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
                </tr>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endforeach; endif; unset($_from);  ob_start(); ?>
</TABLE>


<?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this); ?>