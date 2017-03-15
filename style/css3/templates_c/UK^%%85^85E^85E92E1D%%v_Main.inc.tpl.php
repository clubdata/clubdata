<?php /* Smarty version 2.6.10, created on 2015-06-14 01:56:39
         compiled from main/v_Main.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'main/v_Main.inc.tpl', 2, false),array('modifier', 'cat', 'main/v_Main.inc.tpl', 3, false),array('function', 'html_image', 'main/v_Main.inc.tpl', 3, false),)), $this); ?>
<div class="vMain" style="margin: 0px auto; text-align: center;">
            <H3><?php if (( $this->_tpl_vars['demoMode'] )):  echo ((is_array($_tmp='at the demo version of')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp));  else:  echo ((is_array($_tmp='at')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp));  endif; ?></H3>
            <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/Logo/LogoPenSmall.png") : smarty_modifier_cat($_tmp, "images/Logo/LogoPenSmall.png")),'alt' => 'ClubAdmin','align' => 'middle','border' => '0'), $this);?>

            <H3>The software for club member administration</H3>
           <H4>Version 2.03 beta 8<BR>
            Copyright 2002-2015, <a href="mailto:franz.domes@gmx.de">Franz Domes</a></H4>
</div>