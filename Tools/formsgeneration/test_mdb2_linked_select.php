<?php
/*
 * test_mdb2_linked_select.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_mdb2_linked_select.php,v 1.2 2006/12/20 06:21:17 mlemos Exp $
 *
 */

	require 'MDB2.php';
	require 'forms.php';
	require 'form_linked_select.php';
	require 'form_mdb2_linked_select.php';

	$dsn=array(
		"phptype"=>"mysql",
		"username"=>"mysqluser",
		"password"=>"mysqlpassword",
		"database"=>"locations",
		"Debug"=>"error_log",
	);
	$database =& MDB2::connect($dsn);
	if (PEAR::isError($database)) {
		var_dump($database->getMessage());
		die();
	}

	$continents=array(
		""=>"Select continent",
		"na"=>"North America",
		"eu"=>"Europe",
		"sa"=>"South America",
		"as"=>"Asia",
		"oc"=>"Oceania"
	);

	$form=new form_class;
	$form->NAME="location_form";
	$form->METHOD="GET";
	$form->ACTION="";
	$form->debug="OutputDebug";
	$form->AddInput(array(
		"TYPE"=>"select",
		"ID"=>"continent",
		"NAME"=>"continent",
		"LABEL"=>"<u>C</u>ontinent",
		"ACCESSKEY"=>"C",
		"VALUE"=>"",
		"OPTIONS"=>$continents,
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid continent."
	));
	$form->AddInput(array(
		"TYPE"=>"custom",
		"ID"=>"country",
		"NAME"=>"country",
		"LABEL"=>"Coun<u>t</u>ry",
		"ACCESSKEY"=>"t",
		"CustomClass"=>"form_mdb2_linked_select_class",
		"Connection"=>&$database,
		"OptionsQuery"=>"SELECT code, name FROM countries WHERE continent=?",
		"DefaultOption"=>"",
		"DefaultOptionValue"=>"Select country",
		"Dynamic"=>1,
		"VALUE"=>"",
		"LinkedInput"=>"continent",
		"SIZE"=>3,
		"AutoWidthLimit"=>0,
		"AutoHeightLimit"=>0,
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid country."
	));
	$form->AddInput(array(
		"TYPE"=>"custom",
		"ID"=>"location",
		"NAME"=>"location",
		"LABEL"=>"<u>L</u>ocation",
		"ACCESSKEY"=>"L",
		"CustomClass"=>"form_mdb2_linked_select_class",
		"Connection"=>&$database,
		"OptionsQuery"=>"SELECT code, name FROM locations WHERE country=?",
		"DefaultOption"=>"",
		"DefaultOptionValue"=>"Select location",
		"Dynamic"=>1,
		"VALUE"=>"",
		"LinkedInput"=>"country",
		"SIZE"=>3,
		"AutoWidthLimit"=>0,
		"AutoHeightLimit"=>0,
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid location."
	));
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>">",
		"NAME"=>"update",
		"SubForm"=>"update"
	));
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Go",
		"NAME"=>"doit"
	));
	$form->Connect("location", "doit", "ONCHANGE", "Click", array());

	/*
	 * This code is necessary to handle the requests for serving the
	 * dynamically generated lists of options for linked select inputs.
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
	{
		if(strlen($error_message))
		{
			Reset($verify);
			$focus=Key($verify);
		}
		else
			$focus='continent';
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
	}

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class using the linked select plug-in input</title>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class using the linked select plug-in input</h1></center>
<hr />
<?php
  if($doit)
	{
		$form->GetInputProperty("continent", "SelectedOption", $continent);
		$form->GetInputProperty("country", "SelectedOption", $country);
		$form->GetInputProperty("location", "SelectedOption", $location);
?>
<center><h2>The chosen location is <?php echo HtmlEntities($location), " (",HtmlEntities($country),", ",HtmlEntities($continent),")"; ?></h2></center>
<?php
	}
	else
	{
		$form->StartLayoutCapture();
		$title="Linked select plug-in test";
		$body_template="form_linked_select_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
