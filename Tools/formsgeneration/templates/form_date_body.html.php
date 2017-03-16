<fieldset>
<legend><b><?php $form->AddLabelPart(array("FOR"=>"date")); ?></b> (From <tt><?php echo $start_date; ?></tt> to <tt><?php echo $end_date; ?></tt>)</legend>
<center><?php $form->AddInputPart("date"); ?>
<?php echo (IsSet($verify["doit"]) ? "&nbsp;[Verify]" : ($doit ? "" : "&nbsp;[Optional]")) ?></center>
</fieldset>
<hr />
<center><?php $form->AddInputPart("doit"); ?></center>