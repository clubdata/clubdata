<fieldset><legend><b>Choose location</b></legend>
<center>
<table>

	<tr>
		<th><?php $form->AddLabelPart(array("FOR"=>"continent")); ?></th>
		<td>&nbsp;</td>
		<th><?php $form->AddLabelPart(array("FOR"=>"country")); ?></th>
		<td>&nbsp;</td>
		<th><?php $form->AddLabelPart(array("FOR"=>"location")); ?></th>
	</tr>

	<tr>
		<td>
		<center><?php $form->AddInputPart("continent"); ?></center>
		</td>
		<td>
		<center><?php $form->AddInputPart("update"); ?></center>
		</td>
		<td>
		<center><?php $form->AddInputPart("country"); ?></center>
		</td>
		<td>
		<center><?php $form->AddInputPart("update"); ?></center>
		</td>
		<td>
		<center><?php $form->AddInputPart("location"); ?></center>
		</td>
	</tr>

	<tr>
		<td>
		<center><?php
		if(IsSet($verify["continent"]))
		{
			?> [Verify] <?php
		}
		?></center>
		</td>
		<td>&nbsp;</td>
		<td>
		<center><?php
		if(IsSet($verify["country"]))
		{
			?> [Verify] <?php
		}
		?></center>
		</td>
		<td>&nbsp;</td>
		<td>
		<center><?php
		if(IsSet($verify["location"]))
		{
			?> [Verify] <?php
		}
		?></center>
		</td>
	</tr>

</table>
</center>
</fieldset>
<br />
<center><?php $form->AddInputPart("doit"); ?></center>
