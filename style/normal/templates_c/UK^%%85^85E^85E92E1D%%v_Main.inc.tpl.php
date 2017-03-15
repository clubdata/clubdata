<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:07
         compiled from main/v_Main.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', 'main/v_Main.inc.tpl', 5, false),array('modifier', 'cat', 'main/v_Main.inc.tpl', 5, false),)), $this); ?>
<P>
<CENTER>
<H2>Welcome</H2>
<H3>at</H3>
<?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/ClubLogo.jpg") : smarty_modifier_cat($_tmp, "images/ClubLogo.jpg")),'alt' => 'ClubAdmin','align' => 'middle','border' => '0'), $this);?>

<H3>The software for club member administration</H3>
<H4>Version 2.0<BR>
Copyright 2002-2005, <a href="mailto:franz.domes@gmx.de">Franz Domes</a></H4>
<H5><a href=COPYING target=COPYING>GNU General Public License</a></H5>
<H5><a href="MemberMain_Impressum.php">Impressum</a></H5>
</P>