<CENTER><TABLE>

<TR>
<TH ALIGN="right">File:</TH>
<TD><?php $form->AddInputPart("userfile");
if(IsSet($verify["userfile"]))
	$form->AddDataPart("&nbsp;[Verify]");

?></TD>
</TR>

<TR>
<TD COLSPAN="2" ALIGN="center"><HR></TD>
</TR>

<TR>
<TD COLSPAN="2" ALIGN="center"><?php $form->AddInputPart("doit"); ?></TD>
</TR>

</TABLE></CENTER>
