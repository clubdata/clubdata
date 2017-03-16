<?php
/*
 *
 * @(#) $Id: test_age_date_input.php,v 1.2 2009/05/26 04:39:08 mlemos Exp $
 *
 */

	require("forms.php");
	require("form_date.php");

	$today='now';
	$start_date='1995-01-01';
	$end_date=$today;
	$form=new form_class;
	$form->NAME="experience_form";
	$form->METHOD="GET";
	$form->ACTION="";
	$form->debug="trigger_error";
	$form->InvalidCLASS='invalid';
	$form->ShowAllErrors=1;

/*
 * The following lines are for testing purposes.
 * Remove these lines when adapting this example to real applications.
 */
	if(defined("__TEST"))
	{
		if(IsSet($__test_options["ShowAllErrors"]))
			$form->ShowAllErrors=$__test_options["ShowAllErrors"];
		if(IsSet($__test_options["today"]))
			$today=$__test_options["today"];
		if(IsSet($__test_options["start_date"]))
			$start_date=$__test_options["start_date"];
		if(IsSet($__test_options["end_date"]))
			$end_date=$__test_options["end_date"];
	}

	$form->AddInput(array(
		"TYPE"=>"custom",
		"ID"=>"experience",
		"LABEL"=>"Your PHP <u>E</u>xperience",
		"ACCESSKEY"=>"E",
		"CustomClass"=>"form_date_class",
		"VALUE"=>'',
		"AskAge"=>1,
		"HideDay"=>1,
		"FixedDay"=>1,
		"Format"=>"{year} years and {month} months",
		"ValidationStartDate"=>$start_date,
		"ValidationStartDateErrorMessage"=>"You cannot have that long PHP experience as PHP was only released in 1995.",
		"ValidationEndDate"=>$end_date,
		"ValidationEndDateErrorMessage"=>"You have not specified a valid experience period.",
	));

/*
 * The following lines are for testing purposes.
 * Remove these lines when adapting this example to real applications.
 */
	if(defined("__TEST"))
	{
		if(IsSet($__test_options["set_date"]))
		{
			$form->SetInputValue('experience', $__test_options["set_date"]);
			echo $form->GetInputValue('experience');
			return;
		}
	}
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Submit",
		"NAME"=>"doit"
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
		$form->ConnectFormToInput('experience', 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the date plug-in input to pick up an age value</title>
<style type="text/css"><!--
.invalid { border-color: #ff0000; background-color: #ffcccc; }
// --></style>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using the date plug-in input to pick up an age value</h1></center>
<hr />
<?php
  if($doit)
	{
		$date=$form->GetInputValue("experience");
?>
<center><h2>You have started working with PHP approximately on <?php echo $date; ?> .</h2></center>
<?php
	}
	else
	{
		$form->StartLayoutCapture();
		$title="Form Date plug-in test to pick up an age";
		$body_template="form_age_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();
		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
