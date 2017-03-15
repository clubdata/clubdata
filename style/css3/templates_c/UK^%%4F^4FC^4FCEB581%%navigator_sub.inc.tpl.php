<?php /* Smarty version 2.6.10, created on 2015-06-19 22:57:02
         compiled from general/navigator_sub.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'general/navigator_sub.inc.tpl', 1, false),)), $this); ?>
<li class="navigator_sub row-nomargin <?php echo ((is_array($_tmp=@$this->_tpl_vars['nav_visible'])) ? $this->_run_mod_handler('default', true, $_tmp, 'subnav_hidden') : smarty_modifier_default($_tmp, 'subnav_hidden')); ?>
" >
        <div class="col-auto"><div class="triangle-right"></div></div>
        <div class="col-auto"><a <?php echo ((is_array($_tmp=@$this->_tpl_vars['nav_javascript'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
 href="<?php echo $this->_tpl_vars['nav_href']; ?>
"><?php echo $this->_tpl_vars['nav_label']; ?>
</a></div>
</li>