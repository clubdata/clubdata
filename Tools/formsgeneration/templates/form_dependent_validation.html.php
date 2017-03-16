<center><table summary="Input fields table">

<tr>
<td align="right"><?php $form->AddInputPart("condition"); ?></td>
<td><b><?php $form->AddLabelPart(array("FOR"=>"condition")); ?></b></td>
<td><?php echo (IsSet($verify["condition"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"dependent")); ?>:</th>
<td><?php $form->AddInputPart("dependent"); ?></td>
<td><?php echo (IsSet($verify["dependent"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<td colspan="3" align="center"><?php $form->AddInputPart("doit"); ?></td>
</tr>

</table></center>
