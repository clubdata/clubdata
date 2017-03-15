<?php /* Smarty version 2.6.10, created on 2011-01-06 15:02:36
         compiled from main/v_Impressum.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'main/v_Impressum.inc.tpl', 13, false),)), $this); ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" height="500" BORDER="0">
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ul.png" height="10" width="10" border="0"></TD>
    <td class="light_border_upper"></td>
    <td><img src="style/newstyle/images/light_corner_ur.png" width="13" border="0"></td>
</TR>
<TR>
<TD class="light_border_left"></TD>
<TD valign="top">
<CENTER>
<H5><?php echo $this->_tpl_vars['version']; ?>
<BR>Copyright 2002-2010, <a href="mailto:franz.domes@gmx.de">Franz Domes</a><BR>
<a href=index.php?mod=main?view=Copyright>GNU General Public License</a></H5>
<?php echo ((is_array($_tmp="Clubdata V2 uses the following software:")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<BR>
<?php echo ((is_array($_tmp="Thanks to all those people, who makes programming much easier!")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<TABLE width="100%" >
<TR>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://adodb.sourceforge.net",'boxtitle' => 'ADODB','boxhelp' => ((is_array($_tmp='Database abstraction layer')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://adnoctum.netfirms.com/products/auth/",'boxtitle' => 'Auth','boxhelp' => ((is_array($_tmp="The authentication module used in Clubdata is based on Auth.php from Julio C�sar Carrascal Urquijo")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://www.phpclasses.org/browse/package/540.html",'boxtitle' => 'datapager','boxhelp' => ((is_array($_tmp="Shows listings 'per page'")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
</TR>
<TR>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://sourceforge.net/projects/pdf-php",'boxtitle' => 'pdfClass','boxhelp' => ((is_array($_tmp='Generating PDF output')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://www.cnovak.com/",'boxtitle' => 'PHP2Excel','boxhelp' => ((is_array($_tmp="Generating EXCEL output. (The original Webpage www.cnovak.com is not accessible anymore). I don't know how to contact the author)")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://www.htmlarea.com/",'boxtitle' => 'HTMLArea','boxhelp' => ((is_array($_tmp='HTML editor')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
</TR>
<TR>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://www.smarty.net/",'boxtitle' => 'Smarty','boxhelp' => ((is_array($_tmp='The template engine')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://www.phpclasses.org/browse/package/1.html",'boxtitle' => "Class: Forms generation and validation",'boxhelp' => ((is_array($_tmp='Management of forms')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </TD>
    <td>&nbsp;</td>
    <TD style="vertical-align: top" width="33%">
<!--    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/box.inc.tpl", 'smarty_include_vars' => array('boxlink' => "http://sf.net/projects/itools-htmlarea/",'boxtitle' => 'HTMLArea','boxhelp' => ((is_array($_tmp='HTML editor')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>-->
    </TD>
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