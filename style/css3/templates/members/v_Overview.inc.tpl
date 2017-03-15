<div class="vMain">
	<div class="content equalheight" >
  {foreach from=$Addresses key=addressName item=address}
    {capture name=captureField}
			<div class="row-smallmargin">
			  <div class="col-1-2 Daten Address">
			  {*foreach key=schluessel item=wert from=$address.address}
			    {$schluessel}: {$wert}<br>
			  {/foreach}
        {foreach key=schluessel item=wert from=$address.fieldArr}
          {$schluessel}: {$wert} - {$address.address.$wert}<br>

          {assign var=addressFieldArr value="_"|explode:$wert}
          {foreach key=schluessel item=wert from=$addressFieldArr}
            FIELDARR: {$schluessel}: {$wert} <br>
          {/foreach}
        {/foreach*}

        {assign var=showNameFlg value=1}          
        {assign var=showLocationFlg value=1}          
        {foreach key=schluessel item=wert from=$address.fieldArr}
          {* Split variable name to get extensions like ref in Country_ref or Salutation_ref *}
          {assign var=addressFieldArr value="_"|explode:$wert}
          {if count($addressFieldArr) > 1 && $addressFieldArr[1] == 'ref' } 
          <div>
            {getDescription id=$address.address.$wert|default:'' table=$addressFieldArr[0]}
          </div>
          {elseif $wert == "Title" || $wert == "Firstname" || $wert == "Lastname" }
          <div>
            {if $showNameFlg == 1 }
		          {if !empty($address.address.Title) }{$address.address.Title|default:''}&nbsp;{/if}
		          {if !empty($address.address.Lastname) }{$address.address.Lastname|default:''},&nbsp;{/if}
		          {if !empty($address.address.Firstname) }{$address.address.Firstname|default:''}{/if}
	            {assign var=showNameFlg value=0}
	          {/if}          
          </div>
          {elseif $wert == "ZipCode" || $wert == "Town" }
          <div>
            {if $showLocationFlg == 1 }
              {if !empty($address.address.ZipCode) }{$address.address.ZipCode|default:''}&nbsp;{/if}
              {if !empty($address.address.Town) }{$address.address.Town|default:''}<br>{/if}
              {assign var=showLocationFlg value=0}
            {/if}          
          </div>
          {elseif $wert == "Address" || $wert == "FirmName_ml" || $wert == "FirmDepartment"  }
          <div>
            {$address.address.$wert|default:''}
          </div>
          {else}
            <div class="row-smallmargin">
	            <div class="col-1-3">{$addressFieldArr[0]|lang}:</div>
	            <div class="col-2-3">{$address.address.$wert|default:''}</div>
            </div>
          {/if}
        {/foreach}
			  
			  </div>
			</div>
		{/capture}
  
    {assign var=addressId value=$address.id}
		{include
		 file="general/box.inc.tpl" 
		 boxlink="javascript:doAction('members','Addresses_$addressId')" 
		 boxtitle=$addressName|lang
     boxid="Members_$addressId"		 
		 boxhelp=$smarty.capture.captureField 
		}
		
  {/foreach}

		<!-- Memberinformation -->
		{capture name="captureField"}
		  <div class="Daten">
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Membertype}:</div>
		        <div class="col-2-3">{getDescription
		              id=$Memberinfo.Membertype_ref|default:'' table='Membertype'}</div>
		      </div>
		      {if !empty($Memberinfo.MainMemberID)}
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Full member}:</div>
		        <div class="col-2-3"><a
		              href='{$INDEX_PHP}?mod=members&view=Overview&MemberID={$Memberinfo.MainMemberID.Adr_MemberID}'>
		                {$Memberinfo.MainMemberID.Lastname},
		                {$Memberinfo.MainMemberID.Firstname} </a></div>
		      </div>
		      {/if}
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Entrance}:</div>
		        <div class="col-2-3">{$Memberinfo.Entrydate|regex_replace:"/0000-00-00/":""|date_format:"%d.%m.%Y"}</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang  Information sharing}:</div>
		        <div class="col-2-3">{getDescription
		              id=$Memberinfo.InfoGiveOut_ref|default:'' table='InfoGiveOut'}</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Information in WWW}:</div>
		        <div class="col-2-3">{getDescription
		              id=$Memberinfo.InfoWWW_ref|default:'' table='InfoWWW'}</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Preferred language}:</div>
		        <div class="col-2-3">{getDescription
		              id=$Memberinfo.Language_ref|default:'' table='Language'}</div>
		      </div>
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Birthdate}:</div>
		        <div class="col-2-3">{$Memberinfo.Birthdate|regex_replace:"/0000-00-00/":""|date_format:"%d.%m.%Y"}</div>
		      </div>
		      {if !empty($Memberinfo.Remarks)}
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Remarks}:</div>
		        <div class="col-2-3">{$Memberinfo.Remarks|default:''}</div>
		      </div>
		      {/if}
		      {if !empty($Memberinfo.Selection)}
		      <div class="row-smallmargin">
		        <div class="col-1-3">{lang Selection}:</div>
		        <div class="col-2-3">{$Memberinfo.Selection|default:''}</div>
		      </div>
		      {/if}
		  </div>
		{/capture}
		
		{include
		 file="general/box.inc.tpl" 
     boxlink="javascript:doAction('members','Memberinfo')" 
		 boxtitle="Memberinfo"|lang
     boxid="MembersMemberinfo"     
		 boxhelp=$smarty.capture.captureField 
		}
   
		{capture name=captureField}
			 <div class="Daten">
			  {foreach name=assocLoop item=assoc from=$Memberinfo.associatedMembers}
			 <div class="row-smallmargin">
			    <div class="col-1-3">{if $smarty.foreach.assocLoop.first}
			             {lang Associated members}: {else} &nbsp; {/if}</div>
			    <div class="col-2-3"><a
			             href='{$INDEX_PHP}?mod=members&view=Overview&MemberID={$assoc.MemberID}'>
			               {$assoc.Lastname}, {$assoc.Firstname} </a></div>
			 </div>
			{/foreach}
			{assign var=attribShown value=false}
			{foreach name=assocLoop item=assoc from=$Attributes}
			{if !empty($assoc)}
			 <div class="row-smallmargin">
			    <div class="col-1-3">{if $attribShown == false}
			             {lang Attributes}: {assign var=attribShown value=true} {else} &nbsp; {/if}</div>
			    <div class="col-2-3">{getDescription id=$assoc
			             table='Attributes'}</div>
			 </div>
			{/if}
			{/foreach}
			</div>
		{/capture}

		{include
		 file="general/box.inc.tpl" 
     boxlink="javascript:doAction('members','Memberinfo')" 
		 boxtitle="Additional Memberinfo"|lang
     boxid="MembersAdditionalMemberinfo"     
		 boxhelp=$smarty.capture.captureField 
		}
  </div>
</div>
