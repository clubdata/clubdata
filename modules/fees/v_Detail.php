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
 *  View fees detail information
 *
 * @package Fees
 */
class vDetail {
    var $memberID;
    var $db;
    var $feeObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;

    function vDetail($db, $memberID, $feeObj, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->feeObj = $feeObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;
    }
    
    function getSmartyTemplate()
    {
        return 'fees/v_Detail.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->feeObj->showRecord();
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }


    function getHeadTxt()
    {
        return lang("View fee");
    }
    
/*    function displayView()
    {
        $this->tableObj->showRecord();
    }*/
}
?>
