<?php /* Smarty version 2.6.10, created on 2011-01-05 12:09:31
         compiled from navigator.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'navigator.inc.tpl', 18, false),)), $this); ?>
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
        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=main">
            <img src="style/newstyle/images/home3_small.png" alt="back" border="0">
            Home
        </a>
    </td>
</tr>
<?php if (! isLoggedIn ( ) || $this->_tpl_vars['navigatorMenu'] == 'MAIN'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=main&view=Copyright",'nav_label' => ((is_array($_tmp='Copyright')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=main&view=Impressum",'nav_label' => ((is_array($_tmp='Impressum')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<!-- Member -->
<?php if (( isLoggedIn ( ) )): ?>
<tr>
    <td></td>
    <td>
        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=members">
            <img src="style/newstyle/images/people_circle.png" alt="members" border="0">
            Member
        </a>
    </td>
    <td></td>
</tr>
<?php if ($this->_tpl_vars['navigatorMenu'] == 'MEMBERS'): ?>
  <?php $this->assign('securequestion', ((is_array($_tmp='Do you want to add a new member')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp))); ?>
  <?php if (( ! isMember ( ) && getUserType ( INSERT , 'Member' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_javascript' => "onclick=\"return newMember('".($this->_tpl_vars['securequestion'])."');\"",'nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=members&Action=INSERT",'nav_label' => ((is_array($_tmp='New Member')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( ! isMember ( ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Member",'nav_label' => ((is_array($_tmp='Search for members')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  endif;  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<!-- Search / Communication -->
<?php if (( getClubUserInfo ( 'MemberOnly' ) === false && ( getUserType ( 'Create' , 'Email' ) || getUserType ( 'Create' , 'Infoletter' ) || getUserType ( VIEW , 'Payments' ) ) )): ?>
<tr>
    <td></td>
<td>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=search&view=Search">
        <img src="style/newstyle/images/eyes_circle.png" alt="search" border="0">
        Communication
    </a>
</td>
    <td></td>
</tr>
<?php if (( $this->_tpl_vars['navigatorMenu'] == 'COMMUNICATION' )): ?>
  <?php if (( getUserType ( 'Create' , 'Email' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Email",'nav_label' => ((is_array($_tmp='Send email')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( 'Create' , 'Infoletter' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Infoletter",'nav_label' => ((is_array($_tmp='Send Infoletter')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Invoice",'nav_label' => ((is_array($_tmp='Send Invoice')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<?php endif; ?>
<!-- Queries -->
<?php if (( getUserType ( VIEW , 'Member' ) )): ?>
<tr>
    <td></td>
<td>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=queries&view=Queries">
        <img src="style/newstyle/images/spreadsheet_circle.png" alt="queries" border="0">
        Queries
    </a>
</td>
    <td></td>
</tr>
<?php if ($this->_tpl_vars['navigatorMenu'] == 'QUERIES'): ?>
  <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && getUserType ( VIEW , 'Member' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=MemberSummary",'nav_label' => ((is_array($_tmp='Member summary')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && getUserType ( VIEW , 'Member' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=Statistics",'nav_label' => ((is_array($_tmp='Statistics')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=AddressLists",'nav_label' => ((is_array($_tmp='Addresslists')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<?php endif; ?>
<!-- Jobs -->
<?php if (( getClubUserInfo ( 'MemberOnly' ) === false && ( getUserType ( VIEW , 'Payments' ) || getUserType ( VIEW , 'Fees' ) ) )): ?>
<tr>
    <td></td>
<td>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=jobs&view=Jobs">
        <img src="style/newstyle/images/jobs_circle.png" alt="Jobs" border="0">
        Accounting
    </a>
</td>
    <td></td>
</tr>
<?php if ($this->_tpl_vars['navigatorMenu'] == 'ACCOUNTING'): ?>
  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Payments",'nav_label' => ((is_array($_tmp='Search for payments')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Fees' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Fees",'nav_label' => ((is_array($_tmp='Search for fees')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=jobs&view=EndOfYear",'nav_label' => ((is_array($_tmp='End of Year Updates')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<?php endif; ?>
<!-- Conferences -->
<?php if (( getClubUserInfo ( 'MemberOnly' ) === false && getUserType ( VIEW , 'Conferences' ) )): ?>
<tr>
    <td></td>
<td>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=conferences&view=Conferences">
        <img src="style/newstyle/images/conference_circle.png" alt="Conference" border="0">
        Conferences
    </a>
</td>
    <td></td>
</tr>
<?php if ($this->_tpl_vars['navigatorMenu'] == 'CONFERENCES'): ?>
  <?php if (( getUserType ( VIEW , 'Conferences' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=conferences&view=List&InitView=1",'nav_label' => ((is_array($_tmp='List conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( VIEW , 'Conferences' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Conferences",'nav_label' => ((is_array($_tmp='Search for conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if (( getUserType ( INSERT , 'Conferences' ) )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=conferences&view=Add",'nav_label' => ((is_array($_tmp='Add a new conference')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<?php endif; ?>
<!-- Settings -->
<?php if (( getClubUserInfo ( 'MemberOnly' ) === false && isLoggedIn ( ) )): ?>
<tr>
    <td></td>
<td>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=settings&view=Settings">
        <img src="style/newstyle/images/settings_circle.png" alt="Jobs" border="0">
        Settings
    </a>
</td>
    <td></td>
</tr>
<?php if ($this->_tpl_vars['navigatorMenu'] == 'SETTINGS'): ?>
<tr>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=settings&view=Columns",'nav_label' => ((is_array($_tmp='Select Columns')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=settings&view=Personal",'nav_label' => ((is_array($_tmp='Personal settings')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</tr>
<tr>
<td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<?php endif;  endif; ?>
<tr>
    <td colspan="3"><img border="0" height="10" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<!-- Administration -->
<?php if (( getUserType ( ADMINISTRATOR , 'Member' ) )): ?>
    <tr>
    <td></td>
    <td>
        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=admin&view=Admin">
            <img src="style/newstyle/images/tools_circle.png" alt="admin" border="0">
            Administration
        </a>
    </td>
    <td></td>
    </tr>
<?php if ($this->_tpl_vars['navigatorMenu'] == 'ADMIN'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Users",'nav_label' => ((is_array($_tmp='Users')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Configuration",'nav_label' => ((is_array($_tmp='Configuration')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Database",'nav_label' => ((is_array($_tmp='Database')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Log",'nav_label' => ((is_array($_tmp='Log')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Backup",'nav_label' => ((is_array($_tmp='Backup')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>
<!-- Logoff -->
<?php if (( isLoggedIn ( ) == true )): ?>
<tr>
    <td colspan="3"><img border="0" height="20" src="style/newstyle/images/pixel_transparent.png" width="5"></td>
</tr>
<tr>
    <td></td>
    <td>
        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=main&view=Logoff">
            <img src="style/newstyle/images/exit_circle.png" alt="back" border="0">
            Logoff
        </a>
    </td>
</tr>
<?php else:  if (( $this->_tpl_vars['demoMode'] )): ?>
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
<?php endif; ?>
<tr>
<td></td>
<td colspan="2">
<form action="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
" method="post">
<table>
<tr>
<td colspan="1" class="navigator_label">Nom d\'utilisateur</td>
</tr>
<tr>
<td colspan="1"><input class="text" name="Login" type="text" style="width: 80%;" maxlength="32"/></td>
</tr>
<tr>
<td colspan="1" class="navigator_label">Mot de passe</td>
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
<?php endif; ?>
<tr>
    <TD><img src="style/newstyle/images/corner_ll.png" width="10" border="0"></TD>
    <td></td>
    <td><img style="float: right;" src="style/newstyle/images/corner_lr.png" width="10" border="0"></td>
</tr>


</table>