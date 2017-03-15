{assign value=$APerr->getErrorLvlTxt() var=errLvl}
{assign value=$APerr->getMessages() var=errText}
<div class="vMain">
   <div class="{$errLvl} messages">
        <div class="header">
        {$errLvl|translate}
        </div>
        <div class="message">
        {foreach from=$errText item=aktErr}
        {$aktErr}<BR>
        {/foreach}
        </div>
   </div>
</div>
