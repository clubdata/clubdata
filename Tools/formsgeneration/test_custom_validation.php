<?php
/*
 * This example serves to demonstrate how to develop a custom input that
 * implements a new type of validation not supported by the main forms class.
 * 
 * @(#) $Id: test_custom_validation.php,v 1.2 2007/03/02 05:58:27 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_custom_validation.php');

	$form=new form_class;
	$form->NAME='custom_validation_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->InvalidCLASS='invalid';
	$form->ShowAllErrors=0;
	$form->debug='trigger_error';
	$form->AddInput(array(
		'TYPE'=>'text',
		'ID'=>'first',
		'NAME'=>'first',
		'LABEL'=>'<u>F</u>irst name',
		'ACCESSKEY'=>'F',
		'ValidateAsNotEmpty'=>1,
		'ValidationErrorMessage'=>'It was not specified a valid first name.'
	));
	$form->AddInput(array(
		'TYPE'=>'text',
		'ID'=>'second',
		'NAME'=>'second',
		'LABEL'=>'<u>S</u>econd name',
		'ACCESSKEY'=>'S',
		'ValidateAsNotEmpty'=>1,
		'ValidationErrorMessage'=>'It was not specified a valid second name.'
	));

	/*
	 *  Add a custom input that will be used only for validation purposes
	 */
	$error=$form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'validation',

		/*
		 *  Specify the custom plug-in input class name.
		 */
		'CustomClass'=>'form_custom_validation_class',

		/*
		 *  Specify some custom parameters specific of this plug-in input
		 */
		'FirstInput'=>'first',
		'FirstValidationErrorMessage'=>'The first name is contained in the second name.',
		'SecondInput'=>'second',
		'SecondValidationErrorMessage'=>'The second name is contained in the first name.',
	));

	/*
	 *  If something went wrong, probably due to missing or invalid parameters,
	 *  it is safer to exit the script so the rest of the script does not execute
	 */
	if(strlen($error))
		die('Error: '.$error);

	$form->AddInput(array(
		'TYPE'=>'submit',
		'VALUE'=>'Submit',
		'NAME'=>'doit'
	));


/*
 * The following lines are for testing purposes.
 * Remove these lines when adapting this example to real applications.
 */
	if(defined("__TEST"))
	{
		if(IsSet($__test_options["ShowAllErrors"]))
			$form->ShowAllErrors=$__test_options["ShowAllErrors"];
	}

	$focus='first';
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
			Reset($verify);
			$focus=Key($verify);
		}
	}
	else
	{
		$error_message='';
		$doit=0;
	}

	if(!$doit)
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using a custom validation plug-in input</title>
<style type="text/css"><!--
.invalid { border-color: #ff0000; background-color: #ffcccc; }
// --></style>
</head>
<body onload="<?php	echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using a custom validation plug-in input</h1></center>
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
		$title='Form custom validation plug-in test';
		$body_template='form_custom_validation.html.php';
		include('templates/form_frame.html.php');
		$form->EndLayoutCapture();

	/*
	 *  The custom validation input must also be added to the form output,
	 *  even though it is not a visible input in the form
	 */
		$form->AddInputPart('validation');

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
