<!--buttonbar-->
{if count($form->inputs) > 0 }
<div class="Buttonbar">
  <div class="row" >
    <div class="col-auto">
    <div class="row-nomargin">
	    {* Walk on all entries in formsgeneration input array *}
	    {foreach name=tabs key=schluessel item=button from=$form->inputs}
	        {* If SubForm equals to buttonbar, process it here *}
	        {if $button.SubForm == 'buttonbar'}
	            {* If type is NOT hidden generate a data field, else just output it *}
              {if ($button.ID == 'image_prev' ) }
                  <div class="col-auto arrowLeft" onclick="{$button.EVENTS.ONCLICK}"></div>
              {elseif ($button.ID == 'image_next' ) }
                  <div class="col-auto arrowRight" onclick="{$button.EVENTS.ONCLICK}"></div>
              {elseif ($button.ID == 'image_last' ) }
                  <div class="col-auto arrowRightLast" onclick="{$button.EVENTS.ONCLICK}"></div>
              {elseif ($button.ID == 'image_first' ) }
                  <div class="col-auto arrowLeftFirst" onclick="{$button.EVENTS.ONCLICK}"></div>
	            {elseif ($button.TYPE == "button" || $button.TYPE == "submit") }
	                <div class='col-auto'><div class="BUTTON">{input name="$schluessel"}</div></div>
	            {elseif ($button.TYPE != "hidden") }
	                <div id="{$button.ID}-DIV" class="col-auto">{input name="$schluessel"}</div>
	            {/if}
	        {/if}
	    {/foreach}
	    </div>
    </div>
    <div class="col-auto" style="margin-right: 10px;">
      <div class="row" style="justify-content: flex-end;">
	    {* Walk on all entries in formsgeneration input array *}
	    {foreach name=tabs key=schluessel item=button from=$form->inputs}
	        {* If SubForm equals to buttonbar_right, process it here *}
	        {if $button.SubForm == 'buttonbar_right'}
	            {* If type is NOT hidden generate a data field, else ignore it *}
	            { if ($button.TYPE != "hidden") }
	                <div>{input name="$schluessel"}</div>
	            {/if}
	        {/if}
	    {/foreach}
	    </div>
    </div>
  </div>
</div>
{/if}
<!--buttonbarende-->
