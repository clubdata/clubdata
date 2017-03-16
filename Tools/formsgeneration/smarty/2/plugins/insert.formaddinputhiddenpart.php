<?php

/*
 * insert.formaddinputhiddenpart.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/smarty/2/plugins/insert.formaddinputhiddenpart.php,v 1.1 2003/03/20 02:53:19 mlemos Exp $
 *
 */

function smarty_insert_formaddinputhiddenpart($params, &$smarty)
{
	if(method_exists($smarty,'get_template_vars'))
	{
		$tpl_vars=&$smarty->get_template_vars();
		$form=&$tpl_vars['form'];
	}
	else
		$form=&$smarty->_tpl_vars['form'];
	$form->AddDataPart($params['data']);
	$form->AddInputHiddenPart($params['input']);
	return '';
}

?>