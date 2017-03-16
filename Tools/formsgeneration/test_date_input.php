<?php
/*
 *
 * @(#) $Id: test_date_input.php,v 1.9 2009/05/26 04:39:08 mlemos Exp $
 *
 */

	require("forms.php");
	require("form_date.php");

	$day_seconds=60*60*24;
	$start_date=strftime("%Y-%m-%d",time()+1*$day_seconds);
	$end_date=strftime("%Y-%m-%d",time()+7*$day_seconds);
	$today='now';
	$form=new form_class;
	$form->NAME="date_form";
	$form->METHOD="POST";
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
		"ID"=>"date",
		"LABEL"=>"<u>D</u>ate",
		"ACCESSKEY"=>"D",
		"CustomClass"=>"form_date_class",
		"VALUE"=>$today,
		"Format"=>"{day}/{month}/{year}",
		"Months"=>array(
			"01"=>"January",
			"02"=>"February",
			"03"=>"March",
			"04"=>"April",
			"05"=>"May",
			"06"=>"June",
			"07"=>"July",
			"08"=>"August",
			"09"=>"September",
			"10"=>"October",
			"11"=>"November",
			"12"=>"December"
		),
		"Optional"=>1,
		"ValidationStartDate"=>$start_date,
		"ValidationStartDateErrorMessage"=>"It was specified a schedule date before the start date.",
		"ValidationEndDate"=>$end_date,
		"ValidationEndDateErrorMessage"=>"It was specified a schedule date after the end date.",
	));

/*
 * The following lines are for testing purposes.
 * Remove these lines when adapting this example to real applications.
 */
	if(defined("__TEST"))
	{
		if(IsSet($__test_options["set_date"]))
		{
			$form->SetInputValue('date', $__test_options["set_date"]);
			echo $form->GetInputValue('date');
			return;
		}
	}
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Schedule",
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
		$form->ConnectFormToInput('date', 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the date plug-in input</title>
<style type="text/css"><!--
.invalid { border-color: #ff0000; background-color: #ffcccc; }
// --></style>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using the date plug-in input</h1></center>
<hr />
<?php
  if($doit)
	{
		$date=$form->GetInputValue("date");
		if(strlen($date))
		{
?>
<center><h2>The task is scheduled to be started on <?php echo $date; ?></h2></center>
<?php
		}
		else
		{
?>
<center><h2>The task was not scheduled.</h2></center>
<?php
		}
	}
	else
	{
		$form->StartLayoutCapture();
		$title="Form Date plug-in test";
		$body_template="form_date_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();
		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
