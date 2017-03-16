<table align="center">

<tr>
<th><?php $form->AddLabelPart(array("FOR"=>"address")); ?></th>
<td><?php $form->AddInputPart("address"); ?>
<?php
		if(IsSet($verify["map"]))
		{
?>
&nbsp;[Verify]
<?php
		}
?></td>
</tr>

<tr>
<th><?php $form->AddLabelPart(array("FOR"=>"country")); ?></th>
<td><?php $form->AddInputPart("country"); ?>
<?php
		if(IsSet($verify["country"]))
		{
?>
&nbsp;[Verify]
<?php
		}
?>
&nbsp;
<?php $form->AddInputPart("locate_address"); ?></td>
</tr>

</table>

<fieldset>
<legend><b><?php $form->AddLabelPart(array("FOR"=>"map")); ?></b></legend>
<div align="center"><?php $form->AddInputPart("map"); ?>
<?php
		if(IsSet($verify["map"]))
		{
?>
&nbsp;[Verify]
<?php
		}
?></div>
</fieldset>
<hr />
<div align="center"><?php $form->AddInputPart("refresh"); ?> <?php $form->AddInputPart("doit"); ?></div>