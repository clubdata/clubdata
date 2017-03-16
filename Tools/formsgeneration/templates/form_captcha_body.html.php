<fieldset>
<legend><b><?php $form->AddLabelPart(array("FOR"=>"captcha")); ?></b></legend>
<center><?php $form->AddInputPart("captcha"); ?>
<?php
		if(IsSet($verify["captcha"]))
		{
?>
&nbsp;[Verify]
<?php
		}
?></center>
</fieldset>
<hr />
<center><?php $form->AddInputPart("doit"); ?></center>