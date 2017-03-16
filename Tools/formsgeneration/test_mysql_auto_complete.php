<?php
/*
 * test_mysql_auto_complete.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_mysql_auto_complete.php,v 1.2 2006/12/20 06:21:17 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_ajax_submit.php');
	require('form_auto_complete.php');
	require('form_mysql_auto_complete.php');

	$host="localhost";
	$user="mysqluser";
	$password="mysqlpassword";
	$database="locations";
	$connection=@mysql_pconnect($host, $user, $password);
	if($connection)
		mysql_select_db($database,$connection);

	$form=new form_class;
	$form->NAME='auto_complete_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->debug='error_log';
	$form->AddInput(array(
		'TYPE'=>'text',
		'ID'=>'location',
		'NAME'=>'location',
		'LABEL'=>'<u>L</u>ocation',
		'ACCESSKEY'=>'L',
		'VALUE'=>'',
		'ExtraAttributes'=>array(
			'autocomplete'=>'off',
			'title'=>'Type just the first letters of a location.',
		)
	));
	$form->AddInput(array(
		'TYPE'=>'button',
		'NAME'=>'show_locations',
		'ID'=>'show_locations',
		'VALUE'=>'...',
		'ExtraAttributes'=>array(
			'title'=>'Click to show all locations.'
		)
	));
	$form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'complete_location',
		'NAME'=>'complete_location',
		'CustomClass'=>'form_mysql_auto_complete_class',
		'CompleteInput'=>'location',
		'CompleteMinimumLength'=>1,
		'Dynamic'=>1,
		'ShowButton'=>'show_locations',
		"Connection"=>$connection,
		"CompleteValuesQuery"=>"SELECT name FROM locations WHERE name {BEGINSWITH} ORDER BY name",
		"CompleteValuesLimit"=>10,
		'FeedbackElement'=>'complete_location_feedback',
		'SubmitFeedback'=>' <img src="indicator.gif" width="16" height="16" alt="Looking up for locations" title="Looking up for locations" /> ',
		'CompleteFeedback'=>' <img src="indicator.gif" width="16" height="16" style="visibility: hidden;" /> ',
		'MenuClass'=>'groovymenu',
		'MenuStyle'=>'',
		'ItemClass'=>'groovyitem',
		'ItemStyle'=>'',
		'SelectedItemClass'=>'groovyselecteditem',
		'SelectedItemStyle'=>'',
	));

	/*
	 * This code is necessary to handle the requests for fetching
	 * auto-complete values.
	 */
	$form->HandleEvent($processed);
	if($processed)
		exit;

	$form->ConnectFormToInput('location', 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the auto-complete plug-in input and MySQL database queries</title>
<style type="text/css"><!--
BODY { color: black ; font-family: arial, helvetica, sans-serif ; background-color: #cccccc }
.groovymenu { background-color: #cccccc; padding: 4px; border-style: solid ; border-top-color: #f9f9f9 ; border-left-color: #f9f9f9 ; border-bottom-color: #868686 ; border-right-color: #868686 ; border-width: 1px; opacity: 0.9; filter: alpha(opacity=90); }
.groovyitem { padding: 1px; }
.groovyselecteditem { padding: 1px; color: #ffffff; background-color: #000080; }
// --></style>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using the auto-complete plug-in input and MySQL database queries</h1></center>
<hr />
<?php
	$error_message='';
	$form->StartLayoutCapture();
	$title='Auto-complete plug-in test';
	$body_template='form_locations_auto_complete_body.html.php';
	include('templates/form_frame.html.php');
	$form->EndLayoutCapture();

	$form->DisplayOutput();

	if($connection)
		mysql_close($connection);
?>
<hr />
</body>
</html>
