<?php /* Smarty version 2.6.10, created on 2015-06-19 22:27:39
         compiled from navigator.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'navigator.inc.tpl', 16, false),)), $this); ?>
<div class="navigator" style="width: 200px;">
  <ul>
    <!-- Main -->
	  <li>
		  <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=main">
		      <img src="style/css3/images/Navigator/home3_small.png" alt="back" border="0">
		      Home
		  </a>
		  <ul>
		  <?php if (! isLoggedIn ( ) || $this->_tpl_vars['navigatorMenu'] == 'MAIN'): ?>
		    <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
      <?php endif; ?>		  
      
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=main&view=Copyright",'nav_label' => ((is_array($_tmp='Copyright')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=main&view=Impressum",'nav_label' => ((is_array($_tmp='Impressum')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		  </ul>
	  </li>
		<!-- Member -->
		<?php if (( isLoggedIn ( ) )): ?>
    <li>
			        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=members">
			            <img src="style/css3/images/Navigator/people_circle.png" alt="members" border="0">
			            Member
			        </a>
	    <ul>
			<?php if ($this->_tpl_vars['navigatorMenu'] == 'MEMBERS'): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
      <?php endif; ?>     

		  <?php $this->assign('securequestion', ((is_array($_tmp='Do you want to add a new member')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp))); ?>
		  <?php if (( ! isMember ( ) && getUserType ( INSERT , 'Member' ) )): ?>
		    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_javascript' => "onclick=\"return newMember('".($this->_tpl_vars['securequestion'])."');\"",'nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=members&Action=INSERT",'nav_label' => ((is_array($_tmp='New Member')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		  <?php endif; ?>
		  <?php if (( ! isMember ( ) )): ?>
		    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Member",'nav_label' => ((is_array($_tmp='Search for members')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		  <?php endif; ?>
    </ul>
    </li>
		<?php endif; ?>
    <!-- Search / Communication -->
    <?php if (( isLoggedIn ( ) )): ?>
    <li>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=search&view=Search">
        <img src="style/css3/images/Navigator/eyes_circle.png" alt="search" border="0">
        Communication
    </a>
    <ul>
		<?php if (( $this->_tpl_vars['navigatorMenu'] == 'COMMUNICATION' )): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
    <?php endif; ?>
	  <?php if (( getUserType ( 'Create' , 'Email' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Email",'nav_label' => ((is_array($_tmp='Send email')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( 'Create' , 'Infoletter' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Infoletter",'nav_label' => ((is_array($_tmp='Send Infoletter')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Invoice",'nav_label' => ((is_array($_tmp='Send Invoice')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
    </ul>
    </li>    
  <?php endif; ?>
    <!-- Queries -->
    <?php if (( isLoggedIn ( ) )): ?>
    <li>
    <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=queries&view=Queries">
        <img src="style/css3/images/Navigator/spreadsheet_circle.png" alt="queries" border="0">
        Queries
    </a>
    <ul>
		<?php if ($this->_tpl_vars['navigatorMenu'] == 'QUERIES'): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
    <?php endif; ?>
	  <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && getUserType ( VIEW , 'Member' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=MemberSummary",'nav_label' => ((is_array($_tmp='Member summary')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && getUserType ( VIEW , 'Member' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=Statistics",'nav_label' => ((is_array($_tmp='Statistics')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Member' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=queries&view=AddressLists",'nav_label' => ((is_array($_tmp='Addresslists')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
    <?php endif; ?>
    </ul>
    </li>
    <?php endif; ?>
    <!-- Jobs -->
    <?php if (( isLoggedIn ( ) )): ?>
    <li>
    <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && ( getUserType ( VIEW , 'Payments' ) || getUserType ( VIEW , 'Fees' ) ) )): ?>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=jobs&view=Jobs">
        <img src="style/css3/images/Navigator/jobs_circle.png" alt="Jobs" border="0">
        Accounting
    </a>
    <ul>
		<?php if ($this->_tpl_vars['navigatorMenu'] == 'ACCOUNTING'): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
    <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Payments",'nav_label' => ((is_array($_tmp='Search for payments')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Fees' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Fees",'nav_label' => ((is_array($_tmp='Search for fees')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Payments' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=jobs&view=EndOfYear",'nav_label' => ((is_array($_tmp='End of Year Updates')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
    <?php endif; ?>
  </ul>
  </li>
  <?php endif; ?>
  <!-- Conferences -->
  <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && getUserType ( VIEW , 'Conferences' ) )): ?>
  <li>
  <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=conferences&view=Conferences">
        <img src="style/css3/images/Navigator/conference_circle.png" alt="Conference" border="0">
        Conferences
    </a>
    <ul>
    <?php if ($this->_tpl_vars['navigatorMenu'] == 'CONFERENCES'): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
    <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Conferences' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=conferences&view=List&InitView=1",'nav_label' => ((is_array($_tmp='List conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( VIEW , 'Conferences' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=search&view=Conferences",'nav_label' => ((is_array($_tmp='Search for conferences')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	  <?php if (( getUserType ( INSERT , 'Conferences' ) )): ?>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=conferences&view=Add",'nav_label' => ((is_array($_tmp='Add a new conference')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
  </ul>
  </li>
    <?php endif; ?>
  <!-- Settings -->
  <?php if (( getClubUserInfo ( 'MemberOnly' ) === false && isLoggedIn ( ) )): ?>
  <li>
    <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=settings&view=Settings">
        <img src="style/css3/images/Navigator/settings_circle.png" alt="Jobs" border="0">
        Settings
    </a>
    <ul>
    <?php if ($this->_tpl_vars['navigatorMenu'] == 'SETTINGS'): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
    <?php endif; ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=settings&view=Columns",'nav_label' => ((is_array($_tmp='Select Columns')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=settings&view=Personal",'nav_label' => ((is_array($_tmp='Personal settings')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
   </ul>
   </li>
   <?php endif; ?>
    <!-- Administration -->
  <?php if (( getUserType ( ADMINISTRATOR , 'Member' ) )): ?>
   <li>
     <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=admin&view=Admin">
         <img src="style/css3/images/Navigator/tools_circle.png" alt="admin" border="0">
         Administration
     </a>
     <ul>
    <?php if ($this->_tpl_vars['navigatorMenu'] == 'ADMIN'): ?>
        <?php $this->assign('visible', 'subnav_display'); ?>
      <?php else: ?>
        <?php $this->assign('visible', 'subnav_hidden'); ?>
    <?php endif; ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Users",'nav_label' => ((is_array($_tmp='Users')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Configuration",'nav_label' => ((is_array($_tmp='Configuration')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Database",'nav_label' => ((is_array($_tmp='Database')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Log",'nav_label' => ((is_array($_tmp='Log')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "general/navigator_sub.inc.tpl", 'smarty_include_vars' => array('nav_href' => ($this->_tpl_vars['INDEX_PHP'])."?mod=admin&view=Backup",'nav_label' => ((is_array($_tmp='Backup')) ? $this->_run_mod_handler('lang', true, $_tmp) : lang($_tmp)),'nav_visible' => $this->_tpl_vars['visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </ul>
  </li>
  <?php endif; ?>
  <!-- Logoff -->
  <?php if (( isLoggedIn ( ) == true )): ?>
  <li>
        <a href="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
?mod=main&view=Logoff">
            <img src="style/css3/images/Navigator/exit_circle.png" alt="back" border="0">
            Logoff
        </a>
  </li>
  <?php else: ?>
  <?php if (( $this->_tpl_vars['demoMode'] )): ?>
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
  <?php endif; ?>
  </ul>

<form action="<?php echo $this->_tpl_vars['INDEX_PHP']; ?>
" method="post">
<div class="row" style="margin: 0px 0px 0px 10px;">
<div class="col-1-1 navigator_label">Username</div>
</div>
<div class="row" style="margin: 0px 0px 0px 10px;">
<div class="col-1-1 navigator_label"><input class="text" name="Login" type="text" style="width: 80%;" maxlength="32"/></div>
</div>

<div class="row" style="margin: 5px 0px 0px 10px;">
<div class="col-1-1 navigator_label">Password</div>
</div>
<div class="row" style="margin: 0px 0px 0px 10px;">
<div class="col-1-1 navigator_label"><input class="text" name="PW_Login" type="password" style="width: 80%;" maxlength="32"/></div>
</div>
<div class="row" style="margin: 5px 0px 0px 10px;">
<div class='col-1-1 BUTTON'><span><input onClick="submit();" class="button" type="submit" value="Login"/></span></div>
</div>
<?php endif; ?>
  
</div>