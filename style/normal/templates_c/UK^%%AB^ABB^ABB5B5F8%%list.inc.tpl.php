<?php /* Smarty version 2.6.10, created on 2008-11-21 23:25:38
         compiled from list.inc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'list.inc.tpl', 3, false),array('modifier', 'cat', 'list.inc.tpl', 8, false),array('function', 'html_image', 'list.inc.tpl', 8, false),array('function', 'counter', 'list.inc.tpl', 58, false),)), $this); ?>
<DIV class=listTable>
<INPUT type="hidden" name="cllist_id" VALUE='<?php echo $this->_tpl_vars['listObj']->id; ?>
'>
<?php if (((is_array($_tmp=@$this->_tpl_vars['listObj']->pagecount)) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)) > 1): ?>
<table>
<tr>
    <td>
        <a href=<?php echo $this->_tpl_vars['listObj']->firstLink; ?>
>
        <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/datapager/first.gif") : smarty_modifier_cat($_tmp, "images/datapager/first.gif")),'border' => 0), $this);?>

        </a>
    </td>
    <td>
        <a href=<?php echo $this->_tpl_vars['listObj']->previousLink; ?>
>
        <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/datapager/previous.gif") : smarty_modifier_cat($_tmp, "images/datapager/previous.gif")),'border' => 0), $this);?>

        </a>
    </td>
    <td>
    <?php echo ((is_array($_tmp=@$this->_tpl_vars['listObj']->pageNr)) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
 / <?php echo $this->_tpl_vars['listObj']->pagecount; ?>

    <td>
        <a href=<?php echo $this->_tpl_vars['listObj']->nextLink; ?>
>
        <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/datapager/next.gif") : smarty_modifier_cat($_tmp, "images/datapager/next.gif")),'border' => 0), $this);?>

        </a>
    </td>
    <td>
        <a href=<?php echo $this->_tpl_vars['listObj']->lastLink; ?>
>
        <?php echo smarty_function_html_image(array('file' => ((is_array($_tmp=$this->_tpl_vars['STYLE_DIR'])) ? $this->_run_mod_handler('cat', true, $_tmp, "images/datapager/last.gif") : smarty_modifier_cat($_tmp, "images/datapager/last.gif")),'border' => 0), $this);?>

        </a>
    </td>
</tr>
</table>
<?php endif; ?>
<table class="listTable">
<TR>
    <TH class="listTable" WIDTH='1%'>#</TH>
<?php if ($this->_tpl_vars['listObj']->getConfig('selectRowsFlg') == true): ?>
    <TH class="listTable" style='width: 1%;'>x</TH>
<?php endif;  if (( $this->_tpl_vars['listObj']->getConfig('changeFlg') == true )): ?>
    <TH CLASS="listTable">Del</TH>
    <TH CLASS="listTable">Edit</TH>
<?php endif; ?>

<?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['listObj']->listHeadRows) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['row']['show'] = true;
$this->_sections['row']['max'] = $this->_sections['row']['loop'];
$this->_sections['row']['step'] = 1;
$this->_sections['row']['start'] = $this->_sections['row']['step'] > 0 ? 0 : $this->_sections['row']['loop']-1;
if ($this->_sections['row']['show']) {
    $this->_sections['row']['total'] = $this->_sections['row']['loop'];
    if ($this->_sections['row']['total'] == 0)
        $this->_sections['row']['show'] = false;
} else
    $this->_sections['row']['total'] = 0;
if ($this->_sections['row']['show']):

            for ($this->_sections['row']['index'] = $this->_sections['row']['start'], $this->_sections['row']['iteration'] = 1;
                 $this->_sections['row']['iteration'] <= $this->_sections['row']['total'];
                 $this->_sections['row']['index'] += $this->_sections['row']['step'], $this->_sections['row']['iteration']++):
$this->_sections['row']['rownum'] = $this->_sections['row']['iteration'];
$this->_sections['row']['index_prev'] = $this->_sections['row']['index'] - $this->_sections['row']['step'];
$this->_sections['row']['index_next'] = $this->_sections['row']['index'] + $this->_sections['row']['step'];
$this->_sections['row']['first']      = ($this->_sections['row']['iteration'] == 1);
$this->_sections['row']['last']       = ($this->_sections['row']['iteration'] == $this->_sections['row']['total']);
?>
    <?php unset($this->_sections['col']);
$this->_sections['col']['name'] = 'col';
$this->_sections['col']['loop'] = is_array($_loop=$this->_tpl_vars['listObj']->listHeadRows[$this->_sections['row']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['col']['show'] = true;
$this->_sections['col']['max'] = $this->_sections['col']['loop'];
$this->_sections['col']['step'] = 1;
$this->_sections['col']['start'] = $this->_sections['col']['step'] > 0 ? 0 : $this->_sections['col']['loop']-1;
if ($this->_sections['col']['show']) {
    $this->_sections['col']['total'] = $this->_sections['col']['loop'];
    if ($this->_sections['col']['total'] == 0)
        $this->_sections['col']['show'] = false;
} else
    $this->_sections['col']['total'] = 0;
if ($this->_sections['col']['show']):

            for ($this->_sections['col']['index'] = $this->_sections['col']['start'], $this->_sections['col']['iteration'] = 1;
                 $this->_sections['col']['iteration'] <= $this->_sections['col']['total'];
                 $this->_sections['col']['index'] += $this->_sections['col']['step'], $this->_sections['col']['iteration']++):
$this->_sections['col']['rownum'] = $this->_sections['col']['iteration'];
$this->_sections['col']['index_prev'] = $this->_sections['col']['index'] - $this->_sections['col']['step'];
$this->_sections['col']['index_next'] = $this->_sections['col']['index'] + $this->_sections['col']['step'];
$this->_sections['col']['first']      = ($this->_sections['col']['iteration'] == 1);
$this->_sections['col']['last']       = ($this->_sections['col']['iteration'] == $this->_sections['col']['total']);
?>
        <th class="listTable" $sortID><?php echo $this->_tpl_vars['listObj']->listHeadRows[$this->_sections['row']['index']][$this->_sections['col']['index']]; ?>
</TH>
    <?php endfor; endif;  endfor; endif; ?>
</TR>
<?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['listObj']->listBodyRows) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['row']['show'] = true;
$this->_sections['row']['max'] = $this->_sections['row']['loop'];
$this->_sections['row']['step'] = 1;
$this->_sections['row']['start'] = $this->_sections['row']['step'] > 0 ? 0 : $this->_sections['row']['loop']-1;
if ($this->_sections['row']['show']) {
    $this->_sections['row']['total'] = $this->_sections['row']['loop'];
    if ($this->_sections['row']['total'] == 0)
        $this->_sections['row']['show'] = false;
} else
    $this->_sections['row']['total'] = 0;
if ($this->_sections['row']['show']):

            for ($this->_sections['row']['index'] = $this->_sections['row']['start'], $this->_sections['row']['iteration'] = 1;
                 $this->_sections['row']['iteration'] <= $this->_sections['row']['total'];
                 $this->_sections['row']['index'] += $this->_sections['row']['step'], $this->_sections['row']['iteration']++):
$this->_sections['row']['rownum'] = $this->_sections['row']['iteration'];
$this->_sections['row']['index_prev'] = $this->_sections['row']['index'] - $this->_sections['row']['step'];
$this->_sections['row']['index_next'] = $this->_sections['row']['index'] + $this->_sections['row']['step'];
$this->_sections['row']['first']      = ($this->_sections['row']['iteration'] == 1);
$this->_sections['row']['last']       = ($this->_sections['row']['iteration'] == $this->_sections['row']['total']);
?>

    <?php if (( $this->_sections['row']['index'] % 2 )): ?>
        <?php $this->assign('class', 'even'); ?>
    <?php else: ?>
        <?php $this->assign('class', 'odd'); ?>
    <?php endif; ?>

    <?php echo smarty_function_counter(array('name' => 'colCount','assign' => 'curCol','start' => 0), $this);?>

    <TR CLASS="<?php echo $this->_tpl_vars['class']; ?>
">
        <TD CLASS="listTable" style='text-align: right' >
            <?php echo ((is_array($_tmp=@$this->_tpl_vars['listObj']->listBodyRows[$this->_sections['row']['index']][$this->_tpl_vars['curCol']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

            <?php echo smarty_function_counter(array('name' => 'colCount'), $this);?>

        </TD>
        <?php if (( $this->_tpl_vars['listObj']->getConfig('selectRowsFlg') == true )): ?>
        <TD class="listTable" style='width: 1%;' >
            <?php echo ((is_array($_tmp=@$this->_tpl_vars['listObj']->listBodyRows[$this->_sections['row']['index']][$this->_tpl_vars['curCol']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

            <?php echo smarty_function_counter(array('name' => 'colCount'), $this);?>

        </TD>
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['listObj']->getConfig('changeFlg') == true )): ?>
            <TD class="listTable" style='width: 1%;' >
                 <?php echo ((is_array($_tmp=@$this->_tpl_vars['listObj']->listBodyRows[$this->_sections['row']['index']][$this->_tpl_vars['curCol']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

                 <?php echo smarty_function_counter(array('name' => 'colCount'), $this);?>

            </TD>
            <TD class="listTable" style='width: 1%;' >
                <?php echo ((is_array($_tmp=@$this->_tpl_vars['listObj']->listBodyRows[$this->_sections['row']['index']][$this->_tpl_vars['curCol']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

                <?php echo smarty_function_counter(array('name' => 'colCount'), $this);?>

            </TD>
        <?php endif; ?>
        <?php unset($this->_sections['col']);
$this->_sections['col']['name'] = 'col';
$this->_sections['col']['loop'] = is_array($_loop=$this->_tpl_vars['listObj']->listBodyRows[$this->_sections['row']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['col']['start'] = (int)$this->_tpl_vars['curCol'];
$this->_sections['col']['show'] = true;
$this->_sections['col']['max'] = $this->_sections['col']['loop'];
$this->_sections['col']['step'] = 1;
if ($this->_sections['col']['start'] < 0)
    $this->_sections['col']['start'] = max($this->_sections['col']['step'] > 0 ? 0 : -1, $this->_sections['col']['loop'] + $this->_sections['col']['start']);
else
    $this->_sections['col']['start'] = min($this->_sections['col']['start'], $this->_sections['col']['step'] > 0 ? $this->_sections['col']['loop'] : $this->_sections['col']['loop']-1);
if ($this->_sections['col']['show']) {
    $this->_sections['col']['total'] = min(ceil(($this->_sections['col']['step'] > 0 ? $this->_sections['col']['loop'] - $this->_sections['col']['start'] : $this->_sections['col']['start']+1)/abs($this->_sections['col']['step'])), $this->_sections['col']['max']);
    if ($this->_sections['col']['total'] == 0)
        $this->_sections['col']['show'] = false;
} else
    $this->_sections['col']['total'] = 0;
if ($this->_sections['col']['show']):

            for ($this->_sections['col']['index'] = $this->_sections['col']['start'], $this->_sections['col']['iteration'] = 1;
                 $this->_sections['col']['iteration'] <= $this->_sections['col']['total'];
                 $this->_sections['col']['index'] += $this->_sections['col']['step'], $this->_sections['col']['iteration']++):
$this->_sections['col']['rownum'] = $this->_sections['col']['iteration'];
$this->_sections['col']['index_prev'] = $this->_sections['col']['index'] - $this->_sections['col']['step'];
$this->_sections['col']['index_next'] = $this->_sections['col']['index'] + $this->_sections['col']['step'];
$this->_sections['col']['first']      = ($this->_sections['col']['iteration'] == 1);
$this->_sections['col']['last']       = ($this->_sections['col']['iteration'] == $this->_sections['col']['total']);
?>
            <TD class="listTable" >
                <?php echo ((is_array($_tmp=@$this->_tpl_vars['listObj']->listBodyRows[$this->_sections['row']['index']][$this->_sections['col']['index']])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>

            </TD>
        <?php endfor; endif; ?>
    </TR>
<?php endfor; else:  endif; ?>
</table>