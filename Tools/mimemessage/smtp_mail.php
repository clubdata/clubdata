<?php
/*
 * smtp_mail.php
 *
 * @(#) $Header: /cvsroot/clubdata/Clubdata2/include/mimemessage/smtp_mail.php,v 1.1.1.1 2006/02/04 20:43:39 domes Exp $
 *
 *
 */

require_once("email_message.php");
require_once("smtp_message.php");
require_once("smtp.php");

$message_object=new smtp_message_class;
$message_object->localhost="localhost";   /* This computer address */
$message_object->smtp_host="localhost";   /* SMTP server address */
$message_object->smtp_direct_delivery=0;  /* Deliver directly to the recipients destination SMTP server */
$message_object->smtp_exclude_address=""; /* In directly deliver mode, the DNS may return the IP of a sub-domain of the default domain for domains that do not exist. If that is your case, set this variable with that sub-domain address. */
/*
 * If you use the direct delivery mode and the GetMXRR is not functional, you need to use a replacement function.
 */
/*
 $_NAMESERVERS=array();
 include("rrcompat.php");
 $message_object->smtp_getmxrr="_getmxrr";
 */
$message_object->smtp_user="";            /* authentication user name */
$message_object->smtp_realm="";           /* authentication realm */
$message_object->smtp_password="";        /* authentication password */
$message_object->smtp_pop3_auth_host="";  /* if you need POP3 authetntication before SMTP delivery, specify the host name here. The smtp_user and smtp_password above should set to the POP3 user and password */
$message_object->smtp_debug=0;            /* Output dialog with SMTP server */
$message_object->smtp_html_debug=1;       /* If smtp_debug is 1, set this to 1 to output debug information in HTML */

Function smtp_mail($to,$subject,$message,$additional_headers="",$additional_parameters="")
{
	global $message_object;

	return($message_object->Mail($to,$subject,$message,$additional_headers,$additional_parameters));
}

?>