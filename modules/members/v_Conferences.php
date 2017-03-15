<?php
/**
 * Clubdata Member Modules
 *
 * Contains the class to list and manipulate conferences participation for a member
 * The views which are called by this class correspond to the tabs shown on the member page
 *
 * @package Members
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/dblist.class.php");
require_once("include/subscription.class.php");

/**
 * @package Members
 */
class vConferences {
    var $memberID;
    var $db;
    var $mlist;
    var $pageNr = 0;

    var $smarty;
    var $formsgeneration;

    function vConferences($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        /* addresstype not used */
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $sqlString = <<<_EOT_
        SELECT SubscriptionID, `###_Conferences`.id as ConferenceID, Conferences_ref, Startdate, Starttime, NumPersons
        FROM `###_Members_Conferences` JOIN `###_Conferences` ON `###_Conferences`.id = `###_Members_Conferences`.Conferences_ref

_EOT_;

        $this->mlist = new DbList($db, "memberconferenceslist",
                                    array("changeFlg" => TRUE, //"changeFlg" => getUserType(UPDATE, "Emails"),
                                        "sql" => $sqlString,
                                        "cond" => "MemberID = $this->memberID",
                                        "selectRowsFlg" => FALSE,
                                        "idFieldName" => "SubscriptionID",
                                        "listLinks" => array ( "Detail" => "Conferences/Conferences_detail.php",
                                                                "Edit" => INDEX_PHP . "?mod=conferences&view=Subscribe",
                                                                "Delete" => INDEX_PHP . "?mod=members&view=Conferences&Action=DELETE",
                                                                "ConferenceID" => INDEX_PHP . "?mod=conferences&view=Detail&InitView=1"),
                                        "linkParams" => "MemberID=$this->memberID&buttonSet=detail&list=memberconferenceslist"));

        if ( $this->mlist->getConfig("sort") == "" )
        {
            $this->mlist->setConfig("sort", "Startdate");
        }
    }

   function doAction($action)
    {
      debug('M_MEMBER', "[V_Conferences, doAction]: action: $action");
      switch($action)
      {
        case 'DELETE':
          $id = getGlobVar('SubscriptionID','::number::','PG');
          $this->subscriptionObj = new Subscription($this->db, $this->formsgeneration, array('SubsciptionID' => $id));
          $this->subscriptionObj->deleteRecord();
        break;
      }
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'members/v_Conferences.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('SMARTY', $this->mlist, "[V_Conferences, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('ConferenceList', $this->mlist);
    }
    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable();
    }
}
?>
