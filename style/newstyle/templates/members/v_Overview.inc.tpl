<table class="vMain" width="100%" cellspacing="0" cellpadding="0" BORDER="0">
<TR>
<TD class="light_border_left"></TD>
<TD>
<TABLE CLASS='listTable' BORDER='0' WIDTH='100%'>
<TR>
<TH CLASS='title' COLSPAN='1' WIDTH="50%">{lang Privat}</TH>
<TH CLASS='title' COLSPAN='1' WIDTH="50%">{lang Firm}</TH>
</TR>
<TR>
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {getDescription id=$LeftSide.Salutation_ref|default:'' table='Salutation'}
    </TD>
    <TD rowspan="9" style="text-align:right">
    {foreach key=schluessel item=picture from=$LeftSide}
      {if (substr($schluessel,-5) == '_link' && !empty($picture))}
        {if ( substr($picture,-4) == '.jpg' || substr($picture,-4) == '.png' ||
              substr($picture,-4) == '.gif' || substr($picture,-5) == '.jpeg')}
          <img src="{image_path mode=small img=$picture}" alt=""><BR>{$picture}<BR>
        {else}
          <a href="{image_path img=$picture}">{$picture}</a><BR>
        {/if}
      {/if}
    {/foreach}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    {$LeftSide.Title|default:''}&nbsp;{$LeftSide.Lastname|default:''},&nbsp;{$LeftSide.Firstname|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$LeftSide.Address|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$LeftSide.ZipCode|default:''}&nbsp;{$LeftSide.Town|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {getDescription id=$LeftSide.Country_ref|default:'' table='Country'}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        &nbsp;
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Tel}:
    </TD>
    <TD CLASS='Daten'>
        {$LeftSide.Telephone|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Fax}:
    </TD>
    <TD CLASS='Daten'>
        {$LeftSide.Fax|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Email}:
    </TD>
    <TD CLASS='Daten'>
        {$LeftSide.Email|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang HTML}:
    </TD>
    <TD CLASS='Daten'>
        {$LeftSide.Html|default:''}
    </TD>
</TR>
</TABLE>
</TD>
<!-- FIRM -->
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    {$RightSide.FirmName_ml|default:''}
    </TD>
    <TD rowspan="9" style="text-align:right">
    {foreach key=schluessel item=picture from=$RightSide}
      {if (substr($schluessel,-5) == '_link' && !empty($picture))}
        {if ( substr($picture,-4) == '.jpg' || substr($picture,-4) == '.png' ||
              substr($picture,-4) == '.gif' || substr($picture,-5) == '.jpeg')}
          <img src="{image_path mode=small img=$picture}" alt=""><BR>{$picture}<BR>
        {else}
          <a href="{image_path img=$picture}">{$picture}</a><BR>
        {/if}
      {/if}
    {/foreach}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    {$RightSide.FirmDepartment|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$RightSide.Address|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$RightSide.ZipCode|default:''}&nbsp;{$RightSide.Town|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {getDescription id=$RightSide.Country_ref|default:'' table='Country'}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
        &nbsp;
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Tel}:
    </TD>
    <TD CLASS='Daten'>
        {$RightSide.Telephone|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Fax}:
    </TD>
    <TD CLASS='Daten'>
        {$RightSide.Fax|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Email}:
    </TD>
    <TD CLASS='Daten'>
        {$RightSide.Email|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang HTML}:
    </TD>
    <TD CLASS='Daten'>
        {$RightSide.Html|default:''}
    </TD>
</TR>
</TABLE>
</TD>
</TR>
<!-- Memberinformation -->
<TR>
<TH CLASS='title' COLSPAN='2' WIDTH="100%">{lang Memberinfo}</TH>
</TR>
<TR>
<TD COLSPAN="1" >
<TABLE WIDTH="100%">
<TR>
    <TD CLASS='Description'>
    {lang Membertype}:
    </TD>
    <TD COLSPAN='2' CLASS='Daten'>
        {getDescription id=$Memberinfo.Membertype_ref|default:'' table='Membertype'}
    </TD>
</TR>
{if !empty($Memberinfo.MainMemberID)}
<TR>
    <TD CLASS='Description'>
    {lang Full member}:
    </TD>
    <TD CLASS='Daten'>
    <a href='{$INDEX_PHP}?mod=members&view=Overview&MemberID={$Memberinfo.MainMemberID.Adr_MemberID}'>
    {$Memberinfo.MainMemberID.Lastname}, {$Memberinfo.MainMemberID.Firstname}
    </a>
    </TD>
</TR>
{/if}
<TR>
    <TD CLASS='Description'>
    {lang Entrance}:
    </TD>
    <TD CLASS='Daten'>
    {$Memberinfo.Entrydate|regex_replace:"/0000-00-00/":""|date_format:"%d.%m.%Y"}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Information sharing}:
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$Memberinfo.InfoGiveOut_ref|default:'' table='InfoGiveOut'}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Information in WWW}:
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$Memberinfo.InfoWWW_ref|default:'' table='InfoWWW'}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Preferred language}:
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$Memberinfo.Language_ref|default:'' table='Language'}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Birthdate}:
    </TD>
    <TD CLASS='Daten'>
    {$Memberinfo.Birthdate|regex_replace:"/0000-00-00/":""|date_format:"%d.%m.%Y"}
    </TD>
</TR>
{if !empty($Memberinfo.Remarks)}
<TR>
    <TD CLASS='Description'>
    {lang Remarks}:
    </TD>
    <TD CLASS='Daten'>
        {$Memberinfo.Remarks|default:''}
    </TD>
</TR>
{/if}
{if !empty($Memberinfo.Selection)}
<TR>
    <TD CLASS='Description'>
    {lang Selection}:
    </TD>
    <TD CLASS='Daten'>
        {$Memberinfo.Selection|default:''}
    </TD>
</TR>
{/if}
</TABLE>
</TD>
<TD WIDTH="50%" VALIGN=TOP>
<TABLE WIDTH="100%">
{if !empty($Memberinfo.associatedMembers)}
    {foreach name=assocLoop item=assoc from=$Memberinfo.associatedMembers}
    <TR>
        <TD CLASS='Description'>
        {if $smarty.foreach.assocLoop.first}
        {lang Associated members}:
        {else}
        &nbsp;
        {/if}
        </TD>
        <TD CLASS='Daten'>
            <a href='{$INDEX_PHP}?mod=members&view=Overview&MemberID={$assoc.MemberID}'>
            {$assoc.Lastname}, {$assoc.Firstname}
            </a>
        </TD>
    </TR>
    {/foreach}
{/if}
{assign var=attribShown value=false}
{foreach name=assocLoop item=assoc from=$Attributes}
{if !empty($assoc)}
<TR>
    <TD CLASS='Description'>
    {if $attribShown == false}
    {lang Attributes}:
    {assign var=attribShown value=true}
    {else}
    &nbsp;
    {/if}
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$assoc table='Attributes'}
    </TD>
</TR>
{/if}
{/foreach}
</TABLE>
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
