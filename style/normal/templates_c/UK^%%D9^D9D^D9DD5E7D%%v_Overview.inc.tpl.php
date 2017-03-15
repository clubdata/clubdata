<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:14
         compiled from members/v_Overview.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getDescription', 'members/v_Overview.inc.tpl', 11, false),array('modifier', 'default', 'members/v_Overview.inc.tpl', 11, false),array('modifier', 'regex_replace', 'members/v_Overview.inc.tpl', 170, false),array('modifier', 'date_format', 'members/v_Overview.inc.tpl', 170, false),)), $this); ?>
<TABLE CLASS='listTable' BORDER='0' WIDTH='100%'>
<TR>
<TH CLASS='title' COLSPAN='1' WIDTH="50%">Privat</TH>
<TH CLASS='title' COLSPAN='1' WIDTH="50%">Firm</TH>
</TR>
<TR>
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Privat']['Salutation_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Salutation'), $this);?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Lastname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
,&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Firstname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Address'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['ZipCode'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Town'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Privat']['Country_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Country'), $this);?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        &nbsp;
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Tel:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Telephone'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Fax:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Fax'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Email:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Email'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    HTML:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Privat']['Html'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
</TABLE>
</TD>
<!-- FIRM -->
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['FirmName_ml'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['FirmDepartment'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['Address'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['ZipCode'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['Town'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Firm']['Country_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Country'), $this);?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        &nbsp;
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Tel:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['Telephone'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Fax:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['Fax'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Email:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['Email'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    HTML:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Firm']['Html'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
</TABLE>
</TD>
</TR>
<!-- Memberinformation -->
<TR>
<TH CLASS='title' COLSPAN='2' WIDTH="100%">Memberinfo</TH>
</TR>
<TR>
<TD COLSPAN="1" >
<TABLE WIDTH="100%">
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Membertype_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Membertype'), $this);?>

    </TD>
</TR>
<?php if (! empty ( $this->_tpl_vars['Memberinfo']['MainMemberID'] )): ?>
<TR>
    <TD CLASS='Description'>
    Full member
    </TD>
    <TD CLASS='Daten'>
    <a href='<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=members&view=Overview&MemberID=<?php echo $this->_tpl_vars['Memberinfo']['MainMemberID']['Adr_MemberID']; ?>
'>
    <?php echo $this->_tpl_vars['Memberinfo']['MainMemberID']['Lastname']; ?>
, <?php echo $this->_tpl_vars['Memberinfo']['MainMemberID']['Firstname']; ?>

    </a>
    </TD>
</TR>
<?php endif; ?>
<TR>
    <TD CLASS='Description'>
    Entrance
    </TD>
    <TD CLASS='Daten'>
    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Memberinfo']['Entrydate'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/0000-00-00/", "") : smarty_modifier_regex_replace($_tmp, "/0000-00-00/", "")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Information sharing
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['InfoGiveOut_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'InfoGiveOut'), $this);?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Information in WWW
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['InfoWWW_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'InfoWWW'), $this);?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Preferred language
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Language_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Language'), $this);?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Date of birth
    </TD>
    <TD CLASS='Daten'>
    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Memberinfo']['Birthdate'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/0000-00-00/", "") : smarty_modifier_regex_replace($_tmp, "/0000-00-00/", "")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>

    </TD>
</TR>
<?php if (! empty ( $this->_tpl_vars['Memberinfo']['Remarks'] )): ?>
<TR>
    <TD CLASS='Description'>
    Remarks:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Remarks'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<?php endif;  if (! empty ( $this->_tpl_vars['Memberinfo']['Selection'] )): ?>
<TR>
    <TD CLASS='Description'>
    Selection:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Selection'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<?php endif; ?>
</TABLE>
</TD>
<TD WIDTH="50%" VALIGN=TOP>
<TABLE WIDTH="100%">
<?php if (! empty ( $this->_tpl_vars['Memberinfo']['associatedMembers'] )): ?>
    <?php $_from = $this->_tpl_vars['Memberinfo']['associatedMembers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['assocLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['assocLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['assoc']):
        $this->_foreach['assocLoop']['iteration']++;
?>
    <TR>
        <TD CLASS='Description'>
        <?php if (($this->_foreach['assocLoop']['iteration'] <= 1)): ?>
        Associated members:
        <?php else: ?>
        &nbsp;
        <?php endif; ?>
        </TD>
        <TD CLASS='Daten'>
            <a href='<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=members&view=Overview&MemberID=<?php echo $this->_tpl_vars['assoc']['MemberID']; ?>
'>
            <?php echo $this->_tpl_vars['assoc']['Lastname']; ?>
, <?php echo $this->_tpl_vars['assoc']['Firstname']; ?>

            </a>
        </TD>
    </TR>
    <?php endforeach; endif; unset($_from);  endif;  $this->assign('attribShown', false);  $_from = $this->_tpl_vars['Attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['assocLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['assocLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['assoc']):
        $this->_foreach['assocLoop']['iteration']++;
 if (! empty ( $this->_tpl_vars['assoc'] )): ?>
<TR>
    <TD CLASS='Description'>
    <?php if ($this->_tpl_vars['attribShown'] == false): ?>
    Attributes:
    <?php $this->assign('attribShown', true); ?>
    <?php else: ?>
    &nbsp;
    <?php endif; ?>
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => $this->_tpl_vars['assoc'],'table' => 'Attributes'), $this);?>

    </TD>
</TR>
<?php endif;  endforeach; endif; unset($_from); ?>
</TABLE>
</TD>
</TR>
</TABLE>