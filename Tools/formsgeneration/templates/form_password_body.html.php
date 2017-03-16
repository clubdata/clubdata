<CENTER><TABLE>

<TR>
<TH ALIGN="right">Access name:</TH>
<TD><?php $form->AddInputPart("access_name");
if(IsSet($verify["access_name"]))
	$form->AddDataPart("&nbsp;[Verify]");
?>
</TD>
</TR>

<TR>
<TH ALIGN="right">Password:</TH>
<TD><?php $form->AddInputPart("password");
if(IsSet($verify["password"])
|| IsSet($verify["confirm_password"]))
	$form->AddDataPart("&nbsp;[Verify]</TD></TR>\n");
?>
</TD>
</TR>

<TR>
<TD COLSPAN="2" ALIGN="center"><HR></TD>
<TR>

<TR>
<TD COLSPAN="2" ALIGN="center"><?php $form->AddInputPart("doit"); ?></TD>
</TR>

</TABLE></CENTER>
