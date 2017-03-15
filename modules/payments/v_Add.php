<?php
/**
 * Clubdata Payments Modules (View Add)
 *
 * Contains classes to add payments
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
 * @package Payments
 */
class vAdd {
    var $memberID;
    var $db;
    var $paymentObj;
    var $smarty;
    var $formsgeneration;

    // ignore parameter paymentObj and initView
    function vAdd($db, $memberID, $paymentObj, $initView, $smarty, $formsgeneration)
    {
        debug_r('M_PAYMENTS', $paymentObj, "[Payments, vAdd, vAdd], MemberID: $memberID, initView: $initView, paymentObj:");
        $this->db = $db;
        $this->memberID = $memberID;
        $this->paymentObj = $paymentObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        if ( !empty($this->memberID ) )
        {
            $presetVals = array("MemberID" => $this->memberID);
        }
        $presetEditVals = array();

        $this->paymentObj->newRecord($presetVals, $presetEditVals);
    }

    function getSmartyTemplate()
    {
        return 'payments/v_Add.inc.tpl';
    }

    function setSmartyValues()
    {
        
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
        $insertID = $this->paymentObj->insertRecord();
        return true;
    }
    
    function getHeadTxt()
    {
        return lang("Add payment");
    }
}
?>
