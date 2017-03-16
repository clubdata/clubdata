<?php
/*
 * test_list_select.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_list_select.php,v 1.3 2009/11/21 08:44:46 mlemos Exp $
 *
 */

	require("forms.php");
	require("form_list_select.php");
	
	$currencies = array(
		''=>'None',
		'USD'=>'USD',
		'EUR'=>'EUR',
		'GPB'=>'GBP',
		'YEN'=>'YEN'
	);

	$form=new form_class;
	$form->NAME="currency_form";
	$form->METHOD="POST";
	$form->ACTION="";
	$form->debug="error_log";
	$form->AddInput(array(
		"TYPE"=>"custom",
		"ID"=>"currency",
		"NAME"=>"currency",
		"LABEL"=>"<u>C</u>urrency",
		"ACCESSKEY"=>"t",
		"CustomClass"=>"form_list_select_class",
		"VALUE"=>"",
		"OPTIONS"=>$currencies,
		'Columns'=>array(
			array(
				'Type'=>'Input'
			),
			array(
				'Type'=>'Option',
				'Header'=>'Symbol'
			),
			array(
				'Type'=>'Data',
				'Header'=>'Name',
				'Row'=>'Name',
			),
			array(
				'Type'=>'Data',
				'Header'=>'Region',
				'Row'=>'Region',
			),
			array(
				'Type'=>'Data',
				'Header'=>'Value',
				'Row'=>'Value',
			),
		),
		'Rows'=>array(
			'USD'=>array(
				'Name'=>'Dollar',
				'Region'=>'United States',
				'Value'=>'<tt>$1.00</tt>'
			),
			'EUR'=>array(
				'Name'=>'Euro',
				'Region'=>'Europe',
				'Value'=>'<tt>$1.4986</tt>'
			),
			'GPB'=>array(
				'Name'=>'Pound',
				'Region'=>'United Kingdom',
				'Value'=>'<tt>$1.6737</tt>'
			),
			'YEN'=>array(
				'Name'=>'Yen',
				'Region'=>'Japan',
				'Value'=>'<tt>$0.011132</tt>'
			),
		)
	));
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Choose",
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
	{
		if(strlen($error_message))
		{
			Reset($verify);
			$focus=Key($verify);
		}
		else
			$focus='currency';
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
	}

	$onload = HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the list select plug-in input</title>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
using the list select plug-in input</h1></center>
<hr />
<?php
  if($doit)
	{
		$currency=$form->GetInputValue("currency");
?>
<center><h2>The chosen currency is <tt><?php echo $currencies[$currency]; ?></tt>.</h2></center>
<?php
	}
	else
	{
		$form->StartLayoutCapture();
		$title="List select plug-in test";
		$body_template="form_list_select_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
