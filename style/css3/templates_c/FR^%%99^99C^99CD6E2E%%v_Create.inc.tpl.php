<?php /* Smarty version 2.6.10, created on 2011-01-06 14:11:35
         compiled from email/v_Create.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'email/v_Create.inc.tpl', 25, false),)), $this); ?>
<?php if (( $this->_tpl_vars['htmlEdit'] == true )):  echo '
<SCRIPT type="text/javascript">
    _editor_url = "javascript/htmlarea/";
    _editor_lang = "de";
</script>
<script type="text/javascript" src="javascript/htmlarea/htmlarea.js"></script>

<script type="text/javascript">
    var editor = null;
    HTMLArea.loadPlugin("FullPage");

    function initEditor() {

        editor = new HTMLArea("BODY_TXT");
        editor.config.height = "400px";
        editor.registerPlugin(FullPage);
        // comment the following two lines to see how customization works
        editor.generate();

        var f = document.forms[0];
        f.onHTMLEditorSubmit = function() {
            if ( f.subject.value == "" )
            {
                if ( confirm(\'';  echo ((is_array($_tmp="No Subject ! Continue anyway?")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\') == false)
                {
                    f.subject.focus();
                    return false;
                }
            }
            f.onsubmit();
            return true;
        };

        return false;
    }
</script>
'; ?>

<?php endif; ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
<?php echo $this->_tpl_vars['emailform']; ?>

</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>
<?php if (( $this->_tpl_vars['htmlEdit'] == true )):  echo '
<script type="text/javascript">
    initEditor();
</script>
'; ?>

<?php endif; ?>