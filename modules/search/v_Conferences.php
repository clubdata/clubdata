<?php
/**
 * Clubdata Search Modules
 *
 * Contains classes to search data in Clubdata.
 *
 * @package Search
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/search.class.php");

/**
 * This class generates the search form for conferences
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Search
 */
class vConferences {
    var $db;
    var $smarty;
    var $formsgeneration;

    var $defaultValues = array('###_Members_Attributes' =>
                            array ( 'Invoice_yn' =>
                                        array ( 'Value' => array (1),
                                                'Compare' => 'eq'
                                                )
                                    )
                                );
    
    function vConferences($db, $searchmode,$smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        // Preset values
        $defVals['Invoice_yn'] = 1; // NO Email
        $defCmps['Invoice_yn'] = "eq"; // Compare Selection
//        $this->search = new Search($db, $formsgeneration, "Conferences", "Conferences", $defVals, $defCmps);
          $this->search = new Search($db, $formsgeneration, "Conferences", "Conferences", $this->defaultValues);
    }
    
    function setSearchMode($searchmode)
    {
        $this->search = new Search($this->db, $this->formsgeneration, "Conferences", "Conferences", $this->defaultValues);
    }
    
    /**
     * Returns an array of text (HTML) to be displayed as header.
     * The return value must be an array. The values are displayed side by side.
     * @return array text (HTML) to be displayed as header
     */
    function getHeaderText()
    {
      return lang("Search for conferences");
    }
    
    function getSmartyTemplate()
    {
        return 'search/v_Conferences.inc.tpl';
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