<?php

/*
 * prefilter.form.php
 *
 * @(#) $Header: /opt2/ena/metal/forms/smarty/2/plugins/prefilter.form.php,v 1.5 2006/11/19 18:19:07 mlemos Exp $
 *
 */

function smarty_prefilter_form($tpl_source, &$smarty)
{
	$ql=preg_quote($l=$smarty->left_delimiter, '/');
	$qr=preg_quote($r=$smarty->right_delimiter, '/');
	$qd=preg_quote('/', '/');
	$search=array(
		'/'.$ql.'input\s+name=("[^"]+"|\'[^\']+\'|\S+)'.$qr.'/i',
		'/'.$ql.'hiddeninput\s+name=("[^"]+"|\'[^\']+\'|\S+)'.$qr.'/i',
		'/'.$ql.'label\s+for=("[^"]+"|\'[^\']+\'|\S+)'.$qr.'/i',
		'/('.$ql.'(include|include_php|if|else|elseif|'.$qd.'if|foreach|foreachelse|'.$qd.'foreach|section'.$qd.'section).*'.$qr.')/i'
	);
	$replace=array(
		$l.'/capture'.$r.$l.'insert name="formaddinputpart" input=\\1 data=$smarty.capture.formdata'.$r.$l.'capture name="formdata"'.$r,
		$l.'/capture'.$r.$l.'insert name="formaddinputhiddenpart" input=\\1 data=$smarty.capture.formdata'.$r.$l.'capture name="formdata"'.$r,
		$l.'/capture'.$r.$l.'insert name="formaddlabelpart" for=\\1 data=$smarty.capture.formdata'.$r.$l.'capture name="formdata"'.$r,
		$l.'/capture'.$r.$l.'insert name="formadddatapart" data=$smarty.capture.formdata'.$r.'\\1'.$l.'capture name="formdata"'.$r
	);
	return($l.'capture name="formdata"'.$r.preg_replace($search,$replace,$tpl_source).$l.'/capture'.$r.$l.'insert name="formadddatapart" data=$smarty.capture.formdata'.$r);
}

?>