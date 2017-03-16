<center><table summary="Input fields table">

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"email")); ?>:</th>
<td><?php $form->AddInputPart("email"); ?></td>
<?php
	if(IsSet($verify["email"]))
	{

/*
 * If this field value did not validate, mark it clearly so the user may
 * notice and correct it.
 */
?>
<td>[Verify]</td>
<?php
	}
?>
</tr>

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"credit_card_number")); ?>:</th>
<td><?php $form->AddInputPart("credit_card_number"); ?></td>
<td><?php echo (IsSet($verify["credit_card_number"]) ? "[Verify]" : ($doit ? "" : "[Optional]")) ?></td>
</tr>

<tr>
<th align="right"><?php $form->AddLabelPart(array("FOR"=>"credit_card_type")); ?>:</th>
<td><?php $form->AddInputPart("credit_card_type"); ?></td>
<td><?php echo (IsSet($verify["credit_card_type"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"user_name")); ?>:</th>
<td><?php $form->AddInputPart("user_name"); ?></td>
<td><?php echo (IsSet($verify["user_name"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"age")); ?>:</th>
<td><?php $form->AddInputPart("age"); ?></td>
<td><?php echo (IsSet($verify["age"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"weight")); ?>:</th>
<td><?php $form->AddInputPart("weight"); ?></td>
<td><?php echo (IsSet($verify["weight"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"home_page")); ?>:</th>
<td><?php $form->AddInputPart("home_page"); ?></td>
<td><?php echo (IsSet($verify["home_page"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"alias")); ?>:</th>
<td><?php $form->AddInputPart("alias"); ?></td>
<td><?php echo (IsSet($verify["alias"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"password")); ?>:</th>
<td><?php $form->AddInputPart("password"); ?></td>
<td rowspan="2"><?php echo ((IsSet($verify["password"]) || IsSet($verify["confirm_password"])) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"confirm_password")); ?>:</th>
<td><?php $form->AddInputPart("confirm_password"); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"reminder")); ?>:</th>
<td><?php $form->AddInputPart("reminder"); ?></td>
<td><?php echo (IsSet($verify["reminder"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right" valign="top"><?php $form->AddLabelPart(array("FOR"=>"interests")); ?>:</th>
<td><?php $form->AddInputPart("interests"); ?></td>
<td><?php echo (IsSet($verify["interests"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th colspan="3">When approved, receive notification by:</th>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"email_notification")); ?>:</th>
<td><?php $form->AddInputPart("email_notification"); ?></td>
<td rowspan="2"><?php echo (IsSet($verify["email_notification"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"phone_notification")); ?>:</th>
<td><?php $form->AddInputPart("phone_notification"); ?></td>
</tr>

<tr>
<th colspan="3">Subscription type:</th>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"administrator_subscription")); ?>:</th>
<td><?php $form->AddInputPart("administrator_subscription"); ?></td>
<td rowspan="3"><?php echo (IsSet($verify["administrator_subscription"]) ? "[Verify]" : ""); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"user_subscription")); ?>:</th>
<td><?php $form->AddInputPart("user_subscription"); ?></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"guest_subscription")); ?>:</th>
<td><?php $form->AddInputPart("guest_subscription"); ?></td>
</tr>

<?php
	if(!$doit)
	{
?>
<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"toggle")); ?>:</th>
<td><?php $form->AddInputPart("toggle"); ?></td>
<td >&nbsp;</td>
</tr>
<?php
	}
?>

<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<th align="right">
<?php $form->AddLabelPart(array("FOR"=>"agree")); ?>:</th>
<td><?php $form->AddInputPart("agree"); ?></td>
<td ><?php echo (IsSet($verify["agree"]) ? "[Verify]" : ""); ?></td>
</tr>

<?php
	if(!$doit)
	{

/*
 * If the form was submitted with valid values, there is no need to display
 * the submit button again.
 */
?>
<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<td colspan="3" align="center"><?php $form->AddInputPart("image_subscribe"); ?> <?php $form->AddInputPart("button_subscribe"); ?></td>
</tr>

<tr>
<td colspan="3" align="center"><?php $form->AddInputPart("button_subscribe_with_content"); $form->AddInputPart("doit"); ?></td>
</tr>

<?php
	}
?>
</table></center>
