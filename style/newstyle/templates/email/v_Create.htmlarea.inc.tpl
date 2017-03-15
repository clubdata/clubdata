{if ($htmlEdit == true) }
{literal}
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
                if ( confirm('{/literal}{"No Subject ! Continue anyway?"|translate}{literal}') == false)
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
{/literal}
{/if}
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
{$emailform}
</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>
{if ($htmlEdit == true) }
{literal}
<script type="text/javascript">
    initEditor();
</script>
{/literal}
{/if}