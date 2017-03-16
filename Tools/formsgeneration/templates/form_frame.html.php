<?php
	if($error_message!="")
	{

/*
 * There was a validation error.  Display the error message associated with
 * the first field in error.
 */
 		$active = 0;
 		$output = '<b>'.$error_message.'</b>';
 		$title_was = $title;
 		$title = "Validation error";
 		$icon='';
 		require(dirname(__FILE__).'/message.html.php');
 		$title = $title_was;
 		$active = 1;
	}
?>
<div id="feedback" style="text-align: center;"></div>
<br />
<div id="wholeform">
<center><table summary="Form table" border="1" bgcolor="#c0c0c0" cellpadding="2" cellspacing="1">
<tr>
<td bgcolor="#000080" style="border-style: none;"><font color="#ffffff"><b><?php echo $title; ?></b></font></td>
</tr>

<tr>
<td style="border-style: none;"><?php

	/*
	 * Include the form body template
	 */
	include($body_template);

?></td>
</tr>
</table></center>
</div>