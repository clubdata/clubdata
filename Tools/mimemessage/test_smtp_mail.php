<?php
/*
 * test_smtp_mail.php
 *
 * @(#) $Header: /cvsroot/clubdata/Clubdata2/include/mimemessage/test_smtp_mail.php,v 1.1.1.1 2006/02/04 20:43:36 domes Exp $
 *
 *
 */

require("smtp_mail.php");

$message_object->smtp_host="localhost";
$message_object->smtp_debug=1;

/*
 *  Change these variables to specify your test sender and recipient addresses
 */
$from="mlemos@acm.org";
$to="mlemos@acm.org";

$subject="Testing smtp_mail function";
$message="Hello,\n\nThis message is just to let you know that the smtp_mail() function is working fine as expected.\n\n$from";
$additional_headers="From: $from";
$additional_parameters="-f ".$from;
if(smtp_mail($to,$subject,$message,$additional_headers,$additional_parameters))
echo "Ok.";
else
echo "Error: ".$message_object->error."\n";

?>