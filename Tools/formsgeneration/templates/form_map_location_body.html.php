<fieldset><legend><b><?php $form->AddLabelPart(array("FOR"=>"map")); ?></b></legend>
<center><?php $form->AddInputPart("map"); ?> <?php
if(IsSet($verify["map"]))
{
	?> &nbsp;[Verify] <?php
}
?></center>
</fieldset>
<hr />
<center><?php $form->AddInputPart("refresh"); ?> <?php $form->AddInputPart("doit"); ?></center>
