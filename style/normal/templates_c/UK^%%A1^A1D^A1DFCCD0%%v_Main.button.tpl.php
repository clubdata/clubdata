<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:07
         compiled from main/v_Main.button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'main/v_Main.button.tpl', 6, false),)), $this); ?>
<!-- MAIN buttonbar -->
<table CLASS=Bottombar>
<TR>
<TD>
<BUTTON NAME="Submit_Copyright" TYPE="submit" onClick="doSubmit('main','Copyright');"
          value="<?php echo ((is_array($_tmp='Copyright')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"><?php echo ((is_array($_tmp='Copyright')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</BUTTON>
</TD>
<TD CLASS=FILL>&nbsp;</TD>
<TD>
<BUTTON NAME="Submit_Logoff" TYPE="submit" onClick="doSubmit('main','Logoff');"
          value="<?php echo ((is_array($_tmp='Logoff')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"><?php echo ((is_array($_tmp='Logoff')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</BUTTON>
</TD>
</TR>
</table>