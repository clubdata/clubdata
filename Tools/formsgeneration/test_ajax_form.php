<?php
/*
 * test_ajax_form.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/test_ajax_form.php,v 1.7 2007/02/19 23:44:20 mlemos Exp $
 *
 */

/*
 * Include form class code.
 */
	require("forms.php");
	require("form_ajax_submit.php");

/*
 * Create a form object.
 */
	$form=new form_class;
	$form->NAME="subscription_form";
	$form->METHOD="POST";
	$form->ACTION="";
	$form->debug="error_log";
	$form->AddInput(array(
		"TYPE"=>"text",
		"NAME"=>"description",
		"ID"=>"description",
		"LABEL"=>"<u>D</u>escription",
		"ACCESSKEY"=>"D",
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid description."
	));
	$form->AddInput(array(
		"TYPE"=>"file",
		"NAME"=>"file",
		"ID"=>"file",
		"LABEL"=>"<u>F</u>ile",
		"ACCESSKEY"=>"F",
		"ValidateAsNotEmpty"=>1,
		"ValidationErrorMessage"=>"It was not specified a valid file."
	));
	$form->AddInput(array(
		"TYPE"=>"submit",
		"NAME"=>"doit",
		"ID"=>"doit",
		"VALUE"=>"Submit"
	));
	$form->AddInput(array(
		"TYPE"=>"custom",
		"NAME"=>"sender",
		"ID"=>"sender",
		"CustomClass"=>"form_ajax_submit_class",
		"FeedbackElement"=>"feedback",
		"SubmitFeedback"=>
			'<img src="indicator.gif" width="16" height="16" /> Submitting form ...',
		"TimeoutFeedback"=>'The communication with the server has timed out.',
		"Timeout"=>60,
		"ONTIMEOUT"=>"",
		"DebugConsole"=>"debug_console"
	));

	/*
	 *  Connect the doit submit button to the sender AJAX submit input
	 */
	$form->Connect("doit", "sender", "ONCLICK", "Submit", array());

	/*
	 *  Handle client side events on the server side.
	 *  Do not output anything before these lines.
	 */
	$form->HandleEvent($processed);
	if($processed)
		exit;

	/*
	 *  Did the AJAX submit post any notification message to the application?
	 */
	if($form->GetNextMessage($message))
	{

		/*
		 *  Process and reply to notification messages
		 */
		do
		{
			switch($message["Event"])
			{
				case "submit":

					$message["Actions"]=array();
					
					/*
					 * Client side form submission request
					 */
					$form->LoadInputValues();
					$error_message=$form->Validate($verify);

/*
					$form->GetFileValues("file", $values);
					$form->SetInputProperty("sender", "Feedback", serialize($values));
					sleep(3);
*/
					/*
					 *  Are there any form validation errors?
					 */
					if(strlen($error_message))
					{

						/*
						 * Tell the form submitter input to send to the browser
						 * an order to display validation error feedback message.
						 */
						$title = "Validation error";
						$output = HtmlSpecialChars($error_message);
						$active = 0;
						$icon = '';
						ob_start();
						require('templates/message.html.php');
						$content = ob_get_contents();
						ob_end_clean();
						$form->SetInputProperty("sender", "Feedback", $content);
					}
					else
					{

						/*
						 *  The form was processed without errors.
						 *  Lets execute the form processing actions
						 *  and show some progress feedback.
						 */
						$active = 1;
						$title = "Status";
						$output = '<center>Operation in progress: '.
							'<tt><span id="progress">0</span>%</tt></center>';
						$icon = '&nbsp;';
						ob_start();
						require('templates/message.html.php');
						$content = ob_get_contents();
						ob_end_clean();

						/*
						 *  Send an action order to replace the form contents
						 *  by a progress feedback window.
						 */
						$form->SetInputProperty("sender", "FeedbackElement",
							"wholeform");
						$form->SetInputProperty("sender", "Feedback", $content);
						$form->SetInputProperty("sender", "FeedbackElement",
							"progress");
						for($progress = 1 ; $progress<=100; $progress++)
						{

							/*
							 * Pretend to execute an operation that takes time to complete.
							 * Here you would actually execute a a step of your
							 * lengthy processing action.
							 */
							usleep(50000);

							/*
							 *  Update the progress display.
							 */
							$form->SetInputProperty("sender", "Feedback",
								sprintf("%0d",$progress));
						}
						/*
						 * A little delay before the final message.
						 */
						sleep(1);
						$output = '<center><b>Operation completed!</b><br />'.
							'Going to redirect to the initial page in a few moments'.
							'...</center>';
						ob_start();
						require('templates/message.html.php');
						$content = ob_get_contents();
						ob_end_clean();

						/*
						 * Display the final message and wait a few more seconds
						 */
						$form->SetInputProperty("sender", "FeedbackElement", "wholeform");
						$form->SetInputProperty("sender", "Feedback", $content);
						sleep(3);
						
						/*
						 * Redirect to the form start script page
						 */
						$redirect="/test_ajax_form.php";

						/*
						 * This is just for testing purposes
						 */
						if(defined('AJAX_REDIRECT_URI'))
							$redirect=AJAX_REDIRECT_URI;

						$message["Actions"][]=array(
							"Action"=>"Redirect",
							"URL"=>"http://".GetEnv("HTTP_HOST").
								dirname(GetEnv("REQUEST_URI")).$redirect
						);
					}
					break;
			}

			/*
			 * Reply to the form submit event to tell which actions the
			 * AJAX submit input should execute on the browser side.
			 */
			if(strlen($form->ReplyMessage($message, $processed)))
				exit;
		}
		/*
		 * Loop until there are no more event messages
		 * or the processing was finished
		 */
		while(!$processed
		&& $form->GetNextMessage($message));
		if($processed)
			exit;
	}

/*
 * Normal non-AJAX form processing
 */

/*
 * Load form input values eventually from the submitted form.
 */
	$form->LoadInputValues($form->WasSubmitted("doit"));

	$verify=array();

	if($form->WasSubmitted("doit"))
	{

		if(($error_message=$form->Validate($verify))=="")
		{

			/*
			 *  Process the form if it was submitted without validation errors.
			 */
			$doit=1;

		}
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
  if($doit)
  {
  	$form->ReadOnly=1;
  }

	$form->StartLayoutCapture();
	$title="Form class AJAX submit test";
	$body_template="form_ajax_body.html.php";
	require("templates/form_frame.html.php");
 	$form->EndLayoutCapture();
 	$form->AddInputPart('sender');

	if(!$doit)
	{
		if(strlen($error_message))
		{
			Reset($verify);
			$focus=Key($verify);
		}
		else
			$focus='description';
		$form->ConnectFormToInput($focus, 'ONLOAD', 'Focus', array());
	}

	$onload=HtmlSpecialChars($form->PageLoad());

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the AJAX form submit plug-in</title>
</head>
<body onload="<?php	echo $onload; ?>" bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
using the AJAX form submit plug-in</h1></center>
<hr />
<?php
	$form->DisplayOutput();
?>
<div id="debug_console"></div>
<hr />
</body>
</html>
