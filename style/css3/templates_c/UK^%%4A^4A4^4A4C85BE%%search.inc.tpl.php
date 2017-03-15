<?php /* Smarty version 2.6.10, created on 2015-06-18 23:02:40
         compiled from formsgeneration/search.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'formadddatapart', 'formsgeneration/search.inc.tpl', 17, false),array('insert', 'formaddlabelpart', 'formsgeneration/search.inc.tpl', 38, false),array('insert', 'formaddinputpart', 'formsgeneration/search.inc.tpl', 41, false),array('modifier', 'regex_replace', 'formsgeneration/search.inc.tpl', 25, false),array('modifier', 'cat', 'formsgeneration/search.inc.tpl', 34, false),array('modifier', 'lang', 'formsgeneration/search.inc.tpl', 59, false),)), $this); ?>
<?php ob_start();  $this->assign('title_old', ''); ?>
<div class="XXtable">
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  $_from = $this->_tpl_vars['form']->inputs; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['edit']):
 ob_start(); ?>
        <?php if (( substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != '__' && substr ( $this->_tpl_vars['schluessel'] , 0 , 2 ) != 'p_' && substr ( $this->_tpl_vars['schluessel'] , -7 ) != '_select' && substr ( $this->_tpl_vars['schluessel'] , -9 ) != '_rangeEnd' ) && strncmp ( $this->_tpl_vars['edit']['SubForm'] , 'buttonbar' , 9 )): ?> 
             <?php if (( $this->_tpl_vars['edit']['TYPE'] != 'hidden' )): ?>
                <?php $this->assign('title', ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(([^%]*)%)?.*$/", "\\2") : smarty_modifier_regex_replace($_tmp, "/(([^%]*)%)?.*$/", "\\2"))); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (( $this->_tpl_vars['title'] != $this->_tpl_vars['title_old'] )):  ob_start(); ?>
	                <div class="row">
	                  <div class="col-1-1 SectionTitle">
	                    <?php echo $this->_tpl_vars['heads'][$this->_tpl_vars['title']]; ?>
	                  </div>
	                </div>
                   <?php $this->assign('title_old', $this->_tpl_vars['title']); ?>
                <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
                <?php $this->assign('schluesselselect', ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_select') : smarty_modifier_cat($_tmp, '_select'))); ?>
                <?php $this->assign('schluesselend', ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_rangeEnd') : smarty_modifier_cat($_tmp, '_rangeEnd'))); ?>
                <div class="row">
                  <div class="col-equal Description">
                    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddlabelpart', 'for' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?>:
                  </div>
                  <div class="col-equal Description">
                    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluesselselect']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?>
                  </div>
                  <div class="col-max DataSearch">
                  <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  if (! empty ( $this->_tpl_vars['edit']['MULTIPLE'] )):  ob_start(); ?>
                  <div class="row">
                    <div class="col-auto">
                      <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?>
                    </div>
                    <div class="col-auto">
                      <div class="row-nomargin">
                        <input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(1, '<?php echo ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, "[]") : smarty_modifier_cat($_tmp, "[]")); ?>
');SetComparision('INSelection', '<?php echo $this->_tpl_vars['schluesselselect']; ?>
');" value='Select ALL'>
                      </div>
                      <div class="row-nomargin">
                        <input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(0, '<?php echo ((is_array($_tmp=$this->_tpl_vars['schluessel'])) ? $this->_run_mod_handler('cat', true, $_tmp, "[]") : smarty_modifier_cat($_tmp, "[]")); ?>
');SetComparision('INSelection', '<?php echo $this->_tpl_vars['schluesselselect']; ?>
');" value='Deselect ALL'>
                      </div>
                    </div>
                  </div>
                  <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  else:  ob_start(); ?>
                  <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluessel']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?><span style='display: none;' id='<?php echo $this->_tpl_vars['schluessel']; ?>
_rangeEnd'>&nbsp;<?php echo ((is_array($_tmp='and')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)); ?>
&nbsp;<?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formaddinputpart', 'input' => ($this->_tpl_vars['schluesselend']), 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  ob_start(); ?></span>
                  <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
                  </div>
                </div>
            <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
        <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endif;  ob_start(); ?>
    <?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this);  endforeach; endif; unset($_from);  ob_start(); ?>
</div>


<?php $this->_smarty_vars['capture']['formdata'] = ob_get_contents(); ob_end_clean();  require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'formadddatapart', 'data' => $this->_smarty_vars['capture']['formdata'])), $this); ?>