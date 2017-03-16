<?php
/*
 * test_linked_select.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_linked_select.php,v 1.7 2006/12/20 06:21:16 mlemos Exp $
 *
 */

	require("forms.php");
	require("form_linked_select.php");

	$continents=array(
		""=>"Select continent",
		"na"=>"North America",
		"eu"=>"Europe",
		"sa"=>"South America",
		"as"=>"Asia",
		"oc"=>"Oceania"
	);
	$countries=array(
		""=>array(
			""=>"Select country",
		),
		"na"=>array(
			""=>"Select country",
			"us"=>"United States",
			"ca"=>"Canada"
		),
		"eu"=>array(
			""=>"Select country",
			"pt"=>"Portugal",
			"de"=>"Germany"
		),
		"sa"=>array(
			""=>"Select country",
			"br"=>"Brazil",
			"ar"=>"Argentina"
		),
		"as"=>array(
			""=>"Select country",
			"jp"=>"Japan",
			"kr"=>"Korea"
		),
		"oc"=>array(
			""=>"Select country",
			"au"=>"Australia",
			"nz"=>"New Zeland"
		)
	);
	$locations=array(
		""=>array(
			""=>"Select location"
		),
		"us"=>array(
			""=>"Select location",
			"ny"=>"New York",
			"la"=>"Los Angeles",
		),
		"ca"=>array(
			""=>"Select location",
			"to"=>"Toronto",
			"mo"=>"Montréal",
		),
		"pt"=>array(
			""=>"Select location",
			"li"=>"Lisbon",
			"av"=>"Aveiro",
		),
		"de"=>array(
			""=>"Select location",
			"fr"=>"Frankfurt",
			"be"=>"Berlin",
		),
		"br"=>array(
			""=>"Select location",
			"sa"=>"São Paulo",
			"ri"=>"Rio de Janeiro",
		),
		"ar"=>array(
			""=>"Select location",
			"bu"=>"Buenos Aires",
			"ma"=>"Mar del Plata",
		),
		"jp"=>array(
			""=>"Select location",
			"to"=>"Tokio",
			"os"=>"Osaka",
		),
		"kr"=>array(
			""=>"Select location",
			"se"=>"Seoul",
			"yo"=>"Yosu",
		),
		"au"=>array(
			""=>"Select location",
			"sy"=>"Sydney",
			"me"=>"Melbourne",
		),
		"nz"=>array(
			""=>"Select location",
			"we"=>"Wellington",
			"au"=>"Auckland",
		)
	);
	$form=new form_class;
	$form->NAME="location_form";
	$form->METHOD="POST";
	$form->ACTION="";
	$form->debug="error_log";
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
		"CustomClass"=>"form_linked_select_class",
		"VALUE"=>"",
		"Groups"=>$countries,
		"LinkedInput"=>"continent",
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
		"CustomClass"=>"form_linked_select_class",
		"VALUE"=>"",
		"Groups"=>$locations,
		"LinkedInput"=>"country",
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

	$onload = HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the linked select plug-in input</title>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
using the linked select plug-in input</h1></center>
<hr />
<?php
  if($doit)
	{
		$continent=$form->GetInputValue("continent");
		$country=$form->GetInputValue("country");
		$location=$form->GetInputValue("location");
?>
<center><h2>The chosen location is <?php
		echo HtmlEntities($locations[$country][$location]),
			" (",HtmlEntities($countries[$continent][$country]),
			", ",HtmlEntities($continents[$continent]),")"; ?></h2></center>
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
