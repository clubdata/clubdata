<?php
/**
 * Clubdata Email Modules
 *
 * Contains the classes of the main menu
 *
 * @package Email
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
global $db;

require_once("include/function.php");
require_once("include/emailsendbase.class.php");

// $dispFile = 'style/' . getConfigEntry($db, 'Style') . '/display_email.php';
// require_once($dispFile);

/**
 * Module class to create (=write) emails
 *
 * @package Email
 */
class vCreate {
    var $memberID;
      var $mailingType;
    var $db;
    var $tableObj;
    var $emailSendType;
    var $htmlEdit;
    var $attachmentArr;
    var $bodyTxt;
    var $subject;
    var $sendBcc;
    var $sendCc;
    var $sendTo;
    var $receiver;
    var $from;
      var $replyTo;
    var $smarty;
    var $formsgeneration;
    var $emailsendbase;

    function vCreate($db, $memberID, $mailID, $mailingType, $smarty, $formsgeneration)
    {
        global $APerr;

        $this->db = $db;
        $this->memberID = $memberID;
        $this->mailID = $mailID;
        $this->mailingType = $mailingType;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->emailSendType = getConfigEntry($this->db, "EmailSendType");
        $this->htmlEdit = getConfigEntry($this->db,"EmailAsHTML");

        $this->emailsendbase = new EmailSendBase($this->db, $this->smarty, $this->formsgeneration);
        $emailArr = array();
        if ( !empty($this->mailID) )
        {
            $sql = "SELECT * FROM `###_Emails` WHERE ID = $this->mailID";
            $emailArr = $this->db->GetRow($sql);
            if ( $emailArr === false )
            {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            }
            else
            {
                $this->sendTo = $emailArr["EmailTo"];
                $this->sendCc = $emailArr["EmailCC"];
                $this->sendBcc = $emailArr["EmailBCC"];
                $this->subject = $emailArr["EmailSubject"];
                $this->bodyTxt= $emailArr["EmailBody"];
                $this->attachmentArr = explode(",", $emailArr["EmailAttachedFiles"]);

            }
        }
        elseif ( !empty($this->memberID) )
        {
            if ( is_array($this->memberID) )
            {
                // Get E-Mail addresses of those member which have
                // Attribute "Infos per Email" set (Attribute type 3)
                $sql = "SELECT `###_Members`.MemberID, Email
                          FROM `###_Members`, `###_Addresses`, `###_Addresses_Mailingtypes`, `###_Members_Attributes`
                         WHERE `###_Members`.MemberID = `###_Members_Attributes`.MemberID
                           AND `###_Members_Attributes`.Attributes_ref =3
                           AND `###_Members`.MemberID = `###_Addresses`.Adr_MemberID
                           AND `###_Addresses`.id = `###_Addresses_Mailingtypes`.AddressID
                           AND `###_Addresses_Mailingtypes`.Mailingtypes_ref = $this->mailingType
                           AND `###_Members`.MemberID in (" . implode(", ", $this->memberID) .")";

                $res = $this->db->Execute($sql);
                if ( $res === false )
                {
                    $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                }
                else
                {
                    $recArr = array();
                    while ( $mgArr = $res->FetchRow() )
                    {
                        if ( !empty($mgArr['Email']) )
                        {
                            $recArr[] = $mgArr['Email'];
                        }
                    }
                    $this->receiver = join(", ", $recArr);
                }
            }
        }
    }

    function getSmartyTemplate()
    {
        return 'email/v_Create.inc.tpl';
    }

    function setSmartyValues()
    {
        global $APerr;

        passParameterAsSession(array("MemberID" => $this->memberID));

        $this->from = htmlspecialchars(getConfigEntry($this->db,"Email"));
        $this->replyTo = getConfigEntry($this->db,"ReplyTo");
        switch ($this->emailSendType)
        {
            case "BCC":
            case "OLD":
                $this->sendBcc .= $this->receiver;
                break;

            case "INDIV":
                $this->sendTo .= $this->receiver;
                break;
        }

        $this->smarty->assign("from",$this->from);
        if ( !empty($emailObj->replyTo) && $emailObj->replyTo != $emailObj->from )
        {
                $this->smarty->assign_by_ref("replyTo",htmlspecialchars($this->replyTo));
        }
        $this->smarty->assign_by_ref("h_sendTo", htmlspecialchars($this->sendTo));
        $this->smarty->assign_by_ref("h_sendCc", htmlspecialchars($this->sendCc));
        $this->smarty->assign_by_ref("h_sendBcc", htmlspecialchars($this->sendBcc));
        $this->smarty->assign_by_ref("h_subject", htmlspecialchars($this->subject));
        $this->smarty->assign_by_ref("h_bodyTxt", htmlspecialchars($this->bodyTxt));
        $this->smarty->assign_by_ref("htmlEdit", $this->htmlEdit);

        $this->emailsendbase->emailFormular(array(
                              "from" => $this->from,
                              "replyTo" => $this->replyTo,
                              "sendTo" => $this->sendTo,
                              "sendCc" => $this->sendCc,
                              "sendBcc" => $this->sendBcc,
                              "subject" => $this->subject,
                              "bodyTxt" => $this->bodyTxt,
                              "mailingType" => $this->mailingType,
        					  "accessible" => !isMember()			//FD20110105
                              ));


        $this->smarty->assign_by_ref("emailform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
//         $this->tableObj->updateRecord();
    }
}
?>
