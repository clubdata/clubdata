<?php
/*
 *
 * @(#) $Id: test_crud_input.php,v 1.2 2013/05/08 03:05:42 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_scaffolding.php');
	require('form_crud.php');

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
		die('Error: '.$view->error);

	/*
	 *  Include the blog post model class and initialize the object to store
	 *  and retrieve data of the post entries being edited
	 */
	require('blog_post_model.php');

	$model = new blog_post_model_class;
	if(!$model->Initialize())
		die('Error: '.$model->error);

	/*
	 *  Include the blog post data source class so it can act as adapter to
	 *  store and retrieve information from the model and view classes.
	 */
	require('blog_post_data_source.php');

	$form=new form_class;
	$form->NAME = 'scaffolding_form';
	$form->METHOD = 'POST';
	$form->ACTION = (defined('SCAFFOLDING_URI') ?	SCAFFOLDING_URI : '?');
	$form->InvalidCLASS = $view->GetInvalidInputsClass();
	$form->ShowAllErrors = 1;
	$form->ErrorMessagePrefix = '- ';
	$form->debug = 'trigger_error';

	/*
	 *  Add the crud custom input pointing to the scaffolding input
	 *  The DataSourceClass parameter defines a class that will store and
	 *  retrieve records of data to be manipulated 
	 */
	$error = $form->AddInput(array(
		'TYPE'=>'custom',
		'CustomClass'=>'form_crud_class',
		'ID'=>'crud',
		'DataSourceClass'=>'blog_post_data_source_class',
		'ScaffoldingInput'=>'posts',
		'Model'=>&$model,
		'View'=>&$view,
	));
	if(strlen($error))
		die('Error: '.$error);

	/*
	 *  Add the scaffolding custom input with all the necessary properties
	 */
	$error = $form->AddInput(array(
		'TYPE'=>'custom',
		'CustomClass'=>'form_scaffolding_class',
		'ID'=>'posts',

		/*
		 *  Make the crud input handle all the events to store and retrieve
		 *  the entry records.
		 */
		'TargetInput'=>'crud',

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
		 *  Customize the HTML that surrounds the forms for creating, updating
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
	if(strlen($error = $form->HandleEvent($processed)))
		die('Error: '.$error);

	/*
	 *  Exit your script if all AJAX events were processed.
	 */
	if($processed)
		exit;

	/*
	 *  Load input values so the scaffolding input can post event messages
	 *  for handling by the crud input.
	 */
	$submitted = strlen($form->WasSubmitted('')) != 0;
	if(strlen($error = $form->LoadInputValues($submitted)))
		die('Error: '.$error);

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
	 *  Finalize the view object after we are done with it.
	 */
	if(!$view->Finalize())
		die('Error: '.$view->error);

	/*
	 *  Get some values to generate the page output.
	 */
	$onload = HtmlSpecialChars($form->PageLoad());
	$onunload = HtmlSpecialChars($form->PageUnload());
	$head = $form->PageHead();
	$styles = $view->GetCSSStyles();

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
using the scaffolding and crud plug-in inputs</h1></center>
<hr />
<?php
	$form->AddInputPart('posts');
	$form->DisplayOutput();
?>
<hr />
</body>
</html>
