<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:48
         compiled from members/v_Overview.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getDescription', 'members/v_Overview.inc.tpl', 15, false),array('function', 'image_path', 'members/v_Overview.inc.tpl', 22, false),array('modifier', 'default', 'members/v_Overview.inc.tpl', 15, false),array('modifier', 'regex_replace', 'members/v_Overview.inc.tpl', 201, false),array('modifier', 'date_format', 'members/v_Overview.inc.tpl', 201, false),)), $this); ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
<TABLE CLASS='listTable' BORDER='0' WIDTH='100%'>
<TR>
<TH CLASS='title' COLSPAN='1' WIDTH="50%">Privé</TH>
<TH CLASS='title' COLSPAN='1' WIDTH="50%">Entreprise</TH>
</TR>
<TR>
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Salutation_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Salutation'), $this);?>

    </TD>
    <TD rowspan="9" style="text-align:right">
    <?php $_from = $this->_tpl_vars['LeftSide']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['picture']):
?>
      <?php if (( substr ( $this->_tpl_vars['schluessel'] , -5 ) == '_link' && ! empty ( $this->_tpl_vars['picture'] ) )): ?>
        <?php if (( substr ( $this->_tpl_vars['picture'] , -4 ) == '.jpg' || substr ( $this->_tpl_vars['picture'] , -4 ) == '.png' || substr ( $this->_tpl_vars['picture'] , -4 ) == '.gif' || substr ( $this->_tpl_vars['picture'] , -5 ) == '.jpeg' )): ?>
          <img src="<?php echo imagePath(array('mode' => 'small','img' => $this->_tpl_vars['picture']), $this);?>
" alt=""><BR><?php echo $this->_tpl_vars['picture']; ?>
<BR>
        <?php else: ?>
          <a href="<?php echo imagePath(array('img' => $this->_tpl_vars['picture']), $this);?>
"><?php echo $this->_tpl_vars['picture']; ?>
</a><BR>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Title'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Lastname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
,&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Firstname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Address'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['ZipCode'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Town'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Country_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Country'), $this);?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        &nbsp;
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Tel.:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Telephone'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Fax:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Fax'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Email:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Email'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    HTML:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['LeftSide']['Html'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
</TABLE>
</TD>
<!-- FIRM -->
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['FirmName_ml'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
    <TD rowspan="9" style="text-align:right">
    <?php $_from = $this->_tpl_vars['RightSide']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['picture']):
?>
      <?php if (( substr ( $this->_tpl_vars['schluessel'] , -5 ) == '_link' && ! empty ( $this->_tpl_vars['picture'] ) )): ?>
        <?php if (( substr ( $this->_tpl_vars['picture'] , -4 ) == '.jpg' || substr ( $this->_tpl_vars['picture'] , -4 ) == '.png' || substr ( $this->_tpl_vars['picture'] , -4 ) == '.gif' || substr ( $this->_tpl_vars['picture'] , -5 ) == '.jpeg' )): ?>
          <img src="<?php echo imagePath(array('mode' => 'small','img' => $this->_tpl_vars['picture']), $this);?>
" alt=""><BR><?php echo $this->_tpl_vars['picture']; ?>
<BR>
        <?php else: ?>
          <a href="<?php echo imagePath(array('img' => $this->_tpl_vars['picture']), $this);?>
"><?php echo $this->_tpl_vars['picture']; ?>
</a><BR>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['FirmDepartment'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Address'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['ZipCode'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
&nbsp;<?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Town'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Country_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Country'), $this);?>

    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        &nbsp;
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Tel.:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Telephone'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Fax:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Fax'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Email:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Email'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    HTML:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['RightSide']['Html'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
</TABLE>
</TD>
</TR>
<!-- Memberinformation -->
<TR>
<TH CLASS='title' COLSPAN='2' WIDTH="100%">Données membre</TH>
</TR>
<TR>
<TD COLSPAN="1" >
<TABLE WIDTH="100%">
<TR>
    <TD CLASS='Description'>
    Membertype:
    </TD>
    <TD COLSPAN='2' CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Membertype_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Membertype'), $this);?>

    </TD>
</TR>
<?php if (! empty ( $this->_tpl_vars['Memberinfo']['MainMemberID'] )): ?>
<TR>
    <TD CLASS='Description'>
    Full member:
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
    Entrance:
    </TD>
    <TD CLASS='Daten'>
    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Memberinfo']['Entrydate'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/0000-00-00/", "") : smarty_modifier_regex_replace($_tmp, "/0000-00-00/", "")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Information sharing:
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['InfoGiveOut_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'InfoGiveOut'), $this);?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Information in WWW:
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['InfoWWW_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'InfoWWW'), $this);?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Preferred language:
    </TD>
    <TD CLASS='Daten'>
        <?php echo smarty_function_getDescription(array('id' => ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Language_ref'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')),'table' => 'Language'), $this);?>

    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    Date de naissance:
    </TD>
    <TD CLASS='Daten'>
    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['Memberinfo']['Birthdate'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/0000-00-00/", "") : smarty_modifier_regex_replace($_tmp, "/0000-00-00/", "")))) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>

    </TD>
</TR>
<?php if (! empty ( $this->_tpl_vars['Memberinfo']['Remarks'] )): ?>
<TR>
    <TD CLASS='Description'>
    Remarque:
    </TD>
    <TD CLASS='Daten'>
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['Memberinfo']['Remarks'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

    </TD>
</TR>
<?php endif;  if (! empty ( $this->_tpl_vars['Memberinfo']['Selection'] )): ?>
<TR>
    <TD CLASS='Description'>
    Sélection:
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
        membre associé:
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
</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>