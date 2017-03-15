{assign value=$APerr->getErrorLvlTxt() var=errLvl}
{assign value=$APerr->getMessages() var=errText}
<div class=error>
<table CLASS="{$errLvl}" cellspacing="0">
<tr>
<th colspan="3" rowspan="1">{$errLvl|translate}
</th>
</tr>
<tr>
<td colspan="1" rowspan="1"><br>
</td>
<td>
{foreach from=$errText item=aktErr}
{$aktErr}<BR>
{/foreach}
</td>
<td colspan="1" rowspan="1"><br>
</td>
</tr>
<tr>
<td colspan="3" rowspan="1"><br>
</td>
</tr>
</table>
</div>

