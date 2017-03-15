<?php /* Smarty version 2.6.10, created on 2015-06-13 18:05:04
         compiled from search/v_Invoice.inc.tpl */ ?>
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
<?php echo $this->_tpl_vars['mainform']; ?>

</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>
<div id="invoiceData" class="modal_window">
<div style="border: ridge 5px; color: black; background-color: white; margin: 10px; width: 250px; height: 150px">
<div style="background-color: #A5CBF7; padding: 2px;">Please enter invoice year:</div><BR>
<div style="text-align: center;">Invoice year:&nbsp;<input type="text" style="margin: 10px; width: 4em;" id="invoiceYear" name="invoiceYear"></div>
<input style="float: left; margin: 5px;" type="submit" onClick="close_modal();doSubmit('list','Invoiceletter');" value="Send">&nbsp;
<input style="float: right; margin: 5px;" type="button" onClick="close_modal();" value="Abort">
</div>
</div>