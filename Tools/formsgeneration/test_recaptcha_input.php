<?php
/*
 *
 * @(#) $Id: test_recaptcha_input.php,v 1.2 2013/01/01 10:32:54 mlemos Exp $
 *
 */

	require('forms.php');
	require('http.php');
	require('form_recaptcha.php');

	$key = ''; $line = __LINE__;
	$private_key = '';
	if(strlen($key) == 0)
		die('Please go to the Recaptcha site '.
		'https://www.google.com/recaptcha/admin/create to obtain the public '.
		'and private keys to set in the line '.$line.'.');
	$form = new form_class;
	$form->NAME = 'captcha_form';
	$form->METHOD = 'GET';
	$form->ACTION = '';
	$form->debug = 'error_log';
	$error = $form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'captcha',
		'LABEL'=>'<u>E</u>nter the following text:',
		'ACCESSKEY'=>'E',
		'CustomClass'=>'form_recaptcha_class',
		'Key'=>$key,
		'PrivateKey'=>$private_key,
		// 'ValidationErrorMessage'=>'It was not entered the correct text.',
		// 'DependentValidation'=>'',
		// 'InputClass'=>'',
		// 'InputStyle'=>'',
		// 'InputTabIndex'=>10,
		// 'InputExtraAttributes'=>array(),
		/* 'Format'=>'<div>{image}</div>
		 <div>{instructions_visual}{instructions_audio} {input}</div>
		 <div>{refresh_btn} {visual_challenge}{audio_challenge} {help_btn}</div>',*/
		/* 'Text'=>array(
			'instructions_visual'=>'Enter the words above',
			'instructions_audio'=>'Enter the numbers you hear',
			'visual_challenge'=>'Enter text in an image instead',
			'audio_challenge'=>'Enter numbers you hear instead',
			'refresh_btn'=>'Try another',
			'help_btn'=>'Help',
			'play_again'=>'Play the sound again',
			'cant_hear_this'=>'Download the sound as a MP3 file',
			'image_alt_text'=>'Image with text to enter'
		) */
	));
	if(strlen($error))
		die('Error: '.$error);
	$form->AddInput(array(
		'TYPE'=>'submit',
		'VALUE'=>'Submit',
		'NAME'=>'doit'
	));

	/*
	 * Always check if LoadInputValues returns any errors to detect any
	 * configuration or reCAPTCHA access problem
	 */
	if(strlen($error = $form->LoadInputValues($form->WasSubmitted('doit'))))
		die('Error processing reCAPTCHA response: '.$error);

	$verify = array();
	if($form->WasSubmitted('doit'))
	{
		if(($error_message = $form->Validate($verify))=='')
			$doit = 1;
		else
		{
			$doit = 0;
			$error_message = HtmlSpecialChars($error_message);
		}
	}
	else
	{
		$error_message = '';
		$doit = 0;
	}

	if(!$doit)
		$form->ConnectFormToInput('captcha', 'ONLOAD', 'Focus', array());

	$onload = HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the reCAPTCHA plug-in input</title>
</head>
<body onload="<?php	echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using the reCAPTCHA plug-in input</h1></center>
<hr />
<?php
  if($doit)
	{
?>
<center><h2>The entered text <?php echo $form->GetInputValue('captcha'); ?> is correct.</h2></center>
<?php
	}
	else
	{
		$form->StartLayoutCapture();
		$title = 'Form CAPTCHA plug-in test';
		$body_template = 'form_captcha_body.html.php';
		include('templates/form_frame.html.php');
		$form->EndLayoutCapture();
		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
