<?php

/*
 * insert.formadddatapart.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/smarty/3/plugins/insert.formadddatapart.php,v 1.1 2011/02/07 07:09:35 mlemos Exp $
 *
 */

function smarty_insert_formadddatapart($params, &$smarty)
{
	$form = $smarty->getTemplateVars('form');
	if(!IsSet($form)
	|| !is_a($form, 'form_class'))
		throw(new Exception('the "form" template variable was not assigned to the form object'));
	$form->AddDataPart($params['data']);
	return '';
}

?>