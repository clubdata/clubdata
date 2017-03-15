{*
Template: Search

Variables passed:   $form   input form definition
                    $heads  array of possible headlines

Description:
                Shows the search formular, by sections.
                The format of the names of the input are [Sectionname%]key[_select]
                Only keys without _select are treated. The corresponding _select
                input is used for each key automatically
                The sectionname is separated by the regexp /(([^%]*)%)?.*$/.
                If it changes, a headline is inserted
*}
{assign value='' var=title_old}
<div class="XXtable">
    {foreach key=schluessel item=edit from=$form->inputs}
        {if (substr($schluessel,0,2) != '__' &&
             substr($schluessel,0,2) != 'p_' &&
             substr($schluessel,-7) != '_select' &&
             substr($schluessel,-9) != '_rangeEnd') &&
             strncmp($edit.SubForm,'buttonbar',9)} {* Ignore buttonbar *}

             { if ($edit.TYPE != "hidden") }
                {assign value=$schluessel|regex_replace:"/(([^%]*)%)?.*$/":"\\2" var=title}
                {if ( $title != $title_old) }
	                <div class="row">
	                  <div class="col-1-1 SectionTitle">
	                    {$heads.$title}{*$heads.$title|default:$title*}
	                  </div>
	                </div>
                   {assign value=$title var=title_old}
                {/if}
                {assign value=$schluessel|cat:'_select' var=schluesselselect}
                {assign value=$schluessel|cat:'_rangeEnd' var=schluesselend}
                <div class="row">
                  <div class="col-equal Description">
                    {label for="$schluessel"}:
                  </div>
                  <div class="col-equal Description">
                    {input name="$schluesselselect"}
                  </div>
                  <div class="col-max DataSearch">
                  {if !empty($edit.MULTIPLE) }
                  <div class="row">
                    <div class="col-auto">
                      {input name="$schluessel"}
                    </div>
                    <div class="col-auto">
                      <div class="row-nomargin">
                        <input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(1, '{$schluessel|cat:"[]"}');SetComparision('INSelection', '{$schluesselselect}');" value='Select ALL'>
                      </div>
                      <div class="row-nomargin">
                        <input class="searchTable" ID="searchTableSelectButton" style="width: 3cm;" type=button onClick="SetSelected(0, '{$schluessel|cat:"[]"}');SetComparision('INSelection', '{$schluesselselect}');" value='Deselect ALL'>
                      </div>
                    </div>
                  </div>
                  {else}
                  {input name="$schluessel"}<span style='display: none;' id='{$schluessel}_rangeEnd'>&nbsp;{'and'|lang}&nbsp;{input name="$schluesselend"}</span>
                  {/if}
                  </div>
                </div>
            {/if}
        {/if}
    {/foreach}
</div>


