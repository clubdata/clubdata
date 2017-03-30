<?php
/**
 * @package Clubdata
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/function.php');

$clubdataFG_Output = '';
$clubdataFG_Head = '';
$clubdataFG_Javascript = '';

function captureAndProcessOutput($string) {
    global $clubdataFG_Output, $clubdataFG_Head, $clubdataFG_Javascript;
    static $state = 0;

    if (!strncasecmp($string, "<form", 5)) {
        $state = 1;
    }

    switch ($state) {
        case 0:
            if (!strncasecmp($string, "<script", 7)) {
                $clubdataFG_Javascript .= $string;
                $state = 2;
            } elseif (!strncasecmp($string, "</form>", 7)) {
                $state = 0; //FD20110101
            } else {
                $clubdataFG_Output .= $string;
            }
            break;
        case 1:
            $clubdataFG_Head .= $string;

            if (!strcmp($string, ">\n")) {
                $state = 0;
            }
            break;
        case 2:
            if (($pos=stripos($string, "</script>")) !== false) {
                $clubdataFG_Javascript .= substr($string, 0, $pos+10);
                $state = 0;
            } else {
                $clubdataFG_Javascript .= $string;
            }
            break;
        case 3:
            $state = 0;
            break;
    }
}


/**
 * @package Clubdata
 */
class clubdataFG extends form_class {

    public function __construct($name = 'tableforms') {
        $errTxt = array();

        $this->NAME=$name;

        /*
         * Use the GET method if you want to see the submitted values in the form
         * processing URL, or POST otherwise.
         */
        $this->METHOD="POST";

        $this->ENCTYPE="multipart/form-data";
        /*
        * Make the form be displayed and also processed by this script.
        */
        $this->ACTION=INDEX_PHP;

        /*
        * Specify a debug output function you really want to output any programming errors.
        */
        $this->debug="formsdebug";

        /*
        * Define a warning message to display by Javascript code when the user
        * attempts to submit the this form again from the same page.
        */
        $this->ResubmitConfirmMessage="Are you sure you want to submit this form again?";

        /*
        * Output previously set password values
        */
        $this->OutputPasswordValues=1;


        /*
        * Output multiple select options values separated by line breaks
        */
        $this->OptionsSeparator="<br>\n";

        /*
        * Output all validation errors at once.
        */
        $this->ShowAllErrors=1;

        /*FORMS
        * Output all validation errors at once.
        */
        $this->encoding="UTF-8";

        /*
        * Text to prepend and append to the validation error messages.
        */
        $this->ErrorMessagePrefix="- ";
        $this->ErrorMessageSuffix="";

        debug('FORMS', "[clubdataFG clubdataFG] $name");

        if (count($errTxt = array_filter($errTxt))) {
            $str = join("<BR>", $errTxt);
            $APerr->setFatal(__FILE__, __LINE__, $str);
        }
    }


    public function FetchOutput() {
        global $clubdataFG_Output;

        $arguments=array(
                "Function"=>"captureAndProcessOutput",
                "EndOfLine"=>$this->end_of_line
        );

        return(strlen($this->OutputError($this->Output($arguments))) ? "" : $clubdataFG_Output);
    }

    public function processFormsGeneration($smarty, $template) {
        global $clubdataFG_Output, $clubdataFG_Head, $clubdataFG_Javascript;

        $clubdataFG_Output = $clubdataFG_Head = $clubdataFG_Javascript = '';

        $this->ResetFormParts();

        $smarty->assign_by_ref("form", $this);
        $smarty->register_prefilter("smarty_prefilter_form");
        $op = $smarty->fetch("formsgeneration/" . $template);
        $smarty->unregister_prefilter("smarty_prefilter_form");

        $output = $this->FetchOutput();


        return $clubdataFG_Output;
    }

    public function getFormDefinition() {
        global $clubdataFG_Head;

        return  $clubdataFG_Head;
    }

    public function getJavascriptDefinition() {
        global $clubdataFG_Javascript;

        return  $clubdataFG_Javascript;
    }

    public function addInput($a) {
        debug_r(
            'FORMS',
            $a,
            "[clubdataFG addInput] " .
            (array_key_exists('NAME', $a) ? $a["NAME"] : 'Kein Index NAME vorhanden') .
            ", IsSet:" .
            (array_key_exists('NAME', $a) ? (isset($this->inputs[$a["NAME"]]) ? 'TRUE' : 'FALSE') : '') . "a:"
        );

        if (!(array_key_exists('NAME', $a) && isset($this->inputs[$a["NAME"]]))) {
            return parent::addInput($a);
        }
    }
}
