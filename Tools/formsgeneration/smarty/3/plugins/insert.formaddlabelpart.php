<?php

/*
 * insert.formaddlabelpart.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/smarty/3/plugins/insert.formaddlabelpart.php,v 1.1 2011/02/07 07:09:35 mlemos Exp $
 *
 */

function smarty_insert_formaddlabelpart($params, &$smarty)
{
	$form = $smarty->getTemplateVars('form');
	if(!IsSet($form)
	|| !is_a($form, 'form_class'))
		throw(new Exception('the "form" template variable was not assigned to the form object'));
	$form->AddDataPart($params['data']);
	$form->AddLabelPart(array('FOR'=>$params['for']));
	return '';
}

?>