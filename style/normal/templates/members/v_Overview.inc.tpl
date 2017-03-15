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
    {getDescription id=$Privat.Salutation_ref|default:'' table='Salutation'}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    {$Privat.Title|default:''}&nbsp;{$Privat.Lastname|default:''},&nbsp;{$Privat.Firstname|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$Privat.Address|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$Privat.ZipCode|default:''}&nbsp;{$Privat.Town|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {getDescription id=$Privat.Country_ref|default:'' table='Country'}
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
        {$Privat.Telephone|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Fax}:
    </TD>
    <TD CLASS='Daten'>
        {$Privat.Fax|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Email}:
    </TD>
    <TD CLASS='Daten'>
        {$Privat.Email|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang HTML}:
    </TD>
    <TD CLASS='Daten'>
        {$Privat.Html|default:''}
    </TD>
</TR>
</TABLE>
</TD>
<!-- FIRM -->
<TD>
<TABLE WIDTH='100%'>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    {$Firm.FirmName_ml|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Hauptdaten'>
    {$Firm.FirmDepartment|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$Firm.Address|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {$Firm.ZipCode|default:''}&nbsp;{$Firm.Town|default:''}
    </TD>
</TR>
<TR>
    <TD COLSPAN='2' CLASS='Daten'>
    {getDescription id=$Firm.Country_ref|default:'' table='Country'}
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
        {$Firm.Telephone|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Fax}:
    </TD>
    <TD CLASS='Daten'>
        {$Firm.Fax|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Email}:
    </TD>
    <TD CLASS='Daten'>
        {$Firm.Email|default:''}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang HTML}:
    </TD>
    <TD CLASS='Daten'>
        {$Firm.Html|default:''}
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
    <TD COLSPAN='2' CLASS='Daten'>
        {getDescription id=$Memberinfo.Membertype_ref|default:'' table='Membertype'}
    </TD>
</TR>
{if !empty($Memberinfo.MainMemberID)}
<TR>
    <TD CLASS='Description'>
    {lang Full member}
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
    {lang Entrance}
    </TD>
    <TD CLASS='Daten'>
    {$Memberinfo.Entrydate|regex_replace:"/0000-00-00/":""|date_format:"%d.%m.%Y"}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Information sharing}
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$Memberinfo.InfoGiveOut_ref|default:'' table='InfoGiveOut'}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Information in WWW}
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$Memberinfo.InfoWWW_ref|default:'' table='InfoWWW'}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Preferred language}
    </TD>
    <TD CLASS='Daten'>
        {getDescription id=$Memberinfo.Language_ref|default:'' table='Language'}
    </TD>
</TR>
<TR>
    <TD CLASS='Description'>
    {lang Birthdate}
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
