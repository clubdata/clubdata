<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
  <COLGROUP><COL WIDTH='1%'><COL WIDTH='99%'></COLGROUP>
  <TR>
    <TD>
    {"Conference to subscribe"|lang}:
    <input type="hidden" name="SubscriptionID" value="{$SubscriptionID}">
    </TD>
    <TD>
    {html_options id=ConferenceID name=ConferenceID options=$subscription selected=$subscriptionSelected}
    </TD>
  </TR>
  <TR>
    <TD>
    {"Number of participants"|lang}:
    </TD>
    <TD>
    <input id="numPart" type="text" onkeydown="changeColorOnKey(this)" onblur="changeColorIfChanged(this, '')" maxlength="50" value="{$numPersons}" name="numPart"/>
    </TD>
  </TR>
</TABLE>
</TD>
<TD class="light_border_right"></TD>
</TR>
<TR>
    <TD width="10"><img src="style/newstyle/images/light_corner_ll.png" height="13" width="10" border="0"></TD>
    <td class="light_border_lower"></td>
    <td><img src="style/newstyle/images/light_corner_lr.png" width="13" border="0"></td>
</TR>
</TABLE>
