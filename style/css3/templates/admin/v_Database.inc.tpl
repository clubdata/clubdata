<div class="vMain">
<div class="content equalheight">
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=admin&view=Salutation"
               boxtitle="Salutation"|lang
               boxhelp="Insert/Edit/Delete salutations"|lang}
  
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=admin&view=Paytype"
               boxtitle="Paytype"|lang
               boxid="DatabasePaytype"
               boxhelp="Insert/Edit/Delete types of payments, like membership fees or conference fees"|lang}
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=admin&view=Addresstype"
             boxtitle="Addresstype"|lang
               boxid="DatabaseAddresstype"
             boxhelp="Insert/Edit/Delete types of addresses, like privat or firm adresses"|lang}
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=admin&view=Mailingtypes"
             boxtitle="Mailingtypes"|lang
               boxid="DatabaseMailingtypes"
             boxhelp="Insert/Edit/Delete types of mailings, like invitation, invoice, etc."|lang}
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=admin&view=Membertype"
               boxtitle="Membertype"|lang
               boxid="DatabaseMembertype"
               boxhelp="Insert/Edit/Delete types of memberships"|lang}
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=admin&view=Country"
             boxtitle="Country"|lang
               boxid="DatabaseCountry"
             boxhelp="Insert/Edit/Delete country codes"|lang}
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=admin&view=Language"
             boxtitle="Language"|lang
               boxid="DatabaseLanguage"
             boxhelp="Insert/Edit/Delete languages, which can be selected by a user.<BR>If you add a new language here, you have also to update other tables and create a new file in the subdirectory Language to be able to see Clubdata translated to this language"|lang}
               
      {include file="general/box.inc.tpl"
               boxlink="$INDEX_PHP?mod=admin&view=Paymode"
               boxtitle="Paymode"|lang
               boxid="DatabasePaymode"
               boxhelp="Insert/Edit/Delete modes of payments"|lang}
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=admin&view=Attributes"
             boxtitle="Attributes"|lang
               boxid="DatabaseAttributes"
             boxhelp="Insert/Edit/Delete attributes which may be assigned to members"|lang}
    {include file="general/box.inc.tpl"
             boxlink="$INDEX_PHP?mod=admin&view=Help"
             boxtitle="Help"|lang
               boxid="DatabaseHelp"
             boxhelp="Edit help texts and translate it to new languages"|lang}
</div>
</div>
