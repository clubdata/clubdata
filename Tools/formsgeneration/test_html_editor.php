<?php
/*
 *
 * @(#) $Id: test_html_editor.php,v 1.7 2014/09/28 00:39:10 mlemos Exp $
 *
 */

	require('forms.php');
	require('form_html_editor.php');

	$form=new form_class;
	$form->NAME='html_editor_form';
	$form->METHOD='POST';
	$form->ACTION='';
	$form->ONSUBMIT='return false';
	$form->debug='trigger_error';
	$form->AddInput(array(
		'TYPE'=>'custom',
		'ID'=>'editor',
		'CustomClass'=>'form_html_editor_class',
		'NAME'=>'editor',
		'ROWS'=>20,
		'COLS'=>80,
		'VALUE'=>'<h1>Hello world!</h1>{menu sad}<p>{smiley}</p>',
		'Debug'=>1,
		'STYLE'=>'width: 800px; height: 600px; background-color: #ffffff; border-style: solid; border-width: 1px; margin: 0px; border-color:  #707070 #e0e0e0 #e0e0e0 #707070',
		/*
		 *  Set the path of html_editor.js if it is not in the current directory
		 */
		'JavascriptPath'=>'',
		'TemplateVariables'=>array(
			'menu'=>array(
				'Preview'=>'<a href="">File</a> <a href="">Edit</a> <a href="">Tools</a> <a href="">Help</a>',
				'Inline'=>0,
				'Title'=>'Menu',
				'Alternatives'=>array(
					'vertical'=>array(
						'Preview'=>'<a href="">File</a><br /><a href="">Edit</a><br /><a href="">Tools</a><br /><a href="">Help</a>',
						'Title'=>'Vertical menu'
					),
				)
			),
			'smiley'=>array(
				'Preview'=>';-)',
				'Inline'=>1,
				'Title'=>'Smiley',
				'Alternatives'=>array(
					'sad'=>array(
						'Preview'=>':-(',
						'Title'=>'Sad'
					),
					'grin'=>array(
						'Preview'=>':D',
						'Title'=>'Grin'
					),
					'shocked'=>array(
						'Preview'=>':O',
						'Title'=>'Shocked'
					),
				)
			)
		),
	));
	$form->AddInput(array(
		'TYPE'=>'submit',
		'ID'=>'send',
		'NAME'=>'send',
		'VALUE'=>'Submit',
	));
	$head = $form->PageHead();
	$onload = $form->PageLoad();
	$onunload = $form->PageUnload();
?><!DOCTYPE HTML>
<html>
<head>
<title>Test for Manuel Lemos' PHP form class
using the HTML editor plug-in</title>
<?php echo $head; ?>
<style type="text/css">
.editor { background-color: blue; font-family: "courier" }
</style>
</head>
<body bgcolor="#cccccc" onload="<?php echo HtmlSpecialChars($onload); ?>" onunload="<?php echo HtmlSpecialChars($onunload); ?>">
<h1>Test for Manuel Lemos' PHP form class
using the HTML editor plug-in</h1>
<?php
	$form->StartLayoutCapture();
	$form->AddInputPart('editor');
	$form->AddInputPart('send');
 	$form->EndLayoutCapture();
	$form->DisplayOutput();
?>
</body>
</html>
