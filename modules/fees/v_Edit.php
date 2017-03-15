<?php
/**
 * Clubdata Fees Modules
 *
 * Contains classes to administer fees in Clubdata.
 *
 * @package Fees
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/dbtable.class.php");

/**
 *  Edit fees information
 *
 * @package Fees
 */
class vEdit {
    var $memberID;
    var $db;
    var $feeObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;
    
    function vEdit($db, $memberID, $feeObj, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->feeObj = $feeObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;
        
        $this->feeObj->editRecord();
    }
    
    function getSmartyTemplate()
    {
        return 'fees/v_Edit.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }
    
/*    function displayView()
    {
        $this->tableObj->editRecord();
    }*/
    
    function doAction()
    {
        $this->feeObj->updateRecord();
    }
}
?>
