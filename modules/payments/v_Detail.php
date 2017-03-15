<?php
/**
 * Clubdata Payments Modules
 *
 * Contains classes to administer payments in Clubdata.
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
 *  View payments detail information
 *
 * @package Payments
 */
class vDetail {
    var $memberID;
    var $db;
    var $paymentObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;

    function vDetail($db, $memberID, $paymentObj,$initView, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->paymentObj = $paymentObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;
    }

    function getSmartyTemplate()
    {
        return 'payments/v_Detail.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->paymentObj->showRecord();
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }


    function getHeadTxt()
    {
        return lang("View payment");
    }

}
?>
