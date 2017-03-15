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
require("include/emailsendbase.class.php");

/**
 * Class to send emails with users listed as BCC
 *
 * @package Email
 */
class sendBCC extends EmailSendBase
{
    var $db;
    var $smarty;
    var $formsgeneration;

    function sendBCC($db, $smarty, $formsgeneration)
    {
        global $APerr;

        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;
        $this->db = $db;

        EmailSendBase::EmailSendBase($db, $smarty, $formsgeneration);

        /*
        * Therefore we need to validate the submitted form values.
        */
        if(($error_message=$formsgeneration->Validate($verify))=="")
        {

            $doit=1;

        }
        else
        {

            $doit=0;
            $error_message=nl2br(HtmlSpecialChars($error_message));
            debug("M_MAIL", "[BCC, sendBCC] Formserror: $error_message");
            $APerr->setFatal(__FILE__,__LINE__,$error_message);
        }


        $this->emailFormular();
        $this->mailingType = $this->formsgeneration->GetInputValue('Mailingtype');
        $this->sendTo = trim($this->formsgeneration->GetInputValue("send_to"));
        $this->sendCc = trim($this->formsgeneration->GetInputValue("send_cc"));
        $this->sendBcc = trim($this->formsgeneration->GetInputValue("send_bcc"));
        $this->subject = stripslashes($this->formsgeneration->GetInputValue("subject"));
        $this->bodyTxt = stripslashes($this->formsgeneration->GetInputValue("BODY_TXT"));
    }

    function doSendEmails()
    {
        global $APerr;

        $this->emailMsgObj->SetEncodedHeader("Subject",$this->subject);

        if ($this->isHTML)
        {
          $this->emailMsgObj->CreateQuotedPrintableHTMLPart($this->bodyTxt, "", $html_part);
          $this->emailMsgObj->CreateQuotedPrintableTextPart(strip_tags($this->br2nl($this->bodyTxt)), "",$text_part);

          $alternative_parts=array(
                       $text_part,
                       $html_part
          );
          $this->emailMsgObj->AddAlternativeMultipart($alternative_parts);
        }
        else
        {
            $this->emailMsgObj->AddQuotedPrintableTextPart(strip_tags(br2nl($this->bodyTxt)), "",$text_part);
        }

        $emailAttachedFiles = $this->addAttachments();

        $to_arr = array();
        foreach(preg_split("/\s*,\s*+/", $this->sendTo, -1, PREG_SPLIT_NO_EMPTY) as $val)
        {
            $to_arr[$val] = "";
        }
        if ( count($to_arr) == 0 )
        {
            $to_arr[$this->from] = $this->fromName;
        }
        if ( count($to_arr) > 0 )
        {
            $this->emailMsgObj->SetMultipleEncodedEmailHeader('To', $to_arr);
        }

        $cc_arr = array();
        foreach(preg_split("/\s*,\s*+/", $this->sendCc, -1, PREG_SPLIT_NO_EMPTY) as $val)
        {
            $cc_arr[$val] = "";
        }
        if ( count($cc_arr) > 0 )
        {
            $this->emailMsgObj->SetMultipleEncodedEmailHeader('Cc', $cc_arr);
        }

        $bcc_arr = array();
        debug('M_MAIL', "[sendBCC, doSendEmails] bcc: $this->sendBcc<BR>") ;
        foreach(preg_split("/\s*,\s*+/", $this->sendBcc, -1, PREG_SPLIT_NO_EMPTY) as $val)
        {
            $bcc_arr[$val] = "";
        }
        if ( count($bcc_arr) > 0 )
        {
            $this->emailMsgObj->SetMultipleEncodedEmailHeader('Bcc', $bcc_arr);
        }

        $error=$this->emailMsgObj->Send();

        if(strlen($error))
        {
            $APerr->setError(__FILE__,__LINE__,$error,$this->sendBcc);
        }
        else
        {
            $APerr->setInfo(lang("Email send succesfully !"),
                            lang("Subject") . ": $this->subject");

            $emailID =  $this->insertEmailToDB($this->sendTo, 
			            					   $this->sendCc, 
			            					   $this->sendBcc,
			            					   $this->subject,
			            					   $this->bodyTxt,
			            					   join(",",$emailAttachedFiles),
			            					   $this->mailingType);
            if ( $emailID === false)
//            $sqlTxt = <<<_EOT_
//            INSERT INTO `###_Emails` (EmailFrom, EmailTo, EmailCC, EmailBCC, EmailSubject,
//                                      EmailBody, EmailAttachedFiles, EmailSendtime)
//            VALUES
//                (
//                %s, %s, %s,%s,%s,%s,%s,
//                NOW()
//                );
//_EOT_;
//            $sql = sprintf($sqlTxt,
//                            $this->db->qstr($this->from . " (". $this->fromName . ")"),
//                            $this->db->qstr($this->sendTo),
//                            $this->db->qstr($this->sendCc),
//                            $this->db->qstr($this->sendBcc),
//                            $this->db->qstr($this->subject),
//                            $this->db->qstr($this->bodyTxt),
//                            $this->db->qstr(join(",",$emailAttachedFiles)));
//
//            $res = $this->db->Execute($sql);
//            if ( $res === false )
            {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                $error = "DBERROR";
            }
            else
            {
//                $emailID = $this->db->Insert_ID();
                logEntry("INSERT EMAIL", "ID=$emailID");

                $APerr->setInfo(lang("Email saved successfully with ID") . ": $emailID");

                $ret = $this->insertEmailsMemberRelation($emailID,
                                            implode(",",
                                                array_merge(array_keys($to_arr),
                                                            array_keys($cc_arr),
                                                            array_keys($bcc_arr)
                                                            )
                                                    ),
                                                $this->mailingType
                                            );
                if ( $ret === false )
                {
                    $APerr->setWarn(lang("Unable to insert all E-Mail to Member relations !"));
                }
                else
                {
                    $APerr->setInfo(lang("Successfully inserted all E-Mail to Member relations !"));
                }
            }
        }

        return ($error == "" ) ? TRUE: FALSE;
    }
}
?>