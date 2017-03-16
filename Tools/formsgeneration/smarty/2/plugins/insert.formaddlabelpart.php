<?php

/*
 * insert.formaddlabelpart.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/smarty/2/plugins/insert.formaddlabelpart.php,v 1.1 2003/03/20 02:54:43 mlemos Exp $
 *
 */

function smarty_insert_formaddlabelpart($params, &$smarty)
{
	if(method_exists($smarty,'get_template_vars'))
	{
		$tpl_vars=&$smarty->get_template_vars();
		$form=&$tpl_vars['form'];
	}
	else
		$form=&$smarty->_tpl_vars['form'];
	$form->AddDataPart($params['data']);
	$form->AddLabelPart(array('FOR'=>$params['for']));
	return '';
}

?>