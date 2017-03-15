<?php
/**
 * Listing class
 *
 * The Listing class contains methods to setup and manipulate listings of datas.
 * This class is rarely used directly, but often overload e.g. {@link DbList}
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

global $db;

require_once("include/function.php");
require_once('include/datapager.php');

$dispFile = "style/" . getConfigEntry($db, "Style") . "/display_list.php";
// require_once($dispFile);

/**
 * Class Listing
 *
 * @package Clubdata
 */
class Listing extends datapager {

    var $id;        // ID
    var $withoutHeader = true;
    var $changeFlg = false;
    var $selectRows = true;
    var $selectedRows;
    var $sumCols = array("Amount");
    var $linkParams = "command=execute";
    var $pageNr = 0;

    /**
     * @var array Names of possible configuration variables for Listing class, and their default values
     *
     * selectRowFlg - show a checkbox to be able to select/deselect a row (default: FALSE)<BR>
     * selectedRows - array with id of selected rows, or ALL, if all rows should be selected (default: empty)<BR>
     * sumCols      - array of columnnames which should have a summary at the end of the listing table (default: empty)<BR>
     * linkParams   - string of additional parameters added to all inserted links (default: empty)<BR>
     * idFieldName  - name of the field, which should be taken as id of the row (default: id)<BR>
     * listLinks    - array with columnames as key and links as value, which will be shown at the appropriate column (default: empty)<BR>
     * sort         - column name to sort<BR>
     * columnNames  - Names of columns to show<BR>
     * maxRowsPerPage - maximal number of lines shown per page<BR>
     */
    var $configNames = array("selectRowsFlg" => FALSE,
                             "selectedRows" => array(),
                             "sumCols" => array(),
                             "linkParams" => "",
                             "idFieldName" => "id",
                             "listLinks" => array(),
                             "sort" => "",
                             "columnNames" => array(),
                             "maxRowsPerPage" => "10");
    var $config;

    var $firstLink;
    var $previousLink;
    var $nextLink;
    var $lastLink;

    var $maxRows = 0;
    var $maxCols = 0;
    var $values = array(array());

    var $listHeadRows;

    function Listing($id, $config = array())
    {
        global $db;

        $this->id = $id;

        $this->config = $config;

        $sess_id = "listing_$id";

        if ( is_array($config) && !empty($config))
        {

            $this->configNames["maxRowsPerPage"] = getConfigEntry($db, "MaxRowsPerPage");
            $this->configNames["selectedRows"] = getConfigEntry($db, "CheckedCheckboxes") == 1 ? "ALL" : "NONE";

            $this->resetAll();
        }
    }


   /**
    * function showRecordList
    *
    * Creates a table which shows the recordset given as first parameter.
    *
    * Side Effects:
    *    $this->sort      Column to sort output<BR>
    *    $this->idFieldName    (Default: id) Columnname of id field. This is the unique identifier of a row<BR>
    *    $this->change    False (default): Edit of recordset ist not allowed; true: records may been edited<BR>
    *    $this->selectRow False (default): No checkbox is displayed, true: a checkbox is displayed before each line<BR>
    *    $this->sumCols     (Default: empty array) columns to sum. If at least one column has to been summed up,<BR>
    *                    an additional line is displayed at the end of the table which shows the sum of
    *                    this/these column(s)

    *  Parameter:
    * @param string $cols colon-delimeted list of columns to display.
    * @param array $listLinks  (Default: empty array) An array of links. The index is the columnname, where to show the link
    *                    The value of the column is passed to the link as parameter<BR>
    *                  There are 3 special Names: DETAIL => Link to detail page, column: ID<BR>
    *                                             EDIT => Link to edit page, column: extra column (pencil)<BR>
    *                                             DELETE => Link to delete page, column: extra column (cross)<BR>
    * @return string  Either the value of the passed variable $text or "&nbsp;" if $text is empty
    */
    function prepareRecordList($cols,$listLinks=array())
    {
        global $skript_prefix;

        function addAddParams($listLink, $addParamArr)
        {
            foreach ( $addParamArr as $key => $val )
            {
                if ( strstr($listLink, "$key=") === false )
                {
                    $listLink .= "&$key=$val";
                }
            }
            return $listLink;
        }

        parse_str($this->getConfig("linkParams"), $output);
        $output["cols"] = rawurlencode($cols);
        $output["sort"] = rawurlencode($this->getConfig('sort'));

        $sumCols = $this->getConfig("sumCols");
        if ( count($sumCols) > 0 )
        {
            for ($i=0 ; $i < count($sumCols); $i++ )
            {
                $sumVal[$sumCols[$i]] = 0;
            }
        }
        $listLinkParam = "";
        $listLinkParam .= "cols=" . rawurlencode($cols);
        $listLinkParam .= "&sort=" . rawurlencode($this->getConfig('sort'));

        // Set link to detail page and separator (& if already a ? sign is found, ? else)
        $detailLink = (!isset($listLinks["Detail"])) ? "${skript_prefix}_detail.php" : "$listLinks[Detail]";
        $detailLink = addAddParams($detailLink, $output);
        $detailCatChar = (strchr($detailLink,"?") !== false) ? "&" : "?";

        // Set link to detail page and separator (& if already a ? sign is found, ? else)
        $editLink = (!isset($listLinks["Edit"]) || $listLinks["Edit"] == "") ? "${skript_prefix}_edit.php" : "$listLinks[Edit]";
        $editLink = addAddParams($editLink, $output);
        $editCatChar = (strchr($editLink,"?") !== false) ? "&" : "?";

        // Set link to detail page and separator (& if already a ? sign is found, ? else)
        $delLink = (!isset($listLinks["Delete"]) || $listLinks["Delete"] == "") ? "${skript_prefix}_delete.php" : "$listLinks[Delete]";
        $delLink = addAddParams($delLink, $output);
        $delCatChar = (strchr($delLink,"?") !== false) ? "&" : "?";

        $idField = -1;

        $startIndex = ($this->pageNr - 1) * $this->getConfig("maxRowsPerPage");
        if ( $startIndex < 0 )
        {
            $startIndex = 0;
        }

        $this->firstLink = "'$_SERVER[SCRIPT_NAME]?" . addAddParams("PageNr=1", $output) . "'";
        $this->previousLink = "'$_SERVER[SCRIPT_NAME]?" . addAddParams("PageNr=" . ($this->pageNr-1 < 1 ? 1 : $this->pageNr - 1), $output) . "'";
        $this->nextLink = "'$_SERVER[SCRIPT_NAME]?" . addAddParams("PageNr=" . ($this->pageNr+1 > $this->pagecount ? $this->pagecount : $this->page+1), $output) . "'";
        $this->lastLink = "'$_SERVER[SCRIPT_NAME]?" . addAddParams("PageNr=$this->pagecount", $output) . "'";

        $this->listHeadRows = array();
        $colArr = $this->getConfig("columnNames");
        $colKeysArr = array_keys((array)$colArr);
        for ( $i=0; $i < count($colKeysArr); $i++ )
        {
            if ( strtolower($colKeysArr[$i]) == strtolower($this->getConfig('idFieldName')) )
            {
                    // Spalte merken fr sp�er
                    $idField = $i;
            }
            //$bgcolor =  $name[$i]["raw"] == $this->sort ? "#99ccff" : "#dae0f1";
            if ( $colKeysArr[$i] == $this->getConfig('sort') )
            {
                $this->sortColumn = $i;
            }

            $this->listHeadRows[0][$i] = "<a href='$_SERVER[SCRIPT_NAME]?" .
                                            addAddParams("sort=" . $colKeysArr[$i], $output) .
                                         "'>" .
                                         $colArr[$colKeysArr[$i]] .
                                         "</a>";
        }

        $this->listBodyRows = array(array());
        for ( $aktRowNr_RS = 0; $aktRowNr_RS < $this->maxRows ;  $aktRowNr_RS++)
        {
            $col = 0;
            $id = ( $idField >= 0 ? $this->listBodyRows[$aktRowNr_RS][$idField] : '');

            $this->listBodyRows[$aktRowNr_RS][$col++] = $aktRowNr_RS + $startIndex + 1;
            if ( $this->getConfig("selectRowsFlg") == true )
            {
                if ( $this->getConfig("selectedRows") == "ALL" )
                {
                    $checked = "CHECKED";
                }
                $idFieldName = $this->getConfig('idFieldName');
                $this->listBodyRows[$aktRowNr_RS][$col++] = "<input class=checkbox type=checkbox $checked VALUE=$id NAME='{$idFieldName}[]'>";
            }

            for ( $aktColNr = 0; $aktColNr < $this->maxCols; $aktColNr++ )
            {
                $wert = $this->values[$aktRowNr_RS][$aktColNr];

                if ( count($this->getConfig("sumCols")) > 0 && in_array($name[$i]["raw"], $this->getConfig("sumCols") ) )
                {
                    $sumVal[$name[$i]["raw"]] += $wert;
                }

                if ( empty($wert) || $wert == "NULL" )
                {
                    $wert = "&nbsp;";
                }
                if ( $i == $idField )
                {
                    if ( $detailLink <> "" )
                    {
                        $this->listBodyRows[$aktRowNr_RS][$col++] =
                            "<a href='$detailLink$detailCatChar$this->idFieldName=$wert&selectCMD='" . rawurlencode($selectCMD) . "'>$wert</a>";
                    }
                    else
                    {
                        $this->listBodyRows[$aktRowNr_RS][$col++] = $wert;
                    }
                }
                elseif ( isset($listLinks[$colKeysArr[$aktColNr]]) )
                {
                    $this->listBodyRows[$aktRowNr_RS][$col++] =
                        "<a href='" . $listLinks[$colKeysArr[$aktColNr]] . ((strchr($listLinks[$colKeysArr[$aktColNr]],"?") !== false) ? "&" : "?") . $colKeysArr[$aktColNr] . "=$wert'>" .
                        $wert .
                        "</a>\n";
                }
                else
                {
                    $this->listBodyRows[$aktRowNr_RS][$col++] = $wert;
                }
            }
        }

        $this->listFooterRows = array();
        if ( count($this->getConfig("sumCols")) > 0 )
        {
            $this->listFooterRows[0][0] = lang("Sum");
            for ( $aktColNr = 0; $aktColNr < $this->maxCols; $aktColNr++ )
            {
                if ( in_array($colKeysArr[$aktColNr], $this->getConfig("sumCols")) )
                {
                    $this->listFooterRows[0][$i] = $sumVal[$colKeysArr[$aktColNr]];
                }
                else
                {
                    $this->listFooterRows[0][$i] = "&nbsp;";
                }
            }
        }
    }

    function showRecordList($cols,$listLinks=array())
    {
        $this->prepareRecordList($cols, $listLinks);
        displayList($this);
    }

    function resetAll()
    {
        $this->unsetConfig();

        foreach($this->configNames as $key => $value)
        {
            $this->setConfig($key, (isset($this->config[$key]) ? $this->config[$key] : $value));
//             $this->setConfig($key, (in_array($key, array_keys($this->config)) ? $this->config[$key] : $value));
        }
    }

    function unsetConfig()
    {
        unset($_SESSION["listing_" . $this->id]);
    }
    function getConfig($name, $idx = NULL)
    {
        if ( isset($idx) )
        {
            return empty($_SESSION["listing_" . $this->id][$name][$idx]) ? NULL : $_SESSION["listing_" . $this->id][$name][$idx];
        }
        else
        {
            return empty($_SESSION["listing_" . $this->id][$name]) ? NULL : $_SESSION["listing_" . $this->id][$name];
        }
    }

    function setConfig($name, $val, $idx = NULL)
    {
        debug_backtr('LIST');
        debug_r('LIST', $val, "[setConfig]: NAME: $name, IDX: $idx");
            if ( !array_key_exists("listing_" . $this->id, $_SESSION) ) {
        	$_SESSION["listing_" . $this->id] = array();
        }
        if (isset($idx))
        {
          if ( !array_key_exists($name, $_SESSION["listing_" . $this->id]) ) {
        		$_SESSION["listing_" . $this->id][$name] = array();
        	}
        	debug_r('LIST', $_SESSION["listing_" . $this->id][$name], "[setConfig] SESSION _SESSION[listing_{$this->id}][$name] BEFORE");
            $_SESSION["listing_" . $this->id][$name][$idx] = $val;
            debug_r('LIST', $_SESSION["listing_" . $this->id][$name], "[setConfig] SESSION AFTER");
        }
        else
        {
          if ( !array_key_exists($name, $_SESSION["listing_" . $this->id]) ) {
        		$_SESSION["listing_" . $this->id][$name] = '';
        	}
        	debug_r('LIST', $_SESSION["listing_" . $this->id][$name], "[setConfig] SESSION _SESSION[listing_{$this->id}][$name] BEFORE");
            $_SESSION["listing_" . $this->id][$name] = $val;
            debug_r('LIST', $_SESSION["listing_" . $this->id][$name], "[setConfig] SESSION AFTER");
        }
        return $val;
    }

    function addRow()
    {
        $colNums = func_num_args();
        $argList = func_get_args();

        // Parameters are passed as an array or a list of parameters
        if ( $colNums == 1 && is_array($argList[0]) )
        {
            // If passed as array, get array entries and count them
            $argList = $argList[0];
            $colNums = count($argList);
//             echo "COLNUM: $colNums<BR>";
        }

        if ( $colNums > $this->maxCols )
        {
            $this->maxCols = $colNums;
        }

        $this->values[$this->maxRows++] = $argList;
    }


    function setColumns($cols = "*")
    {
        $retVal = NULL;

        if ( is_array($cols) )
        {
            $cols = implode(',',array_filter($cols));
            $retVal = $this->setConfig("cols", $cols);
        }
        elseif ($cols <> "" )
        {
            $retVal = $this->setConfig("cols", $cols);
        }
        else
        {
            $retVal = $this->getConfig("cols");
        }
        return $retVal;
    }

    function showTable($pageNr = 1)
    {
        $this->pageNr = $pageNr;

        echo "<INPUT TYPE='HIDDEN' NAME='listing_id' VALUE='$this->id'>\n";
        $this->showRecordList($this->getConfig('cols'), $this->getConfig('listLinks'));

    }

    function exportExcel($title = "")
    {
      global $APerr;
      $APerr->setWarn(__FILE__,__LINE__,lang("Not yet implemented"));
    }

       function recordCount()
       {
           return $this->maxRows;
       }

    function setSelectedRows($id, $setFlg)
    {
        $this->setConfig('selectedRowsArr', $setFlg, $id);
    }

    function setAllSelectedRows($setFlg)
    {
        $this->getConfig("selectedRows") == $setFlg ? "ALL" : "NONE";
    }
}
?>
