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

/**
 * @package Members
 */
class vEmails {
    var $memberID;
    var $db;
    var $mlist;
    var $pageNr;

    var $smarty;
    var $formsgeneration;
    
    function vEmails($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        /* addresstype not used */
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $sqlString = <<<_EOT_
        SELECT id, EmailSubject, EmailSendtime
        FROM `###_Emails` LEFT JOIN `###_Members_Emails` ON `###_Emails`.id = `###_Members_Emails`.EmailsID
_EOT_;

        $this->pageNr = getGlobVar('PageNr','::number::');

        $this->mlist = new DbList($db, "emailslist",
                            array("changeFlg" => FALSE, //"changeFlg" => getUserType(UPDATE, "Emails"),
                                "sql" => $sqlString,
                                "cond" => "MemberID = $this->memberID",
                                "selectRowsFlg" => FALSE,
                                "listLinks" => array ( "Detail" => INDEX_PHP . "?mod=email&view=Create",
                                                        "Edit" => "MemberMail/MemberMail_edit.php",
                                                        "Delete" => "MemberMail/MemberMail_delete.php"),
                                "linkParams" => "mod=members&view=Emails&MemberID=$this->memberID&buttonSet=detail"));

        if ( $this->mlist->getConfig("sort") == "" )
        {
            $this->mlist->setConfig("sort", "EmailSendtime");
        }
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'members/v_Emails.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('SMARTY', $this->mlist, "[V_Emails, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('EmailList', $this->mlist);
    }



    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable($this->pageNr);
    }
}
?>
