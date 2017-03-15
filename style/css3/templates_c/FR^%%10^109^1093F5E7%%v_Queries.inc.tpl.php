<?php /* Smarty version 2.6.10, created on 2011-01-05 22:54:57
         compiled from queries/v_Queries.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'queries/v_Queries.inc.tpl', 11, false),)), $this); ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" height="500" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD valign="top">
<CENTER>
<TABLE width="100%" >
<TR>
  <?php if (( ! isMember ( ) )): ?>
    <TD style="vertical-align: top" width="33%">
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDES_PHP'])."?mod=queries&view=MemberSummary",'boxtitle' => ((is_array($_tmp='Member summary')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxhelp' => ((is_array($_tmp='Number of members per member type')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=Statistics",'boxtitle' => ((is_array($_tmp='Statistics')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxhelp' => ((is_array($_tmp="Several statistics, like number of paying members etc.")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </TD>
    <td>&nbsp;</td>
   <?php endif; ?>
    <TD style="vertical-align: top" width="33%">
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=AddressLists",'boxtitle' => ((is_array($_tmp='Addresslists')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'boxhelp' => ((is_array($_tmp="Different address lists, e.g. for public use")) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </TD>
  <?php if (( isMember ( ) )): ?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   <?php endif; ?>
</TR>
</TABLE>
</CENTER>
</TD>
<TD class="light_border_right"></TD>
</TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>