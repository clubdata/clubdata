<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:31
         compiled from general/navigator_sub.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'general/navigator_sub.inc.tpl', 4, false),)), $this); ?>
<TR>
    <TD></TD>
    <TD class="navigator_sub">
        <a <?php echo ((is_array($_tmp=@$this->_tpl_vars['nav_javascript'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
 href="<?php echo $this->_tpl_vars['nav_href']; ?>
">
        <img src="style/newstyle/images/flash_small.png" align="bottom" alt="members" border="0">
        <?php echo $this->_tpl_vars['nav_label']; ?>
</a>
    </TD>
    <TD></TD>
</TR>