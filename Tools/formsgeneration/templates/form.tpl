{include file="formerror.tpl"}
<center><table summary="Form table" border="1" bgcolor="#c0c0c0" cellpadding="2" cellspacing="1">
<tr>
<td bgcolor="#000080" style="border-style: none;"><font color="#ffffff"><b>{$title}</b></font></td>
</tr>
<tr>
<td style="border-style: none">
<center><table summary="Input fields table">

<tr>
<th align="right">{label for="email"}:</th>
<td>{input name="email"}</td>
{if isset($verify.email)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for=$credit_card_field}:</th>
<td>{input name=$credit_card_field}</td>
{if isset($verify.credit_card_number)}<td>{$mark}</td>
{else}<td>{if not $doit}[Optional]{/if}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="credit_card_type"}:</th>
<td>{input name="credit_card_type"}</td>
{if isset($verify.credit_card_type)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="user_name"}:</th>
<td>{input name="user_name"}</td>
{if isset($verify.user_name)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="age"}:</th>
<td>{input name="age"}</td>
{if isset($verify.age)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="weight"}:</th>
<td>{input name="weight"}</td>
{if isset($verify.weight)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="home_page"}:</th>
<td>{input name="home_page"}</td>
{if isset($verify.home_page)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="alias"}:</th>
<td>{input name="alias"}</td>
{if isset($verify.alias)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="password"}:</th>
<td>{input name="password"}</td>
{if isset($verify.password) or isset($verify.confirm_password)}<td rowspan="2">{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="confirm_password"}:</th>
<td>{input name="confirm_password"}</td>
</tr>

<tr>
<th align="right">{label for="reminder"}:</th>
<td>{input name="reminder"}</td>
{if isset($verify.reminder)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right" valign="top">{label for="interests"}:</th>
<td>{input name="interests"}</td>
{if isset($verify.email_notification)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th colspan="3">Receive notification when approved by:</th>
</tr>

<tr>
<th align="right">{label for="email_notification"}:</th>
<td>{input name="email_notification"}</td>
{if isset($verify.email_notification)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="phone_notification"}:</th>
<td>{input name="phone_notification"}</td>
{if isset($verify.phone_notification)}<td>{$mark}</td>
{/if}
</tr>

<tr>
<th colspan="3">Subscription type:</th>
</tr>

<tr>
<th align="right">{label for="administrator_subscription"}:</th>
<td>{input name="administrator_subscription"}</td>
{if isset($verify.administrator_subscription)}<td rowspan="3">{$mark}</td>
{/if}
</tr>

<tr>
<th align="right">{label for="user_subscription"}:</th>
<td>{input name="user_subscription"}</td>
</tr>

<tr>
<th align="right">{label for="guest_subscription"}:</th>
<td>{input name="guest_subscription"}</td>
</tr>

{if !$doit}
<tr>
<th align="right">{label for="toggle"}</th>
<td>{input name="toggle"}</td>
<td >&nbsp;</td>
</tr>
{/if}

<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<th align="right">{label for="agree"}:</th>
<td>{input name="agree"}</td>
{if isset($verify.agree)}<td>{$mark}</td>
{/if}
</tr>

{if !$doit}
<tr>
<td colspan="3" align="center"><hr /></td>
</tr>

<tr>
<td colspan="3" align="center">{input name="image_subscribe"} {input name="button_subscribe"}</td>
</tr>

<tr>
<td colspan="3" align="center">{input name="button_subscribe_with_content"}{input name="doit"}</td>
</tr>
{/if}
</table></center>
</td>
</tr>
</table></center>
