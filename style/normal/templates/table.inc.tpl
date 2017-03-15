<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>
{if $maxNumCols > 1}
    <COLGROUP><COL WIDTH='1%'><COL WIDTH='99%'></COLGROUP>
{/if}
{if ( empty($title) ) }
    <TR CLASS='listTable' VALIGN='MIDDLE'>
        <TH CLASS='title' COLSPAN='{math equation="mc+1" mc=$maxNumCols}'>
            $title
        </TH>
    </TR>
{/if}
{foreach key=schluessel item=edit from=$form->inputs}
    <tr><TD>{$schluessel}</TD><td>{$edit.LABEL}</td></tr>
{/foreach}
</TABLE>