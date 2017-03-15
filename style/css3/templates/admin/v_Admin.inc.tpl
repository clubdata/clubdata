<div class="vMain">
	<div class="content equalheight">
	    {include file="general/box.inc.tpl" 
	             boxlink="$INDEX_PHP?mod=admin&view=Users"
	             boxtitle="Users"|lang 
	             boxid="AdminUsers"
	             boxhelp="Administer Users and their rights"|lang}
	    {include file="general/box.inc.tpl" 
	             boxlink="$INDEX_PHP?mod=admin&view=Configuration"
	             boxtitle="Configuration"|lang 
               boxid="AdminConfiguration"
 	             boxhelp="Change general configuration settings"|lang}
	    {include file="general/box.inc.tpl" 
	             boxlink="$INDEX_PHP?mod=admin&view=Database"
	             boxtitle="Database"|lang 
               boxid="AdminDatabase"
	             boxhelp="Configure misceleanous database tables, line salutations, member types, etc."|lang}
	    {include file="general/box.inc.tpl" 
	             boxlink="$INDEX_PHP?mod=admin&view=Log"
	             boxtitle="Log"|lang 
               boxid="AdminLog"
	             boxhelp="Show log entries"|lang}
	    {include file="general/box.inc.tpl" 
	             boxlink="$INDEX_PHP?mod=admin&view=Backup"
	             boxtitle="Backup"|lang 
               boxid="AdminBackup"
	             boxhelp="Backup clubdata database"|lang}
	</div>
</div>
