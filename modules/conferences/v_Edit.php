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
 *  Edit conference information
 *
 * @package Conferences
 */

class vEdit {
    var $memberID;
    var $db;
    var $conferenceObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;
    
    function vEdit($db, $memberID, $conferenceObj, $initView, $smarty, $formsgeneration)
    {
        debug_r('M_CONFERENCES', $conferenceObj, "[Payments, vEdit, vEdit], MemberID: $memberID, initView: $initView, conferenceObj:");
        $this->db = $db;
        $this->memberID = $memberID;
        $this->conferenceObj = $conferenceObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->conferenceObj->editRecord();
    }
    
    function getSmartyTemplate()
    {
        return 'conferences/v_Edit.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
        return $this->conferenceObj->updateRecord();
    }

    function getHeadTxt()
    {
        return lang("Edit conference");
    }

}
?>
