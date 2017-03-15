<?php
/**
 * Clubdata Member Modules
 *
 * Contains the classes to manipulate member entries
 * The views which are called by this class correspond to the tabs shown on the member page
 *
 * @package Members
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/addresses.class.php');
//     include("javascript/calendar.js.php");

/**
 * @package Members
 */
class vAddresses {
    var $memberID;
    var $db;
    var $addresstype;

    var $adrObj;

    var $smarty;
    var $formsgeneration;

    function vAddresses($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->addresstype = $addresstype;
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $this->adrObj = new Addresses($this->db, $this->formsgeneration, $this->addresstype, $this->memberID);

        $this->adrObj->editRecord();
    }

    function getSmartyTemplate()
    {
        return 'members/v_Addresses.inc.tpl';
    }

    function setSmartyValues()
    {

        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
        $this->smarty->assign("formDefinition", $this->formsgeneration->getFormDefinition());    }

    function doAction($action)
    {
        switch ($action)
        {
            case "UPDATE":
                if ( $this->adrObj->recordExists() )
                {
                    $this->adrObj->updateRecord();
                }
                else
                {
                    $this->adrObj->insertRecord();
                }
        }
    }
}
?>