<fieldset>
<legend><b>Type a few characters in the location field</b></legend>
<center><table>

<tr>
<th align="right"><?php $form->AddLabelPart(array('FOR'=>'location')); ?></th>
<td><?php
	$form->AddInputPart('location');
	$form->AddInputPart('complete_location');
?><span id="complete_location_feedback"></span></td>
</tr>

</table></center>
</fieldset>