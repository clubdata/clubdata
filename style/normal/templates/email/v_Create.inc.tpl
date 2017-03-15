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
{$emailform}
{if ($htmlEdit == true) }
{literal}
<script type="text/javascript">
    initEditor();
</script>
{/literal}
{/if}