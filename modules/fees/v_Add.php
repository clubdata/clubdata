<?php
/**
 * Clubdata Fees Modules (View Add)
 *
 * Contains classes to add fees
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
 * @package Fees
 */
class vAdd {
    var $memberID;
    var $db;
    var $feeObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;
    
    function vAdd($db, $memberID, $feeObj, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->feeObj = $feeObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $invoiceNumber = sprintf(getConfigEntry($this->db, "InvoiceNumberFormat"),
                                    getConfigEntry($this->db, "InvoiceNumber"));

        $presetVals = array("MemberID" => $this->memberID);
        $presetEditVals = array("InvoiceNumber" => $invoiceNumber);
        if ( !empty($this->memberID ) )
        {
            $presetVals = array("MemberID" => $this->memberID);
        }

        $this->feeObj->newRecord($presetVals, $presetEditVals);
    }
    
/*    function displayView()
    {
        $invoiceNumber = sprintf(getConfigEntry($this->db, "InvoiceNumberFormat"),
                                    getConfigEntry($this->db, "InvoiceNumber"));

        $presetVals = array("MemberID" => $this->memberID);
        $presetEditVals = array("InvoiceNumber" => $invoiceNumber);

        $this->tableObj->newRecord($presetVals, $presetEditVals);
    }
 */   
    function getSmartyTemplate()
    {
        return 'fees/v_Add.inc.tpl';
    }

    function setSmartyValues()
    {
        
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }
    
    function doAction()
    {
        $insertID = $this->feeObj->insertRecord();
        return true;
    }
}
?>
