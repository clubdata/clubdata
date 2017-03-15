<?php
/**
 * Clubdata Conference Modules
 *
 * Contains classes to administer conferences in Clubdata.
 *
 * @package Conferences
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/dbtable.class.php");

/**
 *  Add a new conference
 *
 * @package Conferences
 */
class vAdd {
    var $memberID;
    var $db;
    var $conferenceObj;
    var $smarty;
    var $formsgeneration;

    // ignore parameter conferenceObj and initView
    function vAdd($db, $memberID, $conferenceObj, $initView, $smarty, $formsgeneration)
    {
        debug_r('M_CONFERENCES', $conferenceObj, "[Payments, vAdd, vAdd], MemberID: $memberID, initView: $initView, conferenceObj:");
        $this->db = $db;
        $this->memberID = $memberID;
        $this->conferenceObj = $conferenceObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        if ( !empty($this->memberID ) )
        {
            $presetVals = array("MemberID" => $this->memberID);
        }
        $presetEditVals = array();

        $this->conferenceObj->newRecord($presetVals, $presetEditVals);
    }

    function getSmartyTemplate()
    {
        return 'conferences/v_Add.inc.tpl';
    }

    function setSmartyValues()
    {
        
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
        $insertID = $this->conferenceObj->insertRecord();
        return true;
    }
    
    function getHeadTxt()
    {
        return lang("Add conference");
    }
}
?>
