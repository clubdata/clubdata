<?php
/*
 * Add the automatic layout custom input to render all inputs at once.
 */
	$form->AddInputPart("layout");

	if(!$doit)
	{

/*
 * If the form was submitted with valid values, there is no need to display
 * the submit button again.
 */

?><center><?php
		$form->AddInputPart("image_subscribe");
?> <?php
		$form->AddInputPart("button_subscribe");
?><br /><br />
<?php
		$form->AddInputPart("button_subscribe_with_content");
		$form->AddInputPart("doit");
?></center><?php
	}
?>
