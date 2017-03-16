<?php
/*
 * test_secure_submit.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_secure_submit.php,v 1.2 2007/05/09 01:43:29 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_secure_submit.php');

	$key="my secret key";
	$form=new form_class;
	$form->ID='secure_form';
	$form->METHOD='POST';
	$form->ACTION='?';
	$form->debug='trigger_error';
	$error=$form->AddInput(array(
		'TYPE'=>'custom',
		'VALUE'=>'Secure submit',
		'ID'=>'secure_submit',
		'NAME'=>'secure_submit',
		'CustomClass'=>'form_secure_submit_class',
		'Key'=>$key,
/*
		'ExpiryTime'=>300,
		"SRC"=>"http://www.phpclasses.org/graphics/add.gif",
*/
	));
	if(strlen($error))
		die("Error: ".$error);
	$submitted=$form->WasSubmitted('secure_submit');
	$form->LoadInputValues($submitted);
	$verify=array();
	if($submitted)
	{
		if(strlen($error_message=$form->Validate($verify))==0)
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
	{
		$focus='secure_submit';
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
	}

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class secure submit button</title>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<h1><center>Test for Manuel Lemos' PHP form class secure submit button</center></h1>
<hr />
<?php
	if($doit)
	{
?>
<center><h2>The form was submitted securely!</h2></center>
<?php
	}
  else
  {
		$form->StartLayoutCapture();
		if(strlen($error=$form->GetInputProperty('secure_submit', 'Expired', $expired))==0
		&& $expired)
		{
?><center><h2>The form submission expired. Please submit the form again.</h2></center><?php
		}
?>
<center><?php
		$form->AddInputPart('secure_submit');
?></center>
<?php
		$form->EndLayoutCapture();

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
