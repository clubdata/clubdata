<?php
/*
 * test_upload_progress.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_upload_progress.php,v 1.7 2008/08/16 05:12:37 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_ajax_submit.php');
	require('form_upload_progress.php');

	$form=new form_class;
 	$form->NAME='upload_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->ENCTYPE='multipart/form-data';
	$form->debug='error_log';
	$form->ResubmitConfirmMessage=
		'Are you sure you want to submit this form again?';
	$form->AddInput(array(
		'TYPE'=>'file',
		'NAME'=>'userfile',
		'ValidateAsNotEmpty'=>1,
		'ValidationErrorMessage'=>
			'It was not specified a valid file to upload'
	));
	$form->AddInput(array(
		'TYPE'=>'submit',
		'VALUE'=>'Upload',
		'NAME'=>'doit'
	));
	$form->AddInput(array(
		'TYPE'=>'hidden',
		'NAME'=>'MAX_FILE_SIZE',
		'VALUE'=>1000000
	));
	$form->AddInput(array(
		'TYPE'=>'custom',
		'NAME'=>'upload_progress',
		'ID'=>'upload_progress',
		'FeedbackElement'=>'feedback',
		'FeedbackFormat'=>
			'<center>
<table style="width: 200px" class="progress_container" border="1">
<tr><td style="width: {ACCURATE_PROGRESS}%;" class="progress_bar">
<tt>{PROGRESS}%</tt></td><td style="border-style: none;"></td>
</tr></table><br />
<tt>Uploaded {UPLOADED}B of {TOTAL}B<br />
Remaining time: {REMAINING}<br />
Average speed: {AVERAGE_SPEED}B/s<br />
Current speed: {CURRENT_SPEED}B/s</tt></center>',
		'CustomClass'=>'form_upload_progress_class'
	));

	/*
	 *  Handle client side events on the server side.
	 *  Do not output anything before these lines.
	 */
	$form->HandleEvent($processed);
	if($processed)
		exit;

	$form->LoadInputValues($form->WasSubmitted('doit'));
	$verify=array();
	if($form->WasSubmitted('doit'))
	{
		sleep(1);
		if(($error_message=$form->Validate($verify))=='')
			$doit=1;
		else
		{
			$doit=0;
			$error_message=HtmlEntities($error_message);
		}
	}
	else
	{
		$error_message='';
		$doit=0;
	}
	if(!$doit)
	{
		if(strlen($error_message))
		{
			Reset($verify);
			$focus=Key($verify);
		}
		else
			$focus='userfile';
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
	}
	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
to show upload file progress</title>
<style type="text/css"><!--
.progress_container { background-color: #c0c0c0; border-radius: 8px;
	-moz-border-radius: 8px; padding: 4px; }
.progress_bar { border-style: none; color: #000000; padding: 4px;
	background-color: #0000ff; background-image: url(progress.gif);
	text-align: center; }
// --></style>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
to show upload file progress</h1></center>
<hr />
<img src="progress.gif" width="0" height="0" alt="Progress" />
<?php

  if($doit)
	{
		$form->GetFileValues('userfile',$userfile_values);
?>
<h2><center>The file was uploaded.</center></h2>
<center><table>

<tr>
<th align="right">Uploaded file path:</th>
<td><tt><?php echo $userfile_values['tmp_name']; ?></tt></td>
</tr>

<tr>
<th align="right">Client file name:</th>
<td><tt><?php echo HtmlEntities($userfile_values['name']); ?></tt></td>
</tr>

<tr>
<th align="right">File type:</th>
<td><tt><?php echo $userfile_values['type']; ?></tt></td>
</tr>

<tr>
<th align="right">File size:</th>
<td><tt><?php echo $userfile_values['size']; ?></tt></td>
</tr>

</table></center>
<?php
	}
  else
  {
		$form->AddInputPart('upload_progress');
		$form->AddInputPart('MAX_FILE_SIZE');
		$form->StartLayoutCapture();
		$title='Form upload progress test - 1MB maximum size';
		$body_template='form_upload_body.html.php';
		include('templates/form_frame.html.php');
		$form->EndLayoutCapture();
	$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
