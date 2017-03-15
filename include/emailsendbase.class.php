<?php
/**
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require("Tools/mimemessage/email_message.php");
require("Tools/mimemessage/smtp_message.php");
require("Tools/mimemessage/smtp.php");

/**
 * The Email Send Base class
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Clubdata
 */
class EmailSendBase
{

    var $smarty;
    var $formsgeneration;

    /** @var handle database handle*/
    var $db;

    /** @var FROM header of email*/
    var $from;

    /** @var Full Name for FROM header of email*/
    var $fromName;

    /** @var REPLY-TO header of email*/
    var $replyTo;

    /** @var Full Name for REPLY-TO header of email*/
    var $replyToName;

    /** @var Address of mail server*/
    var $mailHost;

    /** @var Needs mailserver authorization flag*/
    var $smtpAuth;

    /** @var Username for authorization*/
    var $username;

    /** @var Password for authorization*/
    var $password;

    /** @var Mailer used for sending emails (SMTP)*/
    var $mailer;

    /** @var SMTP-Debug flag*/
    var $smtpDebug;

    /** @var Send type of email (BCC, INDIV, PREVIEW)*/
    var $emailSendType;

    /** @var Object of email message class*/
    var $emailMsgObj;

    /** @var Send email as HTML flag*/
    var $isHTML;


    /**
    * Constructor of class EmailBase
    * @return integer Always OK
    */
    function EmailSendBase($db, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->from     = getConfigEntry($this->db, "Email");
        $this->fromName = getConfigEntry($this->db, "Emailname");
        $this->replyTo =  getConfigEntry($this->db, "ReplyTo");
        $this->replyToName = getConfigEntry($this->db, "ReplyToName");
        $this->mailHost     = getConfigEntry($this->db, "MailHost");
        $this->smtpAuth = getConfigEntry($this->db, "SMTPAuthorizing");
        $this->username = getConfigEntry($this->db, "SMTPUsername");
        $this->password = getConfigEntry($this->db, "SMTPPassword");
        $this->mailer   = "smtp";
        $this->smtpDebug = false;
        $this->emailSendType = getConfigEntry($this->db, "EmailSendType");
        $this->isHTML = (getConfigEntry($this->db,"EmailAsHTML") == "1");

        $this->emailMsgObj=new smtp_message_class;

        $this->emailMsgObj->SetEncodedEmailHeader("From",$this->from,$this->fromName);
        $this->emailMsgObj->SetEncodedEmailHeader("Reply-To",$this->replyTo, $this->replyToName);
        /*
        *  Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
        *  If you are using Windows, you need to use the smtp_message_class to set the return-path address.
        */
        if(defined("PHP_OS") && strcmp(substr(PHP_OS,0,3),"WIN"))
                $this->emailMsgObj->SetHeader("Return-Path",$this->from);
        $this->emailMsgObj->SetEncodedEmailHeader("Errors-To",$this->from,$this->fromName);

        /* This computer address */
        $this->emailMsgObj->localhost= php_uname('n');

        /* SMTP server address, probably your ISP address */
        $this->emailMsgObj->smtp_host=$this->mailHost;


        /* Deliver directly to the recipients destination SMTP server */
        $this->emailMsgObj->smtp_direct_delivery=0;

        /* In directly deliver mode, the DNS may return the IP of a sub-domain of
        * the default domain for domains that do not exist. If that is your
        * case, set this variable with that sub-domain address. */
        $this->emailMsgObj->smtp_exclude_address="";

        if ( getConfigEntry($this->db, "SMTPAuthorizing" ) )
        {
            /* authentication user name */
            $this->emailMsgObj->smtp_user=$this->username;

            /* authentication realm, usually empty */
            $this->emailMsgObj->smtp_realm="";

            /* authentication password */
            $this->emailMsgObj->smtp_password=$this->password;
        }

        /* if smtp_debug is 1,
        * set this to 1 to make the debug output appear in HTML */
        $this->emailMsgObj->smtp_html_debug=1;

        return true;
    }

    function emailFormular($optionArr = array())
    {
        global $APerr;

        $errTxt = array();

        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"hidden",
                        "NAME"=>'WRAP="VIRTUAL"Mailingtype',
                        "ID"=>'Mailingtype',
                        "VALUE"=>isset($optionArr["mailingType"]) ? $optionArr["mailingType"] : '',
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"text",
                        "NAME"=>'from',
                        "ID"=>'from',
                        "CLASS"=>"email",
                        "SIZE"=>30,
                        "LABEL"=>lang("From"),
                        "VALUE"=>isset($optionArr["from"]) ? $optionArr["from"] : '',
                        "Accessible"=>0
                        ));
        if ( !empty($this->replyTo) && $this->replyTo != $this->from )
        {
            $errTxt[] .= $this->formsgeneration->AddInput(array(
                            "TYPE"=>"text",
                            "NAME"=>'replyto',
                            "ID"=>'replyto',
                            "CLASS"=>"email",
                            "SIZE"=>30,
                            "LABEL"=>lang("Reply to"),
                            "VALUE"=>isset($optionArr["replyTo"]) ? $optionArr["replyTo"] : '',
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                            ));
        }
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"textarea",
                        "NAME"=>'send_to',
                        "ID"=>'send_to',
                        "CLASS"=>"email",
                        "STYLE"=>"height: 1cm;",
                        "ROWS"=>30,
                        "COLS"=>30,
                        "LABEL"=>lang("To"),
                        "VALUE"=>isset($optionArr["sendTo"]) ? $optionArr["sendTo"] : '',
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"textarea",
                        "NAME"=>'send_cc',
                        "ID"=>'send_cc',
                        "CLASS"=>"email",
                        "STYLE"=>"height: 1cm;",
                        "ROWS"=>30,
                        "COLS"=>30,
                        "LABEL"=>lang("CC"),
                        "VALUE"=>isset($optionArr["sendCC"]) ? $optionArr["sendCC"] : '',
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));

        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"textarea",
                        "NAME"=>'send_bcc',
                        "ID"=>'send_bcc',
                        "CLASS"=>"email",
                        "STYLE"=>"height: 2cm;",
                        "ExtraAttributes"=>array("WRAP" => "VIRTUAL"),
                        "ROWS"=>30,
                        "COLS"=>30,
                        "LABEL"=>lang("BCC"),
				        //FD20110106: Don't show recipient list to other members
                        "VALUE"=>(!isMember() ? (isset($optionArr["sendBcc"]) ? $optionArr["sendBcc"] : '') : lang('Recipients hidden')),
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"text",
                        "NAME"=>'subject',
                        "ID"=>'subject',
                        "CLASS"=>"email",
                        "SIZE"=>38,
                        "LABEL"=>lang("Subject"),
                        "VALUE"=>isset($optionArr["subject"]) ? $optionArr["subject"] : '',
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"textarea",
                        "NAME"=>'BODY_TXT',
                        "ID"=>'BODY_TXT',
                        "CLASS"=>"email",
                        "LABEL"=>lang("Text"),
                        "VALUE"=>isset($optionArr["bodyTxt"]) ? $optionArr["bodyTxt"] : '',
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"file",
                        "NAME"=>'attachfile',
                        "ID"=>'attachfile',
                        "CLASS"=>"email",
                        "SIZE"=>48,
                        "LABEL"=>lang("Attachment") . " 1",
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"file",
                        "NAME"=>'attachfile1',
                        "ID"=>'attachfile1',
                        "CLASS"=>"email",
                        "SIZE"=>48,
                        "LABEL"=>lang("Attachment") . " 2",
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"file",
                        "NAME"=>'attachfile2',
                        "ID"=>'attachfile2',
                        "CLASS"=>"email",
                        "SIZE"=>48,
                        "LABEL"=>lang("Attachment") . " 3",
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"file",
                        "NAME"=>'attachfile3',
                        "ID"=>'attachfile3',
                        "CLASS"=>"email",
                        "SIZE"=>48,
                        "LABEL"=>lang("Attachment") . " 4",
        				"Accessible" => isset($optionArr["accessible"]) ? $optionArr["accessible"] : true,
                        ));

        $errTxt[] .= $this->formsgeneration->LoadInputValues($this->formsgeneration->WasSubmitted("doit"));

        if ( count($errTxt = array_filter($errTxt)) )
        {
            $str = join("<BR>",$errTxt);
            $APerr->setFatal(__FILE__,__LINE__,$str);
        }
    }

    function insertEmailsMemberRelation($emailID, $mailinglist,$mailingType)
    {
        global $APerr;

        $ok = true;
        if ( empty($mailinglist) )
        {
            // No emails sent
            return NULL;
        }
        $mailinglist = "'" . preg_replace("/\s*,\s*/", "','", $mailinglist) . "'";

        debug('M_MAIL',"[insertEmailsMemberRelation] MAILINGLIST: $mailinglist");

        $sql = <<<_EOT_
        SELECT `###_Members`.MemberID
                    FROM `###_Members`, `###_Addresses`, `###_Addresses_Mailingtypes`, `###_Members_Attributes`
                    WHERE `###_Members`.MemberID = `###_Members_Attributes`.MemberID
                    AND `###_Members_Attributes`.Attributes_ref =3
                    AND `###_Members`.MemberID = `###_Addresses`.Adr_MemberID
                    AND `###_Addresses`.id = `###_Addresses_Mailingtypes`.AddressID
                    AND `###_Addresses_Mailingtypes`.Mailingtypes_ref = $mailingType
                    AND Email in ($mailinglist)
_EOT_;
        debug('M_MAIL', "[insertEmailsMemberRelation] SQL: $sql");

         $ok = true;
         if ( ($MemberIdArr = $this->db->GetCol($sql)) === false)
         {
            $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            $ok = false;
         }
         else
         {
            foreach ( $MemberIdArr as $val )
            {
                debug('M_MAIL',"Inserting Email %s to Member %s", $emailID, $val);
                $sql = "INSERT INTO `###_Members_Emails` VALUES ($val, $emailID)";
                if ( $this->db->Execute($sql) === false )
                {
                    $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    $ok = false;
                }
            }
        }
        return $ok;
    }

    function setIndivBodyText($emailTo, $bodyTXT)
    {
        global $APerr;

        $sql = <<<_EOT_
            SELECT *, `###_Salutation`.*
            FROM `###_Members`, `###_Salutation`
            WHERE `###_Salutation`.id = `###_Members`.Salutation_ref
            AND FirmEmail = '$emailTo' OR PrivatEmail = '$emailTo'
_EOT_;

        $anArr = $this->db->GetRow($sql)  or
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");


        foreach ($anArr as $key => $val)
        {
            //echo "KEY: $key => $val<BR>";
            $$key = $val;
        }

        eval("\$bodyTXT1 = \"$bodyTXT\";");
        //echo "BODY1:<BR>$bodyTXT1<BR>\n";

        return $bodyTXT1;
    }

    function br2nl( $data )
    {
        return preg_replace( array('!<br.*>!iU',
                                    '!<P>!iU',
                                    '!</P>!iU'),
                                array("\n",
                                    "\n\n",
                                    ""),
                                $data );
    }

    function insertEmailToDB($send_to, $send_cc, $send_bcc, $subject, $body, $attachments, $mailingtype)
    {
        global $APerr;

        $sqlTxt = <<<_EOT_
        INSERT INTO `###_Emails` (EmailFrom, EmailTo, EmailCC, EmailBCC, EmailSubject,
                                  EmailBody, EmailAttachedFiles,EmailEmailtype, EmailSendtime)
        VALUES
            (
            %s, %s, %s,%s,%s,%s,%s,%s,
            NOW()
            );
_EOT_;

        $sql = sprintf($sqlTxt,
                        $this->db->qstr(getConfigEntry($this->db, "Email") . " (". getConfigEntry($this->db, "Emailname") . ")"),
                        $this->db->qstr($send_to),
                        $this->db->qstr($send_cc),
                        $this->db->qstr($send_bcc),
                        $this->db->qstr($subject),
                        $this->db->qstr($body),
                        $this->db->qstr($attachements),
                        $this->db->qstr($mailingtype));

        if ( $this->db->Execute($sql) === false)
        {
            $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            $emailID = false;
        }
        else
        {
        	$emailID = $this->db->Insert_ID();
        }
        
        return $emailID;
    }

    function replaceImagesAsInline($bodyTxt, &$imgArr)
    {
        function doReplaceImagesAsInline($tag, &$imgArr)
        {
            /*
            *  An HTML message that requires any dependent files to be sent,
            *  like image files, style sheet files, HTML frame files, etc..,
            *  needs to be composed as a multipart/related message part.
            *  Different parts need to be created before they can be added
            *  to the message.
            *
            *  Parts can be created from files that can be opened and read.
            *  The data content type needs to be specified. The can try to guess
            *  the content type automatically from the file name.
            */
            // String file:// from beginning of string
            if ( strpos($tag, "file://") !== false )
            {
                $tag = substr($tag, 7);
            }
            if ( ! is_file($tag) )
            {
                echo "TAG: " . SCRIPTROOT . $tag . "<BR>$_SERVER[DOCUMENT_ROOT]$tag";

                if (is_file(SCRIPTROOT . $tag) )
                {
                    $tag = SCRIPTROOT . $tag;
                }
                elseif(is_file($_SERVER["DOCUMENT_ROOT"] . $tag) )
                {
                    $tag = $_SERVER["DOCUMENT_ROOT"] . $tag;
                }

            }
            $image=array(
                    "FileName"=>"$tag",
                    "Content-Type"=>"automatic/name",
                    "Disposition"=>"inline"
            );
            $this->emailMsgObj->CreateFilePart($image,$image_part);

            $imgArr[] = $image_part;
            print("imgArr = " . $imgArr[count($imgArr)-1] . "<BR>");
    /*
    *  Parts that need to be referenced from other parts,
    *  like images that have to be hyperlinked from the HTML,
    *  are referenced with a special Content-ID string that
    *  the class creates when needed.
    */
            $image_content_id=$this->emailMsgObj->GetPartContentID($image_part);

            print "IMG: $tag ($image_content_id)<BR>\n";
            return $image_content_id;
        }
        print "START: $bodyTxt<BR><BR>\n";
        $imgArr = array();
        $bodyTxt = preg_replace("/(<img\s+.*)src=\"([^\"]+)\"/e", "'\\1 src=\"cid:' . doReplaceImagesAsInline('\\2', \$imgArr) . '\"'", $bodyTxt);
        print "ENDE: <PRE>$bodyTxt</PRE><BR><BR>\n";

        return $bodyTxt;
    }

    function addAttachments()
    {
        $emailAttachedFiles = array();
//         phpinfo(INFO_VARIABLES);
        /*
        *  One or more additional parts may be added as attachments.
        *  In this case a file part is added from data provided directly from this script.
        */
        if ( !empty($_FILES["attachfile"]["name"]) )
        {
                $attachment=array(
                        "FileName"=>$_FILES["attachfile"]["tmp_name"],
                        "Name"=>$_FILES["attachfile"]["name"],
                        "Content-Type"=>$_FILES["attachfile"]["type"],
                        "Disposition"=>"attachment"
                );
                $this->emailMsgObj->AddFilePart($attachment);
                $emailAttachedFiles[] =  $_FILES["attachfile"]["name"];
                debug_r('M_MAIL',$attachment,"[addAttachments] Attachment1:");
      }
        if ( !empty($_FILES["attachfile1"]["name"]) )
        {
                $attachment=array(
                        "FileName"=>$_FILES["attachfile1"]["tmp_name"],
                        "Name"=>$_FILES["attachfile1"]["name"],
                        "Content-Type"=>$_FILES["attachfile1"]["type"],
                        "Disposition"=>"attachment"
                );
                $this->emailMsgObj->AddFilePart($attachment);
                $emailAttachedFiles[] =  $_FILES["attachfile1"]["name"];
                debug_r('M_MAIL',$attachment,"[addAttachments] Attachment2:");
        }
        if ( !empty($_FILES["attachfile2"]["name"]) )
        {
                $attachment=array(
                        "FileName"=>$_FILES["attachfile2"]["tmp_name"],
                        "Name"=>$_FILES["attachfile2"]["name"],
                        "Content-Type"=>$_FILES["attachfile2"]["type"],
                        "Disposition"=>"attachment"
                );
                $this->emailMsgObj->AddFilePart($attachment);
                $emailAttachedFiles[] =  $_FILES["attachfile2"]["name"];
                debug_r('M_MAIL',$attachment,"[addAttachments] Attachment3:");
        }
        if ( !empty($_FILES["attachfile3"]["name"]) )
        {
                $attachment=array(
                        "FileName"=>$_FILES["attachfile3"]["tmp_name"],
                        "Name"=>$_FILES["attachfile3"]["name"],
                        "Content-Type"=>$_FILES["attachfile3"]["type"],
                        "Disposition"=>"attachment"
                );
                $this->emailMsgObj->AddFilePart($attachment);
                $emailAttachedFiles[] =  $_FILES["attachfile3"]["name"];
                debug_r('M_MAIL',$attachment,"[addAttachments] Attachment4:");
        }
        return $emailAttachedFiles;
    }
}
?>