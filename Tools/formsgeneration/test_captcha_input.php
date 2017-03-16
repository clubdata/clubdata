<?php
/*
 *
 * @(#) $Id: test_captcha_input.php,v 1.9 2006/12/20 06:21:16 mlemos Exp $
 *
 */

	require("forms.php");
	require("form_captcha.php");

	$key="my secret key";
	$form=new form_class;
	$form->NAME="captcha_form";
	$form->METHOD="POST";
	$form->ACTION="";
	$form->debug="error_log";
	$error=$form->AddInput(array(
		"TYPE"=>"custom",
		"ID"=>"captcha",
		"LABEL"=>"<u>E</u>nter the following text:",
		"ACCESSKEY"=>"E",
		"CustomClass"=>"form_captcha_class",
		"Key"=>$key,
/*		"Format"=>"{image} {text} {redraw}{validation}", */
/*		"ImageWidth"=>80, */
/*		"ImageHeight"=>20, */
/*		"TextLength"=>4, */
/*		"TextColor"=>"#000000", */
		"ImageFormat"=>"png",
		"NoiseFromPNGImage"=>"noise.png",
		/*
		 * If you have installed GD with GIF support, you may uncomment these
		 * lines.
		 */
/*		"ImageFormat"=>"gif", */
/*		"NoiseFromGIFImage"=>"noise.gif", */
		"ResetIncorrectText"=>1,
		"BackgroundColor"=>"#FFFFFF",
		"ValidationErrorMessage"=>"It was not entered the correct text.",
		"ExpiryTime"=>60,
		"ExpiryTimeValidationErrorMessage"=>"The validation text has expired."
	));
	if(strlen($error))
		die("Error: ".$error);
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Submit",
		"NAME"=>"doit"
	));


	/*
	 * This code is necessary to handle the requests for serving the captcha
	 * image.
	 * Do not remove it nor output any data or headers before these lines.
	 */
	$form->HandleEvent($processed);
	if($processed)
		exit;


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
		$form->ConnectFormToInput('captcha', 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the CAPTCHA plug-in input</title>
</head>
<body onload="<?php	echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using the CAPTCHA plug-in input</h1></center>
<hr />
<?php
  if($doit)
	{
?>
<center><h2>The entered text <?php echo $form->GetInputValue("captcha"); ?> is correct.</h2></center>
<?php
	}
	else
	{
		$form->StartLayoutCapture();
		$title="Form CAPTCHA plug-in test";
		$body_template="form_captcha_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();
		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
