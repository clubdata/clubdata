<div class="vMain">
<div class="row">
  <div class="col_1_1">
    <div class="description">
    {$mainform}
    </div>
  </div>
</div>
{if is_object($listObj) }
<div class="row">
  <div class="col_1_1">
  {include file='list.inc.tpl'}
  </div>
</div>
{/if}
