<?php
/*
 * test_upload.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_upload.php,v 1.8 2007/07/02 03:07:56 mlemos Exp $
 *
 */

	require("forms.php");

	$form=new form_class;
	$form->NAME="subscription_form";
	$form->METHOD="POST";
	$form->ACTION="";
	$form->ENCTYPE="multipart/form-data";
	$form->debug="trigger_error";
	$form->ResubmitConfirmMessage="Are you sure you want to submit this form again?";
	$form->AddInput(array(
		"TYPE"=>"file",
		"NAME"=>"userfile",
		"ACCEPT"=>"image/gif",
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid file to upload"
	));
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Upload",
		"NAME"=>"doit"
	));
	$form->AddInput(array(
		"TYPE"=>"hidden",
		"NAME"=>"MAX_FILE_SIZE",
		"VALUE"=>1000000
	));
	$form->LoadInputValues($form->WasSubmitted("doit"));
	$verify=array();
	if($form->WasSubmitted("doit"))
	{
		if(($error_message=$form->Validate($verify))=="")
			$doit=1;
		else
		{
			$doit=0;
			$error_message=HtmlEntities($error_message);
		}
	}
	else
	{
		$error_message="";
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
<title>Test for Manuel Lemos' PHP form class to upload a file</title>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<h1><center>Test for Manuel Lemos' PHP form class to upload a file</center></h1>
<hr />
<?php
	if($doit)
	{
		$form->GetFileValues("userfile",$userfile_values);
?>
<h2><center>The file was uploaded.</center></h2>
<center><table>

<tr>
<th align="right">Uploaded file path:</th>
<td><tt><?php echo $userfile_values["tmp_name"]; ?></tt></td>
</tr>

<tr>
<th align="right">Client file name:</th>
<td><tt><?php echo HtmlEntities($userfile_values["name"]); ?></tt></td>
</tr>

<tr>
<th align="right">File type:</th>
<td><tt><?php echo $userfile_values["type"]; ?></tt></td>
</tr>

<tr>
<th align="right">File size:</th>
<td><tt><?php echo $userfile_values["size"]; ?></tt></td>
</tr>

</table></center>
<?php
	}
  else
  {
		$form->StartLayoutCapture();
		$title="Form upload file test";
		$body_template="form_upload_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
