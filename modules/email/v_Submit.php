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

/**
 * Module class to send emails. It uses another class (e.g. BCC) to do the actual delivering
 * @see sendBCC
 *
 * @package Email
 */
class vSubmit {
    var $db;
    var $bodyTxt;
    var $subject;
    var $sendBcc;
    var $sendCc;
    var $sendTo;
    var $smarty;
    var $formsgeneration;
    
    /** @var Send type of email (BCC, INDIV, PREVIEW)*/
    var $emailSendType;
    
    function vSubmit($db, $memberID, $mailID, $mailingType, $smarty, $formsgeneration)
    {
        global $APerr;

        $this->db = $db;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;
        $this->mailingType = $mailingType;
        $this->emailSendType = getConfigEntry($db, "EmailSendType");
    }
    
    function displayView()
    {

    }
    
    function doAction()
    {
        global $APerr;
        
        $sendfile = "modules/email/" . $this->emailSendType . ".php";
        if ( file_exists($sendfile) )
        {
            // The included code defineds the function doSendEmails
            include($sendfile);
            $sendObjName = "send" . $this->emailSendType;

            $sendMailObj = new $sendObjName($this->db, $this->smarty, $this->formsgeneration);
            $result = $sendMailObj->doSendEmails();
        }
        else
        {
            $APerr->setFatal(__FILE__,__LINE__,lang("Cannot find mail send module: ") . $this->emailSendType,"FILE: $sendfile");    
        }
    }
}
?>