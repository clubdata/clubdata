<TABLE class="navigator" cellspacing=0 cellpadding=0 width="200" BORDER="0">
<tr>
    <TD><img src="style/newstyle/images/corner_ul.png" width="10" border="0"></TD>
    <td></td>
    <td><img style="float: right;" src="style/newstyle/images/corner_ur.png" width="10" border="0"></td>
</tr>
<!-- Main -->
<tr>
    <td></td>
    <td>
        <a href="{$INDEX_PHP}?mod=main">
            <img src="style/newstyle/images/home3_small.png" alt="back" border="0">
            {lang Home}
        </a>
    </td>
</tr>
{if !isLoggedIn() || $navigatorMenu == 'MAIN' }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=main&view=Copyright"
             nav_label="Copyright"|lang}
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=main&view=Impressum"
             nav_label="Impressum"|lang}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<!-- Member -->
{if ( isLoggedIn() ) }
<tr>
    <td></td>
    <td>
        <a href="{$INDEX_PHP}?mod=members">
            <img src="style/newstyle/images/people_circle.png" alt="members" border="0">
            {lang Member}
        </a>
    </td>
    <td></td>
</tr>
{if $navigatorMenu == 'MEMBERS'  }
  {assign value='Do you want to add a new member'|lang var=securequestion}
  {if ( !isMember() && getUserType(INSERT,'Member') ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_javascript="onclick=\"return newMember('$securequestion');\""
             nav_href="$INDEX_PHP?mod=members&Action=INSERT"
             nav_label="New Member"|lang}
  {/if}
  {if ( !isMember() ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Member"
             nav_label="Search for members"|lang}
  {/if}
{/if}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<!-- Search / Communication -->
{if ( getClubUserInfo("MemberOnly") === false && (getUserType("Create",'Email') || getUserType("Create",'Infoletter') ||  getUserType(VIEW,'Payments')) )}
<tr>
    <td></td>
<td>
    <a href="{$INDEX_PHP}?mod=search&view=Search">
        <img src="style/newstyle/images/eyes_circle.png" alt="search" border="0">
        {lang Communication}
    </a>
</td>
    <td></td>
</tr>
{if ($navigatorMenu == 'COMMUNICATION' )}
  {if ( getUserType("Create",'Email') ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Email"
             nav_label="Send email"|lang}
  {/if}
  {if ( getUserType("Create",'Infoletter') ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Infoletter"
             nav_label="Send Infoletter"|lang}
  {/if}
  {if ( getUserType(VIEW,'Payments') ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Invoice"
             nav_label="Send Invoice"|lang}
  {/if}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
{/if}
<!-- Queries -->
{if ( getUserType(VIEW, "Member") ) }
<tr>
    <td></td>
<td>
    <a href="{$INDEX_PHP}?mod=queries&view=Queries">
        <img src="style/newstyle/images/spreadsheet_circle.png" alt="queries" border="0">
        {lang Queries}
    </a>
</td>
    <td></td>
</tr>
{if $navigatorMenu == 'QUERIES'}
  {if ( getClubUserInfo("MemberOnly") === false && getUserType(VIEW, "Member") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=queries&view=MemberSummary"
             nav_label="Member summary"|lang}
  {/if}
  {if ( getClubUserInfo("MemberOnly") === false && getUserType(VIEW, "Member") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=queries&view=Statistics"
             nav_label="Statistics"|lang}
  {/if}
  {if ( getUserType(VIEW, "Member") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=queries&view=AddressLists"
             nav_label="Addresslists"|lang}
  {/if}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
{/if}
<!-- Jobs -->
{if ( getClubUserInfo("MemberOnly") === false && (getUserType(VIEW, "Payments") || getUserType(VIEW, "Fees")) ) }
<tr>
    <td></td>
<td>
    <a href="{$INDEX_PHP}?mod=jobs&view=Jobs">
        <img src="style/newstyle/images/jobs_circle.png" alt="Jobs" border="0">
        {lang Accounting}
    </a>
</td>
    <td></td>
</tr>
{if $navigatorMenu == 'ACCOUNTING'  }
  {if ( getUserType(VIEW, "Payments") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Payments"
             nav_label="Search for payments"|lang}
  {/if}
  {if ( getUserType(VIEW, "Fees") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Fees"
             nav_label="Search for fees"|lang}
  {/if}
  {if ( getUserType(VIEW, "Payments") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=jobs&view=EndOfYear"
             nav_label="End of Year Updates"|lang}
  {/if}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
{/if}
<!-- Conferences -->
{if ( getClubUserInfo("MemberOnly") === false && getUserType(VIEW, "Conferences") ) }
<tr>
    <td></td>
<td>
    <a href="{$INDEX_PHP}?mod=conferences&view=Conferences">
        <img src="style/newstyle/images/conference_circle.png" alt="Conference" border="0">
        {lang Conferences}
    </a>
</td>
    <td></td>
</tr>
{if $navigatorMenu == 'CONFERENCES'  }
  {if ( getUserType(VIEW, "Conferences") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=conferences&view=List&InitView=1"
             nav_label="List conferences"|lang}
  {/if}
  {if ( getUserType(VIEW, "Conferences") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=search&view=Conferences"
             nav_label="Search for conferences"|lang}
  {/if}
  {if ( getUserType(INSERT, "Conferences") ) }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=conferences&view=Add"
             nav_label="Add a new conference"|lang}
  {/if}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
{/if}
<!-- Settings -->
{if ( getClubUserInfo("MemberOnly") === false && isLoggedIn() ) }
<tr>
    <td></td>
<td>
    <a href="{$INDEX_PHP}?mod=settings&view=Settings">
        <img src="style/newstyle/images/settings_circle.png" alt="Jobs" border="0">
        {lang Settings}
    </a>
</td>
    <td></td>
</tr>
{if $navigatorMenu == 'SETTINGS'  }
<tr>
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=settings&view=Columns"
             nav_label="Select Columns"|lang}
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=settings&view=Personal"
             nav_label="Personal settings"|lang}
</tr>
<tr>
<td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
{/if}
{/if}
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<!-- Administration -->
{if ( getUserType(ADMINISTRATOR,'Member') ) }
    <tr>
    <td></td>
    <td>
        <a href="{$INDEX_PHP}?mod=admin&view=Admin">
            <img src="style/newstyle/images/tools_circle.png" alt="admin" border="0">
            {lang Administration}
        </a>
    </td>
    <td></td>
    </tr>
{if $navigatorMenu == 'ADMIN' }
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=admin&view=Users"
             nav_label="Users"|lang}
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=admin&view=Configuration"
             nav_label="Configuration"|lang}
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=admin&view=Database"
             nav_label="Database"|lang}
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=admin&view=Log"
             nav_label="Log"|lang}
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=admin&view=Backup"
             nav_label="Backup"|lang}
{/if}
{/if}
<!-- Logoff -->
{if ( isLoggedIn() == true ) }
<tr>
    <td colspan="3"><img border="0" height="20" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<tr>
    <td></td>
    <td>
        <a href="{$INDEX_PHP}?mod=main&view=Logoff">
            <img src="style/newstyle/images/exit_circle.png" alt="back" border="0">
            {lang Logoff}
        </a>
    </td>
</tr>
{else}
{if ( $demoMode ) }
<tr>
<td></td>
<td>
<div style="border: 1px solid" class="navigator_label">
Demo version:<P>
To access Clubdata you need to provide a valid username/password pair.<BR>
</P>
<P>To use this demo use <BR>
<B>admin/admin</B> for administration access,<BR>
<B>AllUser/AllUser</B> for a typical user,<BR>
<B>147/mitglied</B> for a member view<BR>
</P>
</div>
</td>
</tr>
{/if}
<tr>
<td></td>
<td colspan="2">
<form action="{$INDEX_PHP}" method="post">
<table>
<tr>
<td colspan="1" class="navigator_label">{lang Username}</td>
</tr>
<tr>
<td colspan="1"><input class="text" name="Login" type="text" style="width: 80%;" maxlength="32"/></td>
</tr>
<tr>
<td colspan="1" class="navigator_label">{lang Password}</td>
</tr>
<tr>
<td colspan="1"><input class="text" name="PW_Login" type="password" style="width: 80%;" maxlength="32"/></td>
</tr>
<tr>
<td class='clear'><div class='BUTTON'><span><input onClick="submit();" class="button" type="submit" value="Login"/></span></div></td>
</tr>
</table>
</form>
</td>
</tr>
{/if}
<tr>
    <TD><img src="style/newstyle/images/corner_ll.png" width="10" border="0"></TD>
    <td></td>
    <td><img style="float: right;" src="style/newstyle/images/corner_lr.png" width="10" border="0"></td>
</tr>


</table>
