<center><table summary="Input fields table">

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"description")); ?>:</th>
<td><?php $form->AddInputPart("description"); ?></td>
<?php
	if(IsSet($verify["description"]))
	{
?>
<td>[Verify]</td>
<?php
	}
?>
</tr>

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"file")); ?>:</th>
<td><?php $form->AddInputPart("file"); ?></td>
<?php
	if(IsSet($verify["file"]))
	{
?>
<td>[Verify]</td>
<?php
	}
?>
</tr>

<?php
	if(!$doit)
	{
?>
<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<td colspan="3" align="center"><?php $form->AddInputPart("doit"); ?></td>
</tr>
<?php
	}
?>
</table></center>
