<?php
/*
 * @(#) $Id: form_paged_layout_body.html.php,v 1.2 2008/04/29 09:11:30 mlemos Exp $
 *
 * Add the automatic layout custom input to render all inputs.
 */

	$form->AddInputPart("layout");

	if(!$doit)
	{

/*
 * If the form was submitted with valid values, there is no need to display
 * the submit button again.
 */

?>
<hr />
<center><?php
		$form->AddInputPart("image_subscribe");
?> <?php
		$form->AddInputPart("button_subscribe");
?></center><?php
	}
?>
