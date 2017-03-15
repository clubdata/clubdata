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
 * This class generates the search form for fees
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Search
 */
class vFees {
    var $memberID;
    var $db;
    var $smarty;
    var $formsgeneration;

    function vFees($db, $memberID,$smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        // Preset values
        $defVals['Invoice_yn'] = 1; // NO Email
        $defCmps['Invoice_yn'] = "eq"; // Compare Selection
        $this->search = new Search($db, $this->formsgeneration, "Fees", 'Fees', $defVals, $defCmps);

    }

    function setSearchMode($searchmode)
    {
        $this->search = new Search($this->db, $this->formsgeneration, $searchmode, 'Fees');
    }

    /**
     * Returns an array of text (HTML) to be displayed as header.
     * The return value must be an array. The values are displayed side by side.
     * @return array text (HTML) to be displayed as header
     */
    function getHeaderText()
    {
      return lang("Search for fees");
    }
    
    function getSmartyTemplate()
    {
        return 'search/v_Fees.inc.tpl';
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