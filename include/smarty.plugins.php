<?php
/**
 * Smarty Plugins
 *
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.imagePath.php
 * Type:     function
 * Name:     imagePath
 * Purpose:  returns the path of an image
 * -------------------------------------------------------------
 */
function imagePath($params, &$smarty)
{

  if (isset($params['mode'])) {
    $mode = $params['mode'] . "/";
  }

  $basedir = dirname($_SERVER['PHP_SELF']);

  $imagePath = LINKROOT . "/" . DEST_HTTP_DIR . $mode . $params['img'];

  debug('SMARTY', "IMAGE_PATH=$imagePath");

  if (!empty($params['assign'])) {
    $smarty->assign($params['assign'], $imagePath);
    return null;
  }
  else
  {
    return $imagePath;
  }

  return ;
}
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.translate.php
 * Type:     modifier
 * Name:     translate
 * Purpose:  translate the text passed as parameter to selected language
 * -------------------------------------------------------------
 */
function smarty_modifier_translate($string)
{
//     debug('SMARTY', "[translate]: STRING: $string");
    return(lang($string));
}
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.getDescription.php
 * Type:     function
 * Name:     getDescription
 * Purpose:  outputs the translated description of a database field
 * -------------------------------------------------------------
 */
function smarty_function_getDescription($params, &$smarty)
{
    if (empty($params['table'])) {
        $smarty->trigger_error("getDescription: missing 'table' parameter");
        return;
    }

    if (!isset($params['id'])) {
        $smarty->trigger_error("getDescription: missing 'id' parameter");
        return;
    }

    $bezName = (empty($params['bezeichnung'])) ? 'Description' : $params['bezeichnung'];

    $id = $params['id'];
    $table = $params['table'];
    debug('SMARTY', "[getDescription]: ID=$id, TABLE=$table");

    $anArr  = getMyRefTable($smarty->db, $id, $table);
    debug_r('SMARTY', $anArr, "[getDescription]: anArr");
    $desc   = getDescriptionTxt($anArr, $bezName);
    debug('SMARTY', "Description: $desc");

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $desc);
    }

    return $desc;
}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.displayTable.php
 * Type:     function
 * Name:     displayTable
 * Purpose:  displays a table
 * -------------------------------------------------------------
 */
//  ($tableObj, $optArr = array())
function smarty_function_displayTable($params, &$smarty)
{
    $table_attr = 'border="1"';
    $tr_attr = '';
    $td_attr = '';
    $cols = 3;
    $rows = 3;
    $trailpad = '&nbsp;';
    $vdir = 'down';
    $hdir = 'right';
    $inner = 'cols';

    if (!isset($params['loop'])) {
        $smarty->trigger_error("displayTable: missing 'loop' parameter");
        return;
    }

    foreach ($params as $_key=>$_value) {
        switch ($_key) {
            case 'loop':
                $$_key = (array)$_value;
                break;

            case 'cols':
            case 'rows':
                $$_key = (int)$_value;
                break;

            case 'table_attr':
            case 'trailpad':
            case 'hdir':
            case 'vdir':
            case 'inner':
                $$_key = (string)$_value;
                break;

            case 'tr_attr':
            case 'td_attr':
                $$_key = $_value;
                break;
        }
    }

    $loop_cols = count($loop);
    if (empty($params['rows'])) {
        /* no rows specified */
        $rows = ceil($loop_count/$cols);
    } elseif (empty($params['cols'])) {
        if (!empty($params['rows'])) {
            /* no cols specified, but rows */
            $cols = ceil($loop_count/$rows);
        }
    }


    echo "<TABLE BORDER='0' CLASS='listTable' WIDTH='100%'>";
    if ( $tableObj->maxNumCols > 1 )
    {
        echo "<COLGROUP><COL WIDTH='1%'><COL WIDTH='99%'></COLGROUP>\n";
    }
    if ( !empty($tableObj->title) )
    {
        echo "<TR CLASS='listTable' VALIGN='MIDDLE'>
                <TH CLASS='title' COLSPAN='" . ($tableObj->maxNumCols+1) . "'>
                $tableObj->title
                </TH>
              </TR>";
    }

    // SHOW TABLE BODY
    $rowCount = count($tableObj->tableBodyRows);
//     echo "ROWCOUNT: $rowCount<BR>";
    for ( $row=0 ; $row < $rowCount; $row++ )
    {
        $colCount = count($tableObj->tableBodyRows[$row]);
//         echo "COLCOUNT: $colCount<BR>";

        echo '<tr>';
        $rowArr = $tableObj->tableBodyRows[$row];

        $flg = list($actCol, $actVal) = each ($rowArr);
        $nextFlg = list($nextCol, $nextVal) = each($rowArr);
        for ( $i=0 ; $i < $actCol; $i++ )
        {
            echo "<TD CLASS='Description'>&nbsp;</TD>";
        }

        for ( ; $flg !== false; $nextFlg = list($nextCol, $nextVal) = each($rowArr))
        {
//          echo "NEXTCOL: $nextCol, ACTCOL: $actCol, COLCOUNT: $colCount<BR>";
            // Calculate COLSPAN as:
            // If not last column, then COLSPAN = nextColumn - actColumn,
            // If last column, then COLSPAN = maxNumCols - actColumn + 1
            // Else COLSPAN = 1
            if ( is_integer($nextCol) )
            {
                $colSpan = $nextCol - $actCol;
            }
            else if (!empty($tableObj->maxNumCols) && $actCol == $colCount - 1 )
            {
                $colSpan = $tableObj->maxNumCols - $actCol + 1;
            }
            else
            {
                $colSpan = 1;
            }

            // Get Attribute (= class name) for act field
            $class = empty($tableObj->tableBodyAttrs[$row][$actCol]) ?
                            '' :
                            $tableObj->tableBodyAttrs[$row][$actCol];

            // SPECIAL: Use 3 rows for class IMAGE !!
            $rowSpan =  $class == 'Image' ? 3 : 1;

            // Output HTML code to display field
            echo "<td ROWSPAN='$rowSpan' COLSPAN='$colSpan' CLASS='$class'>" .
                $actVal .
                '</td>';

            // Advance one column. Make next column the actual column
            $actCol = $nextCol;
            $actVal = $nextVal;
            $flg = $nextFlg;

        }

        echo "</TR>\n";
    }
    echo "</table>";

}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.displayList.php
 * Type:     function
 * Name:     displayTable
 * Purpose:  displays a table
 * -------------------------------------------------------------
 */
//  ($tableObj, $optArr = array())
function smarty_function_displayList($params, &$smarty)
{
    $table_attr = 'border="1"';
    $tr_attr = '';
    $td_attr = '';
    $cols = 3;
    $rows = 3;
    $trailpad = '&nbsp;';
    $vdir = 'down';
    $hdir = 'right';
    $inner = 'cols';

    if (!isset($params['loop'])) {
        $smarty->trigger_error("displayTable: missing 'loop' parameter");
        return;
    }

    foreach ($params as $_key=>$_value) {
        switch ($_key) {
            case 'loop':
                $$_key = (array)$_value;
                break;

            case 'cols':
            case 'rows':
                $$_key = (int)$_value;
                break;

            case 'table_attr':
            case 'trailpad':
            case 'hdir':
            case 'vdir':
            case 'inner':
                $$_key = (string)$_value;
                break;

            case 'tr_attr':
            case 'td_attr':
                $$_key = $_value;
                break;
        }
    }

    $loop_cols = count($loop);
    if (empty($params['rows'])) {
        /* no rows specified */
        $rows = ceil($loop_count/$cols);
    } elseif (empty($params['cols'])) {
        if (!empty($params['rows'])) {
            /* no cols specified, but rows */
            $cols = ceil($loop_count/$rows);
        }
    }
}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     compiler.lang.php
 * Type:     compiler function
 * Name:     lang
 * Version:  1.0
 * Date:     August 12, 2002
 * Purpose:  Transform the {lang} tags into intermediate tags
 *           to be read by postfilter.lang
 *
 *
 * Example:  {lang Select}
 *    Will replace the tag with the translated string for "Select",
 *    taken from a translation string definition file.
 *
 * Install:  Just drop into the plugin directory.
 *
 * Author:   Alejandro Sarco <ale@sarco.com.ar>
 * -------------------------------------------------------------
 */

function smarty_compiler_lang ($params, &$smarty) {
//  echo "LANG: $params<BR>";
 return "($"."lang.".$params.")";

}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     postfilter.lang.php
 * Type:     postfilter
 * Name:     lang
 * Version:  1.0
 * Date:     August 12, 2002
 * Purpose:  Parses the intermediate tags left by compiler.lang
 *           and replaces them with the translated strings,
 *           according to the $compile_id value (language code).
 *
 * Install:  Drop into the plugin directory, call
 *           $smarty->load_filter('post','lang');
 *            or
 *           $smarty->autoload_filters = array('post' => array('lang'));
 *           from application.
 * Author:   Alejandro Sarco <ale@sarco.com.ar>
 * -------------------------------------------------------------
 */
function smarty_postfilter_lang($tpl, &$smarty) {

 //Include your own respective translation strings here
//  include('path/to/your/languages/directory/'.$smarty->compile_id.'/.your_language_file.php');
 global $lang;

 debug('SMARTY', "[smarty_postfilter_lang]: tpl: $tpl");

 $offset = 0;
 while ( $start = strpos($tpl, '<?php ($lang.', $offset )) {
  $end = strpos($tpl, ') ?>', $start );
  $rplstr =  substr($tpl, $start + 13, $end - ($start + 13));
  debug('SMARTY', "[smarty_postfilter_lang]: RPLSTR: $rplstr, START: $start, END: $end");
  $newTxt = (isset($lang[$rplstr]) ? $lang[$rplstr] : $rplstr);
  $tpl = mb_substr_replace($tpl, $newTxt, $start, $end - ($start - 4));
  $offset = $start + strlen($newTxt) + 1;
 }

 debug_backtr('SMARTY', 99);
 return $tpl;
}


?>

