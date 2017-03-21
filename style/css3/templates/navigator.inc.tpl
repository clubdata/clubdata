<div class="navigator" style="width: 200px;">
    <ul>
        <!-- Main -->
        <li>
            <a href="{$INDEX_PHP}?mod=main">
                <img src="style/css3/images/Navigator/home3_small.png" alt="back" border="0"> {lang Home}
            </a>

            <ul>
                {if !isLoggedIn() || $navigatorMenu == 'MAIN'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=main&view=Copyright"
                         nav_label="Copyright"|lang
                         nav_visible=$visible}
                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=main&view=Impressum"
                         nav_label="Impressum"|lang
                         nav_visible=$visible}
		    </ul>
        </li>
		<!-- Member -->
		{if (isLoggedIn())}
        <li>
            <a href="{$INDEX_PHP}?mod=members">
                <img src="style/css3/images/Navigator/people_circle.png" alt="members" border="0"> {lang Member}
            </a>

            <ul>
                {if $navigatorMenu == 'MEMBERS'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {assign value='Do you want to add a new member'|lang var=securequestion}

                {if (!isMember() && getUserType(INSERT,'Member'))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_javascript="onclick=\"return newMember('$securequestion');\""
                             nav_href="$INDEX_PHP?mod=members&Action=INSERT"
                             nav_label="New Member"|lang
                             nav_visible=$visible}
                {/if}

                {if (!isMember())}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Member"
                             nav_label="Search for members"|lang
                             nav_visible=$visible}
                {/if}
            </ul>
        </li>
        {/if}

        <!-- Search / Communication -->
        {if (isLoggedIn())}
        <li>
            <a href="{$INDEX_PHP}?mod=search&view=Search">
                <img src="style/css3/images/Navigator/eyes_circle.png" alt="search" border="0"> {lang Communication}
            </a>

            <ul>
                {if ($navigatorMenu == 'COMMUNICATION')}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {if (getUserType("Create",'Email'))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Email"
                             nav_label="Send email"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType("Create",'Infoletter'))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Infoletter"
                             nav_label="Send Infoletter"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType(VIEW,'Payments'))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Invoice"
                             nav_label="Send Invoice"|lang
                             nav_visible=$visible}
                {/if}
            </ul>
        </li>
        {/if}

        <!-- Queries -->
        {if (isLoggedIn())}
        <li>
            {if (getUserType(VIEW, "Member"))}
            <a href="{$INDEX_PHP}?mod=queries&view=Queries">
                <img src="style/css3/images/Navigator/spreadsheet_circle.png" alt="queries" border="0"> {lang Queries}
            </a>

            <ul>
                {if $navigatorMenu == 'QUERIES'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {if (getClubUserInfo("MemberOnly") === false && getUserType(VIEW, "Member"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=queries&view=MemberSummary"
                             nav_label="Member summary"|lang
                             nav_visible=$visible}
                {/if}

                {if (getClubUserInfo("MemberOnly") === false && getUserType(VIEW, "Member"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=queries&view=Statistics"
                             nav_label="Statistics"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType(VIEW, "Member"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=queries&view=AddressLists"
                             nav_label="Addresslists"|lang
                             nav_visible=$visible}
                {/if}
            </ul>
            {/if}
        </li>
        {/if}

        <!-- Jobs -->
        {if (isLoggedIn())}
        <li>
            {if (getClubUserInfo("MemberOnly") === false && (getUserType(VIEW, "Payments") || getUserType(VIEW, "Fees")))}
            <a href="{$INDEX_PHP}?mod=jobs&view=Jobs">
                <img src="style/css3/images/Navigator/jobs_circle.png" alt="Jobs" border="0"> {lang Accounting}
            </a>

            <ul>
                {if $navigatorMenu == 'ACCOUNTING'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {if (getUserType(VIEW, "Payments"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Payments"
                             nav_label="Search for payments"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType(VIEW, "Fees"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Fees"
                             nav_label="Search for fees"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType(VIEW, "Payments"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=jobs&view=EndOfYear"
                             nav_label="End of Year Updates"|lang
                             nav_visible=$visible}
                {/if}
            </ul>
            {/if}
        </li>
        {/if}

        <!-- Conferences -->
        {if (getClubUserInfo("MemberOnly") === false && getUserType(VIEW, "Conferences"))}
        <li>
            <a href="{$INDEX_PHP}?mod=conferences&view=Conferences">
                <img src="style/css3/images/Navigator/conference_circle.png" alt="Conference" border="0"> {lang Conferences}
            </a>

            <ul>
                {if $navigatorMenu == 'CONFERENCES'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {if (getUserType(VIEW, "Conferences"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=conferences&view=List&InitView=1"
                             nav_label="List conferences"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType(VIEW, "Conferences"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=search&view=Conferences"
                             nav_label="Search for conferences"|lang
                             nav_visible=$visible}
                {/if}

                {if (getUserType(INSERT, "Conferences"))}
                    {include file="general/navigator_sub.inc.tpl"
                             nav_href="$INDEX_PHP?mod=conferences&view=Add"
                             nav_label="Add a new conference"|lang
                             nav_visible=$visible}
                {/if}
            </ul>
        </li>
        {/if}

        <!-- Settings -->
        {if (getClubUserInfo("MemberOnly") === false && isLoggedIn())}
        <li>
            <a href="{$INDEX_PHP}?mod=settings&view=Settings">
                <img src="style/css3/images/Navigator/settings_circle.png" alt="Jobs" border="0"> {lang Settings}
            </a>

            <ul>
                {if $navigatorMenu == 'SETTINGS'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=settings&view=Columns"
                         nav_label="Select Columns"|lang
                         nav_visible=$visible}
                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=settings&view=Personal"
                         nav_label="Personal settings"|lang
                         nav_visible=$visible}
            </ul>
        </li>
        {/if}

        <!-- Administration -->
        {if (getUserType(ADMINISTRATOR, 'Member'))}
        <li>
            <a href="{$INDEX_PHP}?mod=admin&view=Admin">
                <img src="style/css3/images/Navigator/tools_circle.png" alt="admin" border="0"> {lang Administration}
            </a>

            <ul>
                {if $navigatorMenu == 'ADMIN'}
                    {assign var="visible" value="subnav_display"}
                {else}
                    {assign var="visible" value="subnav_hidden"}
                {/if}

                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=admin&view=Users"
                         nav_label="Users"|lang
                         nav_visible=$visible}
                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=admin&view=Configuration"
                         nav_label="Configuration"|lang
                         nav_visible=$visible}
                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=admin&view=Database"
                         nav_label="Database"|lang
                         nav_visible=$visible}
                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=admin&view=Log"
                         nav_label="Log"|lang
                         nav_visible=$visible}
                {include file="general/navigator_sub.inc.tpl"
                         nav_href="$INDEX_PHP?mod=admin&view=Backup"
                         nav_label="Backup"|lang
                         nav_visible=$visible}
            </ul>
        </li>
        {/if}

        <!-- Logoff -->
        {if (isLoggedIn() == true)}
        <li>
            <a href="{$INDEX_PHP}?mod=main&view=Logoff">
                <img src="style/css3/images/Navigator/exit_circle.png" alt="back" border="0"> {lang Logoff}
            </a>
        </li>
        {/if}
    </ul>

    {if (!isLoggedIn())}
        {if ($demoMode)}
        <div style="border: 1px solid" class="navigator_label">
            Demo version:
            <p>To access Clubdata you need to provide a valid username/password pair.</p>
            <p>
                To use this demo use <br>
                <b>admin/admin</b> for administration access,<br>
                <b>AllUser/AllUser</b> for a typical user,<br>
                <b>147/mitglied</b> for a member view<br>
            </p>
        </div>
        {/if}

        <form action="{$INDEX_PHP}" method="post">
            <div class="row" style="margin: 0px 0px 0px 10px;">
                <div class="col-1-1 navigator_label">{lang Username}</div>
            </div>
            <div class="row" style="margin: 0px 0px 0px 10px;">
                <div class="col-1-1 navigator_label"><input class="text" name="Login" type="text" style="width: 80%;" maxlength="32"/></div>
            </div>

            <div class="row" style="margin: 5px 0px 0px 10px;">
                <div class="col-1-1 navigator_label">{lang Password}</div>
            </div>
            <div class="row" style="margin: 0px 0px 0px 10px;">
                <div class="col-1-1 navigator_label"><input class="text" name="PW_Login" type="password" style="width: 80%;" maxlength="32"/></div>
            </div>

            <div class="row" style="margin: 5px 0px 0px 10px;">
                <div class='col-1-1 BUTTON'><span><input onClick="submit();" class="button" type="submit" value="Login"/></span></div>
            </div>
        </form>
    {/if}
</div>
