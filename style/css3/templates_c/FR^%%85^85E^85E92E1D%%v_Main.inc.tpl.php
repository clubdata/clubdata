<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:31
         compiled from main/v_Main.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'main/v_Main.inc.tpl', 6, false),array('modifier', 'cat', 'main/v_Main.inc.tpl', 7, false),array('function', 'html_image', 'main/v_Main.inc.tpl', 7, false),)), $this); ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
    <TD class="light_border_left"></TD>
    <TD>
        <CENTER>
            <H3><?php if (( $this->_tpl_vars['demoMode'] )):  echo ((is_array($_tmp='at the demo version of')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp));  else:  echo ((is_array($_tmp='at')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp));  endif; ?></H3>
            <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/Logo/LogoPen.png") : smarty_modifier_cat($_tmp, "images/Logo/LogoPen.png")),'alt' => 'ClubAdmin','align' => 'middle','border' => '0'), $this);?>

            <H3>The software for club member administration</H3>
           <H4>Version 2.03 beta 6<BR>
            Copyright 2002-2010, <a href="mailto:franz.domes@gmx.de">Franz Domes</a></H4>
        </CENTER>
    </TD>
    <TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>