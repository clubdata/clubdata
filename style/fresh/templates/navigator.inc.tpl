<ul class="navigator">
    {include file="general/navigator_sub.inc.tpl"
             nav_href="$INDEX_PHP?mod=main"
             nav_label="Home"|lang}

    {if !isLoggedIn() || $navigatorMenu == 'MAIN'}
        {include file="general/navigator_sub.inc.tpl"
                 nav_href="$INDEX_PHP?mod=main&view=Copyright"
                 nav_label="Copyright"|lang}
         {include file="general/navigator_sub.inc.tpl"
                  nav_href="$INDEX_PHP?mod=main&view=Impressum"
                  nav_label="Impressum"|lang}
    {/if}

    <!-- Member -->
    {if (isLoggedIn())}
        <li class="separator"></li>

        {include file="general/navigator_sub.inc.tpl"
                 nav_href="$INDEX_PHP?mod=members"
                 nav_label="Member"|lang}

        <li class="separator"></li>

        {include file="general/navigator_sub.inc.tpl"
                 nav_href="$INDEX_PHP?mod=main&view=Logoff"
                 nav_label="Logoff"|lang}
    {else}
        {if ($demoMode)}
            <li class="separator"></li>
            <li class="text">
                <b>Demo version:</b>

                <p>
                    To access Clubdata you need to provide valid username and password.
                </p>
                <p>
                    To use this demo use<br />
                    <dl>
                        <dt>admin/admin</dt>
                        <dd>for administration access</dd>

                        <dt>AllUser/AllUser</dt>
                        <dd>for a typical user</dd>

                        <dt>147/mitglied</dt>
                        <dd>for a member view</dd>
                    </dl>
                </p>
            </li>
        {/if}

        <li class="separator"></li>
    {/if}
</ul>

{if (!isLoggedIn())}
    <form action="{$INDEX_PHP}" method="post">
        <input type="text" class="borderless" name="Login" placeholder="{'Username'|lang}" />
        <input type="password" class="borderless" name="PW_Login" placeholder="{'Password'|lang}" />
        <button type="submit" class="button-full">Login</button>
    </form>
{/if}
