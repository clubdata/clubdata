{if $error_message ne ""}
<center><table summary="Validation error table" border="1" bgcolor="#c0c0c0" cellpadding="2" cellspacing="1">
<tr>
<td bgcolor="#808080" style="border-style: none"><table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td><b><font color="#c0c0c0">Validation error</font></b></td>
</tr>
</table></td>
</tr>
<tr>
<td style="border-style: none"><table cellpadding="0" cellspacing="0">
<tr>
<td><table frame="box" bgcolor="#FF8000">
<tr>
<td><b>!</b></td>
</tr>
</table></td>
<td><table>
<tr>
<td><b>{$error_message}</b></td>
</tr>
</table></td>
</tr>
{if count($verify) gt 1}
<tr>
<td><table frame="box" bgcolor="#FF8000">
<tr>
<td><b>!</b></td>
</tr>
</table></td>
<td><table>
<tr>
<td><b>Please verify also the remaining fields marked with [Verify]</b></td>
</tr>
</table></td>
</tr>
{/if}
</table></td>
</tr>
</table></center>
<br />
{/if}
