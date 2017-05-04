{assign value=$APerr->getErrorLvlTxt() var=errLvl}
{assign value=$APerr->getMessages() var=errText}

{foreach from=$errText item=aktErr}
<div class="message message-{$errLvl}">
    {$aktErr}
</div>
{/foreach}
