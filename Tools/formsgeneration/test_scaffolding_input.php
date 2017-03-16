<?php
/*
 *
 * @(#) $Id: test_scaffolding_input.php,v 1.14 2009/04/07 09:30:17 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_scaffolding.php');

	/*
	 *  Include the layout vertical plug-in class to automatically layout
	 *  the inputs
	 */
	require('form_layout_vertical.php');

	/*
	 *  Include the AJAX submit plug-in class to automatically interact with
	 *  the server without reloading the page
	 */
	require('form_ajax_submit.php');

	/*
	 *  Include the blog post view class and initialize the object to define
	 *  details of presentation of the forms and listings of the posts being
	 *  edited
	 */
	require('blog_post_view.php');

	$view = new blog_post_view_class;
	if(!$view->Initialize())
		die('Error: '.$model->error);

	$form=new form_class;
	$form->NAME = 'scaffolding_form';
	$form->METHOD = 'POST';
	$form->ACTION = (defined('SCAFFOLDING_URI') ?	SCAFFOLDING_URI : '?');
	$form->InvalidCLASS = $view->GetInvalidInputsClass();
	$form->ShowAllErrors = 1;
	$form->ErrorMessagePrefix = '- ';
	$form->debug = 'trigger_error';

	/*
	 *  Include the blog post model class and initialize the object to store
	 *  and retrieve data of the post entries being edited
	 */
	require('blog_post_model.php');

	$model = new blog_post_model_class;
	if(!$model->Initialize())
		die('Error: '.$model->error);

	/*
	 *  Add the scaffolding custom input with all the necessary properties
	 */
	$error = $form->AddInput(array(
		'TYPE'=>'custom',
		'CustomClass'=>'form_scaffolding_class',
		'ID'=>'posts',

		/*
		 *  Customize all the necessary messages for which the default values
		 *  may not be suitable. These messages may include HTML tags.
		 */
		'ListingMessage'=>'All blog posts',
		'NoEntriesMessage'=>'No blog articles were submitted.',
		'CreateMessage'=>'Submit a new blog post',
		'CreateCanceledMessage'=>'Submitting the blog post was canceled.',
		'CreatedMessage'=>'The new blog post was submitted successfully.',
		'UpdateMessage'=>'Update this blog post',
		'UpdateCanceledMessage'=>'Updating the blog post was canceled.',
		'UpdatedMessage'=>'The blog post was updated successfully.',
		'DeleteMessage'=>'Are you sure you want to delete this blog post?',
		'DeleteCanceledMessage'=>'Deleting the blog post was canceled.',
		'DeletedMessage'=>'The blog post was deleted successfully.',

		/*
		 *  If we want to display entry previews, a few more properties are
		 *  necessary.
		 */
		'Preview'=>1,
		'PreviewLabel'=>'Preview',
		'CreatePreviewMessage'=>'New blog post preview',
		'UpdatePreviewMessage'=>'Blog post update preview',

		/*
		 *  If we want to allow saving an entry and continue editing, a few
		 *  more properties are necessary.
		 */
		'Save'=>1,
		'SaveLabel'=>'Save',

		/*
		 *  If we want to allow viewing an entry without editing it, a few
		 *  more properties are necessary.
		 */
		'View'=>1,
		'ViewLabel'=>'View',
		'ViewingMessage'=>'Viewing blog post',

		/*
		 *  Here we define all the input fields necessary to edit the
		 *  properties of each entry being created or updated.
		 */
		'EntryFields'=>array(
			array(
				'TYPE'=>'text',
				'NAME'=>'title',
				'LABEL'=>'<u>T</u>itle',
				'ValidateAsNotEmpty'=>1,
				'ValidationErrorMessage'=>'It was not entered a valid post title.',
			),
			array(
				'TYPE'=>'textarea',
				'NAME'=>'body',
				'LABEL'=>'<u>B</u>ody',
				'COLS'=>60,
				'ROWS'=>10,
				'ValidateAsNotEmpty'=>1,
				'ValidationErrorMessage'=>'It was not entered a valid post body.',
			)
		),

		/*
		 *  Several properties may be set to customize the presentation of the
		 *  listing of existing entries.
		 */
		'ListingClass'=>'listing box',
		'HighlightRowListingClass'=>'highlightrow',
		'OddRowListingClass'=>'oddrow',
		'EvenRowListingClass'=>'evenrow',

		/*
		 *  Customize the presentation of validation error messages and marks
		 *  that appear next to invalid fields.
		 */
		'ErrorMessageFormat'=>$view->GetErrorMessageFormat(),
		'InvalidMark'=>$view->GetInvalidMark(),

		/*
		 *  Customize the HTML that envolve the forms for creating, updating
		 *  and deleting entries.
		 */
		'FormHeader'=>$view->GetFormHeader(),
		'FormFooter'=>$view->GetFormFooter(),
	));
	if(strlen($error))
		die('Error: '.$error);

	/*
	 *  Handle events AJAX requests handling events.
	 *  Do not output anything nor send any headers before this line.
	 */
	$form->HandleEvent($processed);

	/*
	 *  Exit your script if all AJAX events were processed.
	 */
	if($processed)
		exit;

	/*
	 *  Load input values so the scaffolding input can post event messages
	 *  for handling by your application.
	 */
	$submitted = strlen($form->WasSubmitted('')) != 0;
	$form->LoadInputValues($submitted);

	/*
	 *  Were any messages posted to handle scaffolding events?
	 */
	if($form->GetNextMessage($message))
	{

		/*
		 *  If so process and reply to all messages until there are no more
		 *  messages to process.
		 */
		do
		{

			/*
			 *  First lets check which input posted a message.
			 */
			switch($message['From'])
			{
				case 'posts':

					/*
					 *  If it was the posts scaffolding input, now lets handle each
					 *  type of event.
					 */
					switch($message["Event"])
					{
						case "listing":
							/*
							 *  When the listing event is sent, applications should
							 *  retrieve the data of entries to display and set a few
							 *  properties to tell the scaffolding plug-in how to render
							 *  the entries.
							 */
							$page = $message['Page'];
							if(!$model->GetEntries($page, $posts, $total_posts))
								die('Error: '.$model->error);
							if(!$view->GetPostListingFormat($columns, $id_column, $page_entries))
								die('Error: '.$view->error);
							$form->SetInputProperty('posts', 'IDColumn', $id_column);
							$form->SetInputProperty('posts', 'Columns', $columns);
							$form->SetInputProperty('posts', 'TotalEntries', $total_posts);
							$form->SetInputProperty('posts', 'PageEntries', $page_entries);
							$form->SetInputProperty('posts', 'Page', $page);
							$form->SetInputProperty('posts', 'Rows', $posts);
							break;

						case "created":
							/*
							 *  When the created event is sent, applications should
							 *  validate the form and create a new entry if it is all
							 *  OK.
							 */
							if(strlen($error_message = $form->Validate($verify, 'posts-submit')) == 0)
							{
								$form->LoadInputValues(1);
								$entry = array(
									'title' => $form->GetInputValue('title'),
									'body' => $form->GetInputValue('body')
								);
								if(!$model->CreateEntry($entry))
								{
									/*
									 *  If there was a problem creating an entry, cancel the
									 *  entry creation and display an helpful error message.
									 */
									$message['Cancel'] = 1;
									$form->SetInputProperty('posts', 'CreateCanceledMessage', 'Error: '.$model->error);
								}
								else
								{
									/*
									 *  If the entry was created successfully, set the Entry
									 *  entry of the message array to pass back the
									 *  identifier of the newly created entry.
									 */
									$message['Entry'] = $entry['id'];
								}
							}
							break;

						case "updating":
						case "updated":
							/*
							 *  When it is sent an event of an entry being updated, make
							 *  sure the specified entry identifier really exists and
							 *  can be updated.
							 */
							$id = $message['Entry'];
							if(!$model->ReadEntry($id, $entry))
							{
									/*
									 *  If there was a problem checking the an entry, cancel
									 *  the entry update and display an helpful error
									 *  message.
									 */
								$message['Cancel'] = 1;
								$form->SetInputProperty('posts', 'UpdateCanceledMessage', 'Error: '.$model->error);
							}
							elseif(IsSet($entry))
							{
								$form->SetInputValue('title', $entry['title']);
								$form->SetInputValue('body', $entry['body']);

								/*
								 *  When the updated event is sent, applications should
								 *  validate the form and update the entry if it is all
								 *  OK.
								 */
								if(!strcmp($message['Event'], 'updated'))
								{
									$form->LoadInputValues(1);
									$entry['title'] = $form->GetInputValue('title');
									$entry['body'] = $form->GetInputValue('body');
									if(strlen($form->Validate($verify, 'posts-submit')) == 0)
									{
										if(!$model->UpdateEntry($id, $entry))
										{
											/*
											 *  If there was a problem updating the entry, cancel the
											 *  entry updating and display an helpful error message.
											 */
											$message['Cancel'] = 1;
											$form->SetInputProperty('posts', 'UpdateCanceledMessage', 'Error: '.$model->error);
										}
									}
								}
							}
							else
							{
								/*
								 *  If the entry does not exist or the user does not have
								 *  permissions to access it, cancel the entry update but
								 *  do not display any message as this may be an attempt
								 *  to access unauthorized information.
								 */
								$message['Cancel'] = 1;
								$form->SetInputProperty('posts', 'UpdateCanceledMessage', '');
							}
							break;

						case "deleting":
						case "deleted":
							/*
							 *  When it is sent an event of an entry being deleted, make
							 *  sure the specified entry identifier really exists and
							 *  can be updated.
							 */
							$id = $message['Entry'];
							if(!$model->ReadEntry($id, $entry))
							{
								/*
								 *  If there was a problem checking the an entry, cancel
								 *  the entry update and display an helpful error message.
								 */
								$message['Cancel'] = 1;
								$form->SetInputProperty('posts', 'DeleteCanceledMessage', 'Error: '.$model->error);
							}
							elseif(IsSet($entry))
							{
								/*
								 *  When the deleted event is sent, applications should
								 *  validate the form and delete the entry if it is all
								 *  OK.
								 */
								if(!strcmp($message['Event'], 'deleted'))
								{
									if(strlen($form->Validate($verify, 'posts-delete')) == 0)
									{
										if(!$model->DeleteEntry($id, $entry))
										{
											/*
											 *  If there was a problem deleting the entry, cancel the
											 *  entry deletion and display an helpful error message.
											 */
											$message['Cancel'] = 1;
											$form->SetInputProperty('posts', 'DeleteCanceledMessage', 'Error: '.$model->error);
										}
									}
								}
							}
							else
							{
								/*
								 *  If the entry does not exist or the user does not have
								 *  permissions to access it, cancel the entry deletion
								 *  but do not display any message as this may be an
								 *  attempt to access unauthorized information.
								 */
								$message['Cancel'] = 1;
								$form->SetInputProperty('posts', 'DeleteCanceledMessage', '');
							}
							break;

						case 'update_previewing':
						case 'viewing':
							/*
							 *  When it is sent an event to show an existing entry or a
							 *  preview of an entry being updated, make sure the
							 *  specified entry identifier really exists and can be
							 *  updated.
							 */
							$form->GetInputProperty('posts', 'Entry', $id);
							$id = intval($id);
							if(!$model->ReadEntry($id, $entry))
							{
								/*
								 *  If there was a problem checking the an entry, cancel
								 *  the entry update.
								 */
								$message['Cancel'] = 1;
								break;
							}
							if(!IsSet($entry))
							{
								/*
								 *  If the entry does not exist or the user does not have
								 *  permissions to access it, cancel the entry update
								 *  but do not display any message as this may be an
								 *  attempt to access unauthorized information.
								 */
								$message['Cancel'] = 1;
								break;
							}
						case 'create_previewing':
							/*
							 *  When it is sent an event to show an existing entry or a
							 *  preview of an entry being created or updated,
							 *  applications should validate the form and generate the
							 *  output HTML if it is all OK.
							 */
							if(strcmp($message['Event'], 'viewing'))
							{
								$form->LoadInputValues(1);
								$error_message = $form->Validate($verify);
								if(strlen($error_message))
									break;
								$entry = array(
									'title' => $form->GetInputValue('title'),
									'body' => $form->GetInputValue('body')
								);
							}
							if(!$view->GetPostOutput($entry, $output))
								die('Error: '.$view->error);
							/*
							 *  If the preview was generated successfully, set the
							 *  EntryOutput property to the necessary HTML to display
							 *  the entry preview.
							 */
							$form->SetInputProperty('posts', 'EntryOutput', $output);
							break;
					}
					break;
			}

			/*
			 *  After processing each type of event, always reply to the
			 *  message.
			 */
			if(strlen($error = $form->ReplyMessage($message, $processed)))
				die('Error: '.$error);
		}
		/*
		 *  Check whether there anymore posted messages until all have been
		 *  processed.
		 */
		while(!$processed
		&& $form->GetNextMessage($message));

		/*
		 *  Exit your script if all AJAX events were processed.
		 */
		if($processed)
			exit;
	}

	/*
	 *  Finalize the model object after we are done with it.
	 */

	if(!$model->Finalize())
		die('Error: '.$model->error);

	/*
	 *  Get some values to generate the page output.
	 */
	$onload = HtmlSpecialChars($form->PageLoad());
	$onunload = HtmlSpecialChars($form->PageUnload());
	$head = $form->PageHead();
	$styles = $view->GetCSSStyles();

	/*
	 *  Finalize the view object after we are done with it.
	 */
	if(!$view->Finalize())
		die('Error: '.$view->error);

	/*
	 *  Generate the page output.
	 */

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the scaffolding plug-in input</title>
<style type="text/css"><!--
<?php
	echo $styles;
?>
// --></style>
<?php	echo $head; ?>
</head>
<body onload="<?php echo $onload; ?>"
      onunload="<?php echo $onunload; ?>"
      bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
using the scaffolding plug-in input</h1></center>
<hr />
<?php
	$form->AddInputPart('posts');
	$form->DisplayOutput();
?>
<hr />
</body>
</html>
