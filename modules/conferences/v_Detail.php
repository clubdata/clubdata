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
 *  View conference detail information
 *
 * @package Conferences
 */
class vDetail {
    var $memberID;
    var $db;
    var $conferenceObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;

    function vDetail($db, $memberID, $conferenceObj,$initView, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->conferenceObj = $conferenceObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;
    }

    function getSmartyTemplate()
    {
        return 'conferences/v_Detail.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->conferenceObj->showRecord();
        $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }


    function getHeadTxt()
    {
        return lang("View conference");
    }

}
?>
