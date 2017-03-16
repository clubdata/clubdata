<center><table summary="Input fields table">

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"first")); ?>:</th>
<td><?php $form->AddInputPart("first"); ?></td>
<td><?php echo (IsSet($verify["first"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"second")); ?>:</th>
<td><?php $form->AddInputPart("second"); ?></td>
<td><?php echo (IsSet($verify["second"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<td colspan="3" align="center"><?php $form->AddInputPart("doit"); ?></td>
</tr>

</table></center>
