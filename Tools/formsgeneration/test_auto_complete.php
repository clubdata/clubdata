<?php
/*
 * test_auto_complete.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_auto_complete.php,v 1.9 2006/12/20 06:21:16 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_ajax_submit.php');
	require('form_auto_complete.php');

	$form=new form_class;
	$form->NAME='auto_complete_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->debug='error_log';
	$form->AddInput(array(
		'TYPE'=>'text',
		'ID'=>'color',
		'NAME'=>'color',
		'LABEL'=>'<u>C</u>olor',
		'ACCESSKEY'=>'C',
		'VALUE'=>'',
		'SIZE'=>32,
		'ExtraAttributes'=>array(
			'autocomplete'=>'off'
		)
	));
	$form->AddInput(array(
		'TYPE'=>'button',
		'NAME'=>'show_colors',
		'ID'=>'show_colors',
		'VALUE'=>'...'
	));
	$form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'complete_color',
		'NAME'=>'complete_color',
		'CustomClass'=>'form_auto_complete_class',
		'CompleteInput'=>'color',
		'CompleteMinimumLength'=>1,
		'CompleteValues'=>array(
			'Black'=>'
				<span style="background-color: black; color: white;">B</span>lack',
			'Blue'=>
				'<span style="background-color: blue; color: white;">B</span>lue',
			'Cyan'=>
				'<span style="background-color: cyan;  color: #000000">C</span>yan',
			'Green'=>
				'<span style="background-color: green; color: white;">G</span>reen',
			'Magenta'=>
				'<span style="background-color: magenta">M</span>agenta',
			'Red'=>
				'<span style="background-color: red">R</span>ed',
			'White'=>
				'<span style="background-color: white; color: #000000">W</span>hite',
			'Yellow'=>
				'<span style="background-color: yellow; color: #000000">Y</span>ellow',
		),
		'Dynamic'=>1,
		'ShowButton'=>'show_colors',
		'FeedbackElement'=>'complete_color_feedback',
		'SubmitFeedback'=>'
			<img src="indicator.gif" width="16" height="16" alt="Looking up for colors" title="Looking up for colors" /> ',
		'CompleteFeedback'=>'
			<img src="indicator.gif" width="16" height="16" style="visibility: hidden;" /> ',
	));
	$form->AddInput(array(
		'TYPE'=>'text',
		'ID'=>'font',
		'NAME'=>'font',
		'LABEL'=>'<u>F</u>ont',
		'ACCESSKEY'=>'F',
		'VALUE'=>'',
		'SIZE'=>32,
		'ExtraAttributes'=>array(
			'autocomplete'=>'off'
		)
	));
	$form->AddInput(array(
		'TYPE'=>'image',
		'NAME'=>'show_fonts',
		'ID'=>'show_fonts',
		'SRC'=>'pulldown.gif',
		'ALT'=>'Show fonts',
		'ALIGN'=>'top'
	));
	$form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'complete_font',
		'NAME'=>'complete_font',
		'CustomClass'=>'form_auto_complete_class',
		'CompleteInput'=>'font',
		'CompleteMinimumLength'=>1,
		'CompleteValues'=>array(
			'sans-serif'=>
				'<span style="font-family: sans-serif; float: right">ABC</span>Sans Serif',
			'serif'=>
				'<span style="font-family: serif; float: right">ABC</span>Serif',
			'cursive'=>
				'<span style="font-family: cursive; float: right">ABC</span>Cursive',
			'fantasy'=>
				'<span style="font-family: fantasy; float: right">ABC</span>Fantasy',
			'monospace'=>
				'<span style="font-family: monospace; float: right">ABC</span>Monospace',
		),
		'Dynamic'=>0,
		'MenuStyle'=>'',
		'MenuClass'=>'groovymenu',
		'ItemStyle'=>'',
		'SelectedItemStyle'=>'',
		'ItemClass'=>'groovyitem',
		'SelectedItemClass'=>'groovyselecteditem',
		'ShowButton'=>'show_fonts',
	));

	/*
	 * This code is necessary to handle the requests for fetching
	 * auto-complete values.
	 */
	$form->HandleEvent($processed);
	if($processed)
		exit;

	$form->ConnectFormToInput('color', 'ONLOAD', 'Focus', array());

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the auto-complete plug-in input</title>
<style type="text/css"><!--
BODY { color: black; font-family: arial, helvetica, sans-serif;
	background-color: #cccccc }
.groovymenu { background-color: #cccccc; padding: 4px; border-style: solid;
	border-top-color: #f9f9f9; border-left-color: #f9f9f9;
	border-bottom-color: #868686; border-right-color: #868686;
	border-width: 1px; opacity: 0.9; filter: alpha(opacity=90); }
.groovyitem { padding: 1px; }
.groovyselecteditem { padding: 1px; color: #ffffff;
	background-color: #000080; }
// --></style>
</head>
<body onload="<?php	echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
using the auto-complete plug-in input</h1></center>
<hr />
<?php
	$error_message='';
	$form->StartLayoutCapture();
	$title='Auto-complete plug-in test';
	$body_template='form_auto_complete_body.html.php';
	include('templates/form_frame.html.php');
	$form->EndLayoutCapture();
	$form->DisplayOutput();
?>
<hr />
</body>
</html>
