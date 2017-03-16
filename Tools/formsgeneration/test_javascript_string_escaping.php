<?php

/*
 * test_javascript_string_escaping.php
 *
 * @(#) $Id: test_javascript_string_escaping.php,v 1.1 2008/02/04 05:58:20 mlemos Exp $
 *
 */

	require('forms.php');

	$form = new form_class;
	$strings = array(
		'',
		"\n",
		"\r",
		"\t",
		'<',
		'%',
		'\'',
		'\\',
		chr(160),
		"\n<",
		"<%",
		"<'",
		"<\\",
		"<\n",
		"<\r",
		"<\t",
		' <',
	);
	$t = count($strings);
	for($s = 0; $s < $t; ++$s)
		echo $form->EncodeJavascriptString($strings[$s]), "\n";
?>