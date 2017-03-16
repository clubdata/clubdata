<center><table summary="Validation error table" border="1" bgcolor="#c0c0c0" cellpadding="2" cellspacing="1">
<tr>
<td bgcolor="<?php echo $active ? '#000080': '#808080'; ?>" style="border-style: none"><table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td><b><font color="<?php echo $active ? '#ffffff': '#c0c0c0'; ?>"><?php echo $title; ?></font></b></td>
</tr>
</table></td>
</tr>
<tr>
<td style="border-style: none"><table cellpadding="0" cellspacing="0">
<tr>
<td><?php echo strlen($icon) ? $icon : '<table cellpadding="0" cellspacing="0">
<tr>
<td><table frame="box" bgcolor="#FF8000">
<tr>
<td><b>!</b></td>
</tr>
</table>
</tr>
</table>' ;?></td>
<td><table>
<tr>
<td><?php echo strlen($output) ? $output : '&nbsp;' ;?></td>
</tr>
</table></td>
</tr>
<?php
/*
 * If there was more than on field in error let the user know.
 */
		if(count($verify)>1
		&& !$form->ShowAllErrors)
		{
?>
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
<?php
		}
?>
</table></td>
</tr>
</table></center>
