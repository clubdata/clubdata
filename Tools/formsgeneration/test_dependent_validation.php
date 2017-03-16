<?php
/*
 * This example serves to demonstrate how to validate an input depending
 * on the state of another input.
 *
 * @(#) $Id: test_dependent_validation.php,v 1.1 2007/06/02 08:37:21 mlemos Exp $
 *
 */

	require('forms.php');

	$form=new form_class;
	$form->NAME='dependent_validation_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->InvalidCLASS='invalid';
	$form->ShowAllErrors=0;
	$form->debug='trigger_error';
	$form->AddInput(array(
		'TYPE'=>'checkbox',
		'ID'=>'condition',
		'NAME'=>'condition',
		'CHECKED'=>1,
		'LABEL'=>'<u>V</u>alidate',
		'ACCESSKEY'=>'V'
	));
	$form->AddInput(array(
		'TYPE'=>'text',
		'ID'=>'dependent',
		'NAME'=>'dependent',
		'LABEL'=>'<u>D</u>ependent',
		'ACCESSKEY'=>'D',
		'ValidateAsNotEmpty'=>1,
		'ValidationErrorMessage'=>'It was not entered a value in the dependent field.',
		'DependentValidation'=>'condition'
	));

	$form->AddInput(array(
		'TYPE'=>'submit',
		'VALUE'=>'Submit',
		'NAME'=>'doit'
	));

	$form->LoadInputValues($form->WasSubmitted('doit'));
	$verify=array();
	if($form->WasSubmitted('doit'))
	{
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
		$form->ConnectFormToInput('dependent', 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class for dependent validation</title>
<style type="text/css"><!--
.invalid { border-color: #ff0000; background-color: #ffcccc; }
// --></style>
</head>
<body onload="<?php	echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class for dependent validation</h1></center>
<hr />
<?php
  if($doit)
	{
?>
<center><h2>OK</h2></center>
<?php
	}
	else
	{
		$form->StartLayoutCapture();
		$title='Form dependent validation test';
		$body_template='form_dependent_validation.html.php';
		include('templates/form_frame.html.php');
		$form->EndLayoutCapture();

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
