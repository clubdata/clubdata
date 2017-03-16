<?php
/*
 * test_encoded_pasword.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_encoded_password.php,v 1.6 2006/12/20 06:21:16 mlemos Exp $
 *
 */

	require("forms.php");

	$form=new form_class;
	$form->NAME="login_form";
	$form->METHOD="GET";
	$form->ACTION="";
	$form->debug="trigger_error";
	$form->AddInput(array(
		"TYPE"=>"text",
		"NAME"=>"access_name",
		"MAXLENGTH"=>20,
		"Capitalization"=>"uppercase",
		"ValidateRegularExpression"=>"^[a-zA-Z0-9\\-_]+$",
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid access name"
	));
	$form->AddInput(array(
		"TYPE"=>"hidden",
		"NAME"=>"user_login",
		"ID"=>"user_login",
		"VALUE"=>""
	));
	$form->AddInput(array(
		"TYPE"=>"password",
		"NAME"=>"password",
		"ONCHANGE"=>"if(value.toLowerCase) value=value.toLowerCase()",
		"Encoding"=>"MD5",
		"EncodingFunctionVerification"=>"loaded_MD5",
		"EncodedField"=>"user_login",
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid password"
	));
	$form->AddInput(array(
		"TYPE"=>"submit",
		"VALUE"=>"Login",
		"NAME"=>"doit"
	));
	$form->LoadInputValues($form->WasSubmitted("doit"));
	$verify=array();
	if($form->WasSubmitted("doit"))
	{
		$user_login=$form->GetInputValue("user_login");
		if(strcmp($user_login,""))
		{
			$password=$user_login;
			$user_login="";
			$form->SetInputValue("user_login",$user_login);
		}
		else
		{
			$password=$form->GetInputValue("password");
			if(strcmp($password,""))
			{
				$password=md5(strtolower($password));
			}
		}
		$form->SetInputValue("password",$password);
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
			$focus='access_name';
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
	}

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for password encoding
with Manuel Lemos' PHP form class</title>
</head>
<body onload="<?php echo $onload; ?>" bgcolor="#cccccc">
<h1><center>Test for password encoding
with Manuel Lemos' PHP form class</center></h1>
<hr />
<h2><center>User login</center></h2>
<?php
	if($doit)
	{
?>
<center><table>
<tr>
<th align="right">Access name:</th>
<td><tt><?php echo $form->GetInputValue("access_name"); ?></tt></td>
</tr>

<tr>
<th align="right">Encoded password:</th>
<td><tt><?php echo $form->GetInputValue("password"); ?></tt></td>
</tr>

</table></center>

<?php
  }
  else
  {
		$form->StartLayoutCapture();
?>
<script type="text/javascript">
<!--
	loaded_MD5=false
// -->
</script>
<script type="text/javascript" src="md5.js">
</script>
<?php
		$title="Form encoded password test";
		$body_template="form_password_body.html.php";
		include("templates/form_frame.html.php");
		$form->EndLayoutCapture();
		$form->AddInputPart("user_login");

		$form->DisplayOutput();
	}
?>
<hr />
</body>
</html>
