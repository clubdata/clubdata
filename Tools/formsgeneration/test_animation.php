<?php
/*
 *
 * @(#) $Id: test_animation.php,v 1.4 2008/09/07 06:24:27 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_animation.php');

	$form=new form_class;
	$form->NAME='animation_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->debug='trigger_error';
	$form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'animation',
		'CustomClass'=>'form_animation_class',

		/*
		 *  Set the path of animation.js if it is not in the current directory
		 */
		'JavascriptPath'=>''
	));
	$form->AddInput(array(
		'TYPE'=>'button',
		'ID'=>'show',
		'VALUE'=>'fade in'
	));
	$form->AddInput(array(
		'TYPE'=>'button',
		'ID'=>'hide',
		'VALUE'=>'fade out'
	));

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the animation plug-in</title>
<?php
	echo $form->PageHead();
?>
</head>
<body bgcolor="#cccccc">
<center><h1>Test for Manuel Lemos' PHP form class
using the animation plug-in</h1></center>
<hr />
<?php

	/*
	 *  Set the debug level to 1 or more show alert messages
	 *  when animation setup errors are detected
	 */
	$debug = 1;

	/*
	 *  Define an animation sequence to be started
	 *  when the hide button is clicked
	 */
	$context=array(
		'Name'=>'Hide form',
		'Debug'=>$debug,
		'Effects'=>array(

			/*
			 *  First, cancel the Show form animation if it is running
			 *  to avoid unwanted flicker effects
			 */
			array(
				'Type'=>'CancelAnimation',
				'Animation'=>'Show form'
			),

			/*
			 *  Update the feedback message box
			 */
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'Hiding...',
			),

			/*
			 *  Fade out the form during 0.5 seconds
			 */
			array(
				'Type'=>'FadeOut',
				'Element'=>'wholeform',
				'Duration'=>0.5
			),

			/*
			 *  Update the feedback message box
			 */
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'Waiting 3 seconds...',
			),

			/*
			 *  Wait 1 second
			 */
			array(
				'Type'=>'Wait',
				'Duration'=>1.0,
			),

			/*
			 *  Update the feedback message box
			 */
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'Waiting 2 seconds...',
			),

			/*
			 *  Wait 1 second
			 */
			array(
				'Type'=>'Wait',
				'Duration'=>1.0,
			),

			/*
			 *  Update the feedback message box
			 */
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'Waiting 1 second...',
			),

			/*
			 *  Wait 1 second
			 */
			array(
				'Type'=>'Wait',
				'Duration'=>1.0,
			),

			/*
			 *  Update the feedback message box
			 */
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'The form is hidden!',
			),
		)
	);
	/*
	 *  Connect the hide button with the animation input to start
	 *  the animation defined aboved when the ONCLICK event is triggered
	 */
	$form->Connect('hide', 'animation', 'ONCLICK', 'AddAnimation', $context);

	/*
	 *  Define an animation sequence to be started
	 *  when the show button is clicked
	 */
	$context=array(
		'Name'=>'Show form',
		'Debug'=>$debug,
		'Effects'=>array(
			array(
				'Type'=>'CancelAnimation',
				'Animation'=>'Hide form'
			),
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'Showing...',
			),
			array(
				'Type'=>'FadeIn',
				'Element'=>'wholeform',
				'Duration'=>0.5
			),
			array(
				'Type'=>'ReplaceContent',
				'Element'=>'feedback',
				'Content'=>'The form is visible!',
			),
		)
	);
	$form->Connect('show', 'animation', 'ONCLICK', 'AddAnimation', $context);
	$form->AddInputPart('animation');
	$form->StartLayoutCapture();
	require('templates/form_animation.html.php');
 	$form->EndLayoutCapture();
	$form->DisplayOutput();
?>
<hr />
</body>
</html>
