<?php
/**
 * Clubdata Search Modules (View Email)
 *
 * Contains the class to search for members to which should be send an email.
 * By default the Attribute "Member wants infos per email" is predefined for this search
 *
 * @package Search
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/search.class.php");

/**
 * This class generates the search form for invoices
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Search
 */
class vInvoice {
    var $db;

    // Preset values
    // Attribute value 2 means Invoices
    var $defaultValues = array('###_Members_Attributes' =>
                            array ( 'Attributes_ref' =>
                                        array ( 'Value' => array (2),
                                                'Compare' => 'INSelection'
                                                )
                                    )
                                );
    var $smarty;
    var $formsgeneration;

    function vInvoice($db, $searchmode,$smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        // No Resubmit-check, as it doesnt work with modalboxes used to ask for invoice date
//        $this->formsgeneration->ResubmitConfirmMessage="";

        $this->search = new Search($db, $formsgeneration, $searchmode, 'Invoice', $this->defaultValues);
    }

    function setSearchMode($searchmode)
    {
        $this->search = new Search($this->db, $this->formsgeneration, $searchmode, 'Invoice');
    }

    /**
     * Returns an array of text (HTML) to be displayed as header.
     * The return value must be an array. The values are displayed side by side.
     * @return array text (HTML) to be displayed as header
     */
    function getHeaderText()
    {
      return lang("Search for invoice");
    }

function getSmartyTemplate()
    {
        return 'search/v_Invoice.inc.tpl';
    }

    function setSmartyValues()
    {

        $errTxt .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"hidden",
                        "NAME"=>'InitView',
                        "ID"=>'InitView',
                        "VALUE"=>1,
                        ));

        $this->search->displaySearch();
        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'search.inc.tpl'));
        $this->smarty->assign_by_ref("formDefinition", $this->formsgeneration->getFormDefinition());
    }
}
?>