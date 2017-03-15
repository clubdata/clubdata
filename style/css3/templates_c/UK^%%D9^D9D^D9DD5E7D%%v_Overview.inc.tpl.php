<?php /* Smarty version 2.6.10, created on 2015-06-21 20:07:22
         compiled from members/v_Overview.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'explode', 'members/v_Overview.inc.tpl', 23, false),array('modifier', 'default', 'members/v_Overview.inc.tpl', 26, false),array('modifier', 'lang', 'members/v_Overview.inc.tpl', 51, false),array('modifier', 'regex_replace', 'members/v_Overview.inc.tpl', 90, false),array('modifier', 'date_format', 'members/v_Overview.inc.tpl', 90, false),array('function', 'getDescription', 'members/v_Overview.inc.tpl', 26, false),)), $this); ?>
<div class="vMain">
	<div class="content equalheight" >
  <?php $_from = $this->_tpl_vars['Addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['addressName'] => $this->_tpl_vars['address']):
?>
    <?php ob_start(); ?>
			<div class="row-smallmargin">
			  <div class="col-1-2 Daten Address">
			  
        <?php $this->assign('showNameFlg', 1); ?>          
        <?php $this->assign('showLocationFlg', 1); ?>          
        <?php $_from = $this->_tpl_vars['address']['fieldArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
                    <?php $this->assign('addressFieldArr', ((is_array($_tmp='_')) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['wert']) : explode($_tmp, $this->_tpl_vars['wert']))); ?>
          <?php if (count ( $this->_tpl_vars['addressFieldArr'] ) > 1 && $this->_tpl_vars['addressFieldArr'][1] == 'ref'): ?> 
          <div>
            <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['address']['address'][$this->_tpl_vars['wert']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => $this->_tpl_vars['addressFieldArr'][0]), $this);?>

          </div>
          <?php elseif ($this->_tpl_vars['wert'] == 'Title' || $this->_tpl_vars['wert'] == 'Firstname' || $this->_tpl_vars['wert'] == 'Lastname'): ?>
          <div>
            <?php if ($this->_tpl_vars['showNameFlg'] == 1): ?>
		          <?php if (! empty ( $this->_tpl_vars['address']['address']['Title'] )):  echo ((is_array($_tmp=@$this->_tpl_vars['address']['address']['Title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php endif; ?>
		          <?php if (! empty ( $this->_tpl_vars['address']['address']['Lastname'] )):  echo ((is_array($_tmp=@$this->_tpl_vars['address']['address']['Lastname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
,&nbsp;<?php endif; ?>
		          <?php if (! empty ( $this->_tpl_vars['address']['address']['Firstname'] )):  echo ((is_array($_tmp=@$this->_tpl_vars['address']['address']['Firstname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, ''));  endif; ?>
	            <?php $this->assign('showNameFlg', 0); ?>
	          <?php endif; ?>          
          </div>
          <?php elseif ($this->_tpl_vars['wert'] == 'ZipCode' || $this->_tpl_vars['wert'] == 'Town'): ?>
          <div>
            <?php if ($this->_tpl_vars['showLocationFlg'] == 1): ?>
              <?php if (! empty ( $this->_tpl_vars['address']['address']['ZipCode'] )):  echo ((is_array($_tmp=@$this->_tpl_vars['address']['address']['ZipCode'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php endif; ?>
              <?php if (! empty ( $this->_tpl_vars['address']['address']['Town'] )):  echo ((is_array($_tmp=@$this->_tpl_vars['address']['address']['Town'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
<br><?php endif; ?>
              <?php $this->assign('showLocationFlg', 0); ?>
            <?php endif; ?>          
          </div>
          <?php elseif ($this->_tpl_vars['wert'] == 'Address' || $this->_tpl_vars['wert'] == 'FirmName_ml' || $this->_tpl_vars['wert'] == 'FirmDepartment'): ?>
          <div>
            <?php echo ((is_array($_tmp=@$this->_tpl_vars['address']['address'][$this->_tpl_vars['wert']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

          </div>
          <?php else: ?>
            <div class="row-smallmargin">
	            <div class="col-1-3"><?php echo ((is_array($_tmp=$this->_tpl_vars['addressFieldArr'][0])) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)); ?>
:</div>
	            <div class="col-2-3"><?php echo ((is_array($_tmp=@$this->_tpl_vars['address']['address'][$this->_tpl_vars['wert']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</div>
            </div>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
			  
			  </div>
			</div>
		<?php $this->_smarty_vars['capture']['captureField'] = ob_get_contents(); ob_end_clean(); ?>
  
    <?php $this->assign('addressId', $this->_tpl_vars['address']['id']); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "javascript:doAction('members','Addresses_".($this->_tpl_vars['addressId'])."')",'boxtitle' => ((is_array($_tmp=$this->_tpl_vars['addressName'])) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => "Members_".($this->_tpl_vars['addressId']),'boxhelp' => $this->_smarty_vars['capture']['captureField'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
  <?php endforeach; endif; unset($_from); ?>

		<!-- Memberinformation -->
		<?php ob_start(); ?>
		  <div class="Daten">
		      <div class="row-smallmargin">
		        <div class="col-1-3">Membertype:</div>
		        <div class="col-2-3"><?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Membertype_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Membertype'), $this);?>
</div>
		      </div>
		      <?php if (! empty ( $this->_tpl_vars['Memberinfo']['MainMemberID'] )): ?>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Full member:</div>
		        <div class="col-2-3"><a
		              href='<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=members&view=Overview&MemberID=<?php echo $this->_tpl_vars['Memberinfo']['MainMemberID']['Adr_MemberID']; ?>
'>
		                <?php echo $this->_tpl_vars['Memberinfo']['MainMemberID']['Lastname']; ?>
,
		                <?php echo $this->_tpl_vars['Memberinfo']['MainMemberID']['Firstname']; ?>
 </a></div>
		      </div>
		      <?php endif; ?>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Entrance:</div>
		        <div class="col-2-3"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Memberinfo']['Entrydate'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/0000-00-00/", "") : smarty_modifier_regex_replace($_tmp, "/0000-00-00/", "")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Information sharing:</div>
		        <div class="col-2-3"><?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['InfoGiveOut_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'InfoGiveOut'), $this);?>
</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Information in WWW:</div>
		        <div class="col-2-3"><?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['InfoWWW_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'InfoWWW'), $this);?>
</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Preferred language:</div>
		        <div class="col-2-3"><?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Language_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Language'), $this);?>
</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Date of birth:</div>
		        <div class="col-2-3"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Memberinfo']['Birthdate'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/0000-00-00/", "") : smarty_modifier_regex_replace($_tmp, "/0000-00-00/", "")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</div>
		      </div>
		      <?php if (! empty ( $this->_tpl_vars['Memberinfo']['Remarks'] )): ?>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Remarks:</div>
		        <div class="col-2-3"><?php echo ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Remarks'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</div>
		      </div>
		      <?php endif; ?>
		      <?php if (! empty ( $this->_tpl_vars['Memberinfo']['Selection'] )): ?>
		      <div class="row-smallmargin">
		        <div class="col-1-3">Selection:</div>
		        <div class="col-2-3"><?php echo ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Selection'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</div>
		      </div>
		      <?php endif; ?>
		  </div>
		<?php $this->_smarty_vars['capture']['captureField'] = ob_get_contents(); ob_end_clean(); ?>
		
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "javascript:doAction('members','Memberinfo')",'boxtitle' => ((is_array($_tmp='Memberinfo')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'MembersMemberinfo','boxhelp' => $this->_smarty_vars['capture']['captureField'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   
		<?php ob_start(); ?>
			 <div class="Daten">
			  <?php $_from = $this->_tpl_vars['Memberinfo']['associatedMembers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['assocLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['assocLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['assoc']):
        $this->_foreach['assocLoop']['iteration']++;
?>
			 <div class="row-smallmargin">
			    <div class="col-1-3"><?php if (($this->_foreach['assocLoop']['iteration'] <= 1)): ?>
			             Associated members: <?php else: ?> &nbsp; <?php endif; ?></div>
			    <div class="col-2-3"><a
			             href='<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=members&view=Overview&MemberID=<?php echo $this->_tpl_vars['assoc']['MemberID']; ?>
'>
			               <?php echo $this->_tpl_vars['assoc']['Lastname']; ?>
, <?php echo $this->_tpl_vars['assoc']['Firstname']; ?>
 </a></div>
			 </div>
			<?php endforeach; endif; unset($_from); ?>
			<?php $this->assign('attribShown', false); ?>
			<?php $_from = $this->_tpl_vars['Attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['assocLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['assocLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['assoc']):
        $this->_foreach['assocLoop']['iteration']++;
?>
			<?php if (! empty ( $this->_tpl_vars['assoc'] )): ?>
			 <div class="row-smallmargin">
			    <div class="col-1-3"><?php if ($this->_tpl_vars['attribShown'] == false): ?>
			             Attributes: <?php $this->assign('attribShown', true); ?> <?php else: ?> &nbsp; <?php endif; ?></div>
			    <div class="col-2-3"><?php echo smarty_function_getDescription(array('id' => $this->_tpl_vars['assoc'],'table' => 'Attributes'), $this);?>
</div>
			 </div>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			</div>
		<?php $this->_smarty_vars['capture']['captureField'] = ob_get_contents(); ob_end_clean(); ?>

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "javascript:doAction('members','Memberinfo')",'boxtitle' => ((is_array($_tmp='Additional Memberinfo')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxid' => 'MembersAdditionalMemberinfo','boxhelp' => $this->_smarty_vars['capture']['captureField'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
</div>