<?php

/*
 * insert.formadddatapart.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/smarty/2/plugins/insert.formadddatapart.php,v 1.1 2003/03/20 02:50:26 mlemos Exp $
 *
 */

function smarty_insert_formadddatapart($params, &$smarty)
{
	if(method_exists($smarty,'get_template_vars'))
	{
		$tpl_vars=&$smarty->get_template_vars();
		$form=&$tpl_vars['form'];
	}
	else
		$form=&$smarty->_tpl_vars['form'];
	$form->AddDataPart($params['data']);
	return '';
}

?>