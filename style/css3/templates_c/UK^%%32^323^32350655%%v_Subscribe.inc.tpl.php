<?php /* Smarty version 2.6.10, created on 2015-06-18 20:44:47
         compiled from conferences/v_Subscribe.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'conferences/v_Subscribe.inc.tpl', 4, false),array('function', 'html_options', 'conferences/v_Subscribe.inc.tpl', 8, false),)), $this); ?>
<div class="vMain">
	<div class="row-smallmargin">
		<div class="col-equal Description">
		    <?php echo ((is_array($_tmp='Conference to subscribe')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)); ?>
:
		    <input type="hidden" name="SubscriptionID" value="<?php echo $this->_tpl_vars['SubscriptionID']; ?>
">
		</div>
		<div class="col-equal Data">
		    <?php echo smarty_function_html_options(array('id' => 'ConferenceID','name' => 'ConferenceID','options' => $this->_tpl_vars['subscription'],'selected' => $this->_tpl_vars['subscriptionSelected']), $this);?>

		</div>
	</div>
	<div class="row-smallmargin">
		<div class="col-equal Description">
		    <?php echo ((is_array($_tmp='Number of participants')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)); ?>
:
		    <input type="hidden" name="SubscriptionID" value="<?php echo $this->_tpl_vars['SubscriptionID']; ?>
">
		</div>
		<div class="col-equal Data">
      <input id="numPart" type="text" onkeydown="changeColorOnKey(this)" onblur="changeColorIfChanged(this, '')" maxlength="50" value="<?php echo $this->_tpl_vars['numPersons']; ?>
" name="numPart"/>
		</div>
	</div>
</div>