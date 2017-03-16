<fieldset>
<legend><b>Choose a currency</b></legend>
<center><table>

<tr>
<th><?php $form->AddLabelPart(array("FOR"=>"currency")); ?></th>
</tr>

<tr>
<td align="center"><?php $form->AddInputPart("currency"); ?></td>
</tr>

<tr>
<td align="center"><?php
		if(IsSet($verify["currency"]))
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