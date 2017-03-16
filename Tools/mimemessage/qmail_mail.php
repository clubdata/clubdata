<?php
/*
 * qmail_mail.php
 *
 * @(#) $Header: /cvsroot/clubdata/Clubdata2/include/mimemessage/qmail_mail.php,v 1.1.1.1 2006/02/04 20:43:37 domes Exp $
 *
 *
 */

require_once("email_message.php");
require_once("qmail_message.php");

$message_object=new qmail_message_class;

Function qmail_mail($to,$subject,$message,$additional_headers="",$additional_parameters="")
{
	global $message_object;

	return($message_object->Mail($to,$subject,$message,$additional_headers,$additional_parameters));
}

?>