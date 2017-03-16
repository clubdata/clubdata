<fieldset>
<legend><b><?php $form->AddLabelPart(array("FOR"=>"experience")); ?></b></legend>
<center><?php $form->AddInputPart("experience"); ?>
<?php echo (IsSet($verify["experience"]) ? "&nbsp;[Verify]" : "") ?></center>
</fieldset>
<hr />
<center><?php $form->AddInputPart("doit"); ?></center>