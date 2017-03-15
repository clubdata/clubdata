{assign value=$APerr->getErrorLvlTxt() var=errLvl}
{assign value=$APerr->getMessages() var=errText}
<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
    <TD class="light_border_left"></TD>
    <TD>
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
    </TD>
    <TD class="light_border_right"></TD>
</TR>
</TABLE>

