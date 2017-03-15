<?php
/**
 * Clubdata Payments Modules
 *
 * Contains classes to administer payments in Clubdata.
 *
 * @package Payments
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
 *  Edit payment information
 *
 * @package Payments
 */
class vEdit {
    var $memberID;
    var $db;
    var $paymentObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;
    
    function vEdit($db, $memberID, $paymentObj, $initView, $smarty, $formsgeneration)
    {
        debug_r('M_PAYMENTS', $paymentObj, "[Payments, vEdit, vEdit], MemberID: $memberID, initView: $initView, paymentObj:");
        $this->db = $db;
        $this->memberID = $memberID;
        $this->paymentObj = $paymentObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->paymentObj->editRecord();
    }
    
    function getSmartyTemplate()
    {
        return 'payments/v_Edit.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
        return $this->paymentObj->updateRecord();
    }

    function getHeadTxt()
    {
        return lang("Edit payment");
    }

}
?>
