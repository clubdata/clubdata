<fieldset>
<legend><b>Choose location</b></legend>
<center><table>

<tr>
<th><?php $form->AddLabelPart(array("FOR"=>"continent")); ?></th>
<td>&nbsp;</td>
<th><?php $form->AddLabelPart(array("FOR"=>"country")); ?></th>
<td>&nbsp;</td>
<th><?php $form->AddLabelPart(array("FOR"=>"location")); ?></th>
</tr>

<tr>
<td align="center"><?php $form->AddInputPart("continent"); ?></td>
<td align="center"><?php $form->AddInputPart("update"); ?></td>
<td align="center"><?php $form->AddInputPart("country"); ?></td>
<td align="center"><?php $form->AddInputPart("update"); ?></td>
<td align="center"><?php $form->AddInputPart("location"); ?></td>
</tr>

<tr>
<td align="center"><?php
		if(IsSet($verify["continent"]))
		{
?>
[Verify]
<?php
		}
?></td>
<td>&nbsp;</td>
<td align="center"><?php
		if(IsSet($verify["country"]))
		{
?>
[Verify]
<?php
		}
?></td>
<td>&nbsp;</td>
<td align="center"><?php
		if(IsSet($verify["location"]))
		{
?>
[Verify]
<?php
		}
?></td>
</tr>

</table></center>
</fieldset>
<br />
<center><?php $form->AddInputPart("doit"); ?></center>