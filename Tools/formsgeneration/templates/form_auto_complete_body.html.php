<fieldset>
<legend><b>Type a few characters in the color or font fields</b></legend>
<center><table>

<tr>
<th align="right"><?php $form->AddLabelPart(array('FOR'=>'color')); ?></th>
<td><?php
	$form->AddInputPart('color');
	$form->AddInputPart('complete_color');
?><span id="complete_color_feedback"></span></td>
</tr>

<tr>
<th align="right"><?php $form->AddLabelPart(array('FOR'=>'font')); ?></th>
<td><?php
	$form->AddInputPart('font');
	$form->AddInputPart('complete_font');
?><span id="complete_font_feedback"></span>
</td>
</tr>

</table></center>
</fieldset>