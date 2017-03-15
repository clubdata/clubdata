<DIV class=listTable>
<INPUT type="hidden" name="cllist_id" VALUE='{$listObj->id}'>
{if $listObj->pagecount|default:1 > 1}
<table>
<tr>
    <td>
        <a href={$listObj->firstLink}>
        {html_image file=$STYLE_DIR|cat:"images/datapager/first.gif" border=0}
        </a>
    </td>
    <td>
        <a href={$listObj->previousLink}>
        {html_image file=$STYLE_DIR|cat:"images/datapager/previous.gif" border=0}
        </a>
    </td>
    <td>
    {$listObj->pageNr|default:1} / {$listObj->pagecount}
    <td>
        <a href={$listObj->nextLink}>
        {html_image file=$STYLE_DIR|cat:"images/datapager/next.gif" border=0}
        </a>
    </td>
    <td>
        <a href={$listObj->lastLink}>
        {html_image file=$STYLE_DIR|cat:"images/datapager/last.gif" border=0}
        </a>
    </td>
</tr>
</table>
{/if}
<table class="listTable">
<TR>
    <TH class="listTable" WIDTH='1%'>#</TH>
{if $listObj->getConfig("selectRowsFlg") == true }
    <TH class="listTable" style='width: 1%;'>x</TH>
{/if}
{if ( $listObj->getConfig("changeFlg") == true ) }
    <TH CLASS="listTable">{lang Del}</TH>
    <TH CLASS="listTable">{lang Edit}</TH>
{/if}

{* SHOW TABLE HEADER *}
{section name=row loop=$listObj->listHeadRows }
    {section name=col loop=$listObj->listHeadRows[row] }
        <th class="listTable" $sortID>{$listObj->listHeadRows[row][col]}</TH>
    {/section}
{/section}
</TR>
{* SHOW TABLE BODY *}
{section name=row loop=$listObj->listBodyRows }

    {if ($smarty.section.row.index % 2)}
        {assign var=class value="even"}
    {else}
        {assign var=class value="odd"}
    {/if}

    {counter name=colCount assign=curCol start=0}
    <TR CLASS="{$class}">
        <TD CLASS="listTable" style='text-align: right' >
            {$listObj->listBodyRows[row][$curCol]|default:''}
            {counter name=colCount}
        </TD>
        {if ( $listObj->getConfig("selectRowsFlg") == true ) }
        <TD class="listTable" style='width: 1%;' >
            {$listObj->listBodyRows[row][$curCol]|default:''}
            {counter name=colCount}
        </TD>
        {/if}
        {if ( $listObj->getConfig("changeFlg") == true )}
            <TD class="listTable" style='width: 1%;' >
                 {$listObj->listBodyRows[row][$curCol]|default:''}
                 {counter name=colCount}
            </TD>
            <TD class="listTable" style='width: 1%;' >
                {$listObj->listBodyRows[row][$curCol]|default:''}
                {counter name=colCount}
            </TD>
        {/if}
        {section name=col loop=$listObj->listBodyRows[row] start=$curCol}
            <TD class="listTable" >
                {$listObj->listBodyRows[row][col]|default:''}
            </TD>
        {/section}
    </TR>
{sectionelse}
{/section}
</table>