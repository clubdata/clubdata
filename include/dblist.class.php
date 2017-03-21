<?php
/**
 * @package Clubdata
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
if (defined('CLASS_DBLIST')) {
    return 0;
} else {
    define('CLASS_DBLIST', true);
}
global $db;

require_once("include/function.php");
require_once("include/listing.class.php");

/**
 * @package Clubdata
 */
class DbList extends Listing {

    var $db;        // DB connection
    var $sql;       // SQL command
    var $id;        // ID
    var $idFieldName;
    var $withoutHeader = true;
    var $changeFlg = false;
    var $selectRows = true;

    // Name of session configuration
    var $sessConfig;

    // Array of possible configuration parameters and their default values
    // These values may be overwritten by the config array, when creating
    // an instance of this class or by a call to setConfig (implicit or explicit)
    var $configNames = array(
        "cols"           => '*',
        "sql"            => '',
        "cond"           => '',
        "changeFlg"      => false,
        // possible: FALSE; TRUE; 'SIMPLE'
        "selectRowsFlg"  => false,
        "selectedRows"   => array(),
        "sumCols"        => array(),
        "linkParams"     => "",
        "idFieldName"    => "id",
        "listLinks"      => array(),
        "sort"           => "",
        "maxRowsPerPage" => "10"
    );

    // Page number to display
    var $pageNr = 0;

    // Links for paging table display
//     var $firstLink;
//     var $previousLink;
//     var $nextLink;
//     var $lastLink;

    function DbList($db, $id, $config = "") {
        $this->db = $db;
        $this->id = $id;
        $this->config = $config;

        Listing::Listing($id, $config);

        datapager::datapager($this->db);

        if ($this->getConfig("cond")) {
            $this->generateSQL();
        }
    }

    function copyList($newId) {
        $new = $this;
        $new->id = $newId;

        return $new;
    }

    /*
    * function showRecordList
    *
    *  Description:
    *    Creates a table which shows the recordset given as first parameter.
    *
    *  Parameter:
    *    $rs        Recordset to display. All rows and all lines will be displayed
    *    $selectCMD SQL select command which genereated the recordset
    *    $cols      Columns to display. THIS PARAMETER IS NOT USED BY SHOWRECORDLIST, BUT PASSED TO THE
    *                DETAIL FUNCTION
    *    $listLinks    (Default: empty array) An array of links. The index is the columnname, where to show the link
    *                    The value of the column is passed to the link as parameter
    *                  There are 3 special Names: DETAIL => Link to detail page, column: ID
    *                                             EDIT => Link to edit page, column: extra column (pencil)
    *                                             DELETE => Link to delete page, column: extra column (cross)
    *    $selectedRows if set to ALL, all lines will be selected by default (see also $selectRow)
    *    $sumCols     (Default: empty array) columns to sum. If at least one column has to been summed up,
    *                    an additional line is displayed at the end of the table which shows the sum of
    *                    this/these column(s)
    *
    * Return:
    *    Either the value of the passed variable $text or "&nbsp;" if $text is empty
    *
    * Side Effects:
    *    $this->sort      Column to sort output
    *    $this->idFieldName    (Default: id) Columnname of id field. This is the unique identifier of a row
    *    $this->change    False (default): Edit of recordset ist not allowed; true: records may been edited
    *    $this->selectRow False (default): No checkbox is displayed, true: a checkbox is displayed before each line
    *    $this->linkParams Parameter to add to all link statements
    */
    function prepareRecordList($pageNr = 1, $listLinks = array()) {
        global $DelCross,$EditPencil, $PictPic, $skript_prefix, $db;

        function addAddParams($listLink, $addParamArr) {
            foreach ($addParamArr as $key => $val) {
                if (strstr($listLink, "{$key}=") === false) {
                    $listLink .= "&{$key}={$val}";
                }
            }

            return $listLink;
        }

        $this->pageNr = $pageNr;

        $sql = $this->generateSQL();
        $rs = null;

        if (empty($sql)) {
            return null;
        }

        $rs = $this->execute($pageNr, $this->getConfig("maxRowsPerPage"));

        if ($rs === false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$this->mainquery}");
            return null;
        }

        parse_str($this->getConfig("linkParams"), $output);

        $output["cols"] = rawurlencode($this->cols);
        $output["sort"] = rawurlencode($this->sort);

        $sumCols = $this->getConfig("sumCols");

        if (count($sumCols) > 0) {
            for ($i = 0; $i < count($sumCols); $i++) {
                $sumVal[$sumCols[$i]] = 0;
            }
        }
        $listLinkParam = "";
        $listLinkParam .= "cols=" . rawurlencode($this->cols);
        $listLinkParam .= "&sort=" . rawurlencode($this->sort);

        // Set link tofirstLink detail page and separator (& if already a ? sign is found, ? else)
        $detailLink = (!isset($this->listLinks["Detail"])) ? "{$skript_prefix}_detail.php" : $this->listLinks['Detail'];
        $detailLink = addAddParams($detailLink, array_merge($output, array("PageNr" => $this->pageNr)));
        $detailCatChar = (strchr($detailLink, "?") !== false) ? "&" : "?";

        // Set link to detail page and separator (& if already a ? sign is found, ? else)
        $editLink = (!isset($this->listLinks["Edit"]) || $this->listLinks["Edit"] == "")
            ? "{$skript_prefix}_edit.php" : $this->listLinks['Edit'];
        $editLink = addAddParams($editLink, array_merge($output, array("PageNr" => $this->pageNr)));
        $editCatChar = (strchr($editLink, "?") !== false) ? "&" : "?";

        // Set link to detail page and separator (& if already a ? sign is found, ? else)
        $delLink = (!isset($this->listLinks["Delete"]) || $this->listLinks["Delete"] == "")
            ? "{$skript_prefix}_delete.php" : $this->listLinks['Delete'];
        $delLink = addAddParams($delLink, array_merge($output, array("PageNr" => $this->pageNr)));
        $delCatChar = (strchr($delLink, "?") !== false) ? "&" : "?";

        $idField = -1;

        $startIndex = ($this->pageNr - 1) * $this->getConfig("maxRowsPerPage");

        if ($startIndex < 0) {
            $startIndex = 0;
        }

        $this->firstLink = "'{$_SERVER['SCRIPT_NAME']}?" . addAddParams("PageNr=1", $output) . "'";
        $this->previousLink = "'{$_SERVER['SCRIPT_NAME']}?" .
            addAddParams("PageNr=" . ($this->pageNr-1 < 1 ? 1 : $this->pageNr - 1), $output) . "'";
        $this->nextLink = "'{$_SERVER['SCRIPT_NAME']}?" .
            addAddParams("PageNr=" . ($this->pageNr+1 > $this->pagecount ? $this->pagecount : $this->page+1), $output) .
            "'";
        $this->lastLink = "'{$_SERVER['SCRIPT_NAME']}?" . addAddParams("PageNr=$this->pagecount", $output) . "'";

//         debug_backtr('MAIN');exit;
        $this->listHeadRows = array();
        $idFieldName = resolveField($this->idFieldName);

        for ($i = 0; $i < $rs->FieldCount(); $i++) {
            $name[$i] = resolveFieldIndex($rs, $i);

            if (strtolower($name[$i]["column"]) == strtolower($idFieldName['column'])) {
                // Save column for later use
                $idField = $i;
            }

            //$bgcolor =  $name[$i]["raw"] == $this->sort ? "#99ccff" : "#dae0f1";
            if ($name[$i]["raw"] == $this->sort) {
                $this->sortColumn = $i;
            }

            $this->listHeadRows[0][$i] = "<a href='{$_SERVER['SCRIPT_NAME']}?" .
                                            addAddParams("sort=" . urlencode($name[$i]["raw"]), $output) .
                                         "'>" .
                                         lang($name[$i]["pretty"]) . // Use pretty column
                                         "</a>";
        }

        $selectedRowsArr = $this->getConfig('selectedRowsArr');

        $this->listBodyRows = array(array());

        for ($aktRowNr_RS = 0; $rowArrAssoc = $rs->FetchRow(); $aktRowNr_RS++) {
            $col = 0;

            if ($idField >= 0) {
                $id = $rowArrAssoc[$idFieldName['raw']];
            }

            $this->listBodyRows[$aktRowNr_RS][$col++] = $aktRowNr_RS + $startIndex + 1;

            if ($this->getConfig("selectRowsFlg") === true && isset($id)) {
                /* Set checked if an id is set AND either there is NO correspondig
                 * entry in $selectedRowsArr (e.g. a new entry) AND the config
                 * parameter 'selectedRows' is set to ALL,
                 * OR (there is a corresponding entry in $selectedRowsArr and) the
                 * corresponding entry in $selectedRowsArr is set to 1
                 */

                $checked = '';

                if (isset($id) && ((count($selectedRowsArr) == 0 && $this->getConfig('selectedRows') == 'ALL')
                    || $selectedRowsArr[$id] == 1)) {
                    $checked = 'CHECKED';
                }

                $this->listBodyRows[$aktRowNr_RS][$col++] =
                    "<input class='checkbox' type='checkbox' $checked VALUE='$id' NAME='{$this->idFieldName}[]'
                        onClick=\"toggleChecked(this.value, this.checked, '&" .
                        addAddParams("PageNr=$this->pageNr", $output) . "');\" >";
            } elseif ($this->getConfig("selectRowsFlg") === 'SIMPLE' && isset($id)) {
                $checked = ( isset($id) && $this->getConfig('selectedRowsArr', $id) == 1 ) ? "CHECKED" : "";
                $this->listBodyRows[$aktRowNr_RS][$col++] =
                    "<input class='checkbox' type='checkbox' $checked VALUE='$id' NAME='{$this->idFieldName}[]'>";
            }

            if ($this->getConfig("changeFlg") == true) {
                $l_delete = sprintf(lang("Do you really want to delete entry %s"), $id);
                $this->listBodyRows[$aktRowNr_RS][$col++] = <<<_EOT_
                <a href='$_SERVER[SCRIPT_NAME]'
                onClick="return delRow(this, '{$l_delete}', '{$delLink}', '{$editCatChar}".
                    "{$this->idFieldName}={$id}&{$listLinkParam}');">
                <img src='$DelCross' width='20' height='19' border='0'></a>
_EOT_;
                $this->listBodyRows[$aktRowNr_RS][$col++] = <<<_EOT_
                <a href='${editLink}$editCatChar$this->idFieldName=$id&$listLinkParam'>
                <img src='$EditPencil' width='20' height='19' border='0'></a>
_EOT_;
            }

            for ($i = 0; $i < $rs->FieldCount(); $i++) {
                $wert = htmlspecialchars($rowArrAssoc[$name[$i]["raw"]], ENT_QUOTES);

                if (count($this->getConfig("sumCols")) > 0 && in_array($name[$i]["raw"], $this->getConfig("sumCols"))) {
                    $sumVal[$name[$i]["raw"]] += $wert;
                }

                switch ($name[$i]["type"]) {
                    case "myref":
                        if ($wert <> "") {
                            if (!is_int($wert)) {
                                $wert = "'$wert'";
                            }

                            $wert = getMyRefDescription($db, $wert, $name[$i]);
                        }
                        break;
                    case "date":
                        $wert = myDateToPhPDate($wert);
                        break;
                    case "yesno":
                        $wert = $wert != 0 ? lang("Yes") : lang("No");
                        break;
                    case "mylink":
                        if ($wert == "" || empty($wert) || $wert == "NULL") {
                            $wert = "&nbsp;";
                        } else {
                            $wert = "<a href='$wert'>";
                            $wert .= "<img src='$PictPic' width='20' height='19' border='0'></a>";
                        }
                        break;
                    case "password":
                        $wert = "*****";
                        break;
                    default:
                        //	      $name = ucfirst($name);
                        //            $wert = Format($wert);
                        break;
                }

                if (!isset($wert) || $wert == ""|| $wert == "NULL") {
                    $wert = "&nbsp;";
                }

                if ($i == $idField) {
                    if ($detailLink <> "") {
                        $this->listBodyRows[$aktRowNr_RS][$col++] =
                            "<a href='$detailLink$detailCatChar$this->idFieldName=" .
                            htmlspecialchars($rowArrAssoc[$name[$i]["raw"]], ENT_QUOTES) .
                            "'>$wert</a>";
                    } else {
                        $this->listBodyRows[$aktRowNr_RS][$col++] = $wert;
                    }
                } elseif (isset($this->listLinks[$name[$i]["raw"]])) {
                    $this->listBodyRows[$aktRowNr_RS][$col++] =
                        "<a href='" . $this->listLinks[$name[$i]["raw"]] .
                            ((strchr($this->listLinks[$name[$i]["raw"]], "?") !== false) ? "&" : "?") .
                            $name[$i]["raw"] . "=" .
                            htmlspecialchars($rowArrAssoc[$name[$i]["raw"]], ENT_QUOTES) . "'>" .
                        $wert .
                        "</a>\n";
                } else {
                    $this->listBodyRows[$aktRowNr_RS][$col++] = $wert;
                }
            }
        }

        $this->listFooterRows = array();

        if (count($this->getConfig("sumCols")) > 0) {
            $this->listFooterRows[0][0] = lang("Sum");

            for ($i = 1; $i < $rs->FieldCount(); $i++) {
                if (in_array($name[$i]["raw"], $this->getConfig("sumCols"))) {
                    $this->listFooterRows[0][$i] = $sumVal[$name[$i]["raw"]];
                } else {
                    $this->listFooterRows[0][$i] = "&nbsp;";
                }
            }
        }
    }

    function showRecordList($pageNr, $listLinks = array()) {
        $this->prepareRecordList($pageNr);
        displayList($this);
    }

    function setSQL($sql) {
        return $this->setConfig("sql", $sql);
    }

    # cond = Condition to set
    # bool = "" (or "SET") Set condition, overwrite old ones
    #         "AND" AND condition to existing one
    #         "OR" OR condition to existing one
    #
    function generateCondition($cond = "", $bool = "") {
        global $APerr;

        $select = "";
        $retVal = null;
        $preCond = "";

        debug('DBLIST', "[generateCondition] cond = {$cond}, bool = {$bool}");

        if ($bool == "AND" || $bool == "OR") {
            $preCond = $this->getConfig("cond");

            if (strlen($preCond) > 0) {
                $preCond = "( " . $preCond . " ) ";
            }
        }

//       phpinfo();
        if ($cond != "") {
            $retVal = $this->setConfig("cond", "{$preCond} {$bool} {$cond}");
            debug('DBLIST', "[generateCondition] retVal = {$retVal}, SET = {$preCond} {$bool} {$cond}");
        } elseif (checkGlobNameExists("_select$")) {
            $db = $this->db;
            $sql = $this->getConfig("sql");
            $rs = $db->SelectLimit($sql, 1);

            if ($rs === false) {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$sql}");
            } else {
                $cond = generateSelectCMD($rs);

                if (strlen($cond) > 0) {
                    $retVal = $this->setConfig("cond", "{$preCond} {$bool} {$cond}");
                } else {
                    $retVal = $this->setConfig("cond", $preCond);
                }
            }
        } else {
            $retVal = $this->getConfig("cond");
        }

        return $retVal;
    }

    function showTable($pageNr = 1) {
        global $APerr;

        $this->pageNr = $pageNr;

        $sql = $this->generateSQL();
        $rs = null;

        if (!empty($sql)) {
            $rs = $this->execute($pageNr, $this->getConfig("maxRowsPerPage"));

            if ($rs === false) {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$this->mainquery}");
            } else {
                echo "<INPUT TYPE='HIDDEN' NAME='cllist_id' VALUE='{$this->id}'>\n";
                $this->showRecordList($pageNr);

                // Reset Recordset and Return it, so it can be used for other purposes
                $rs->MoveFirst();
            }
        }
        return $rs;
    }

    function exportExcel($title = "") {
        global $APerr;

        $saveCond = null;

        debug('DBLIST', "[exportExcel] selectRowsFlg = " . $this->getConfig('selectRowsFlg'));

        if ($this->getConfig('selectRowsFlg') === true) {
            debug_r('DBLIST', $this->getConfig('selectedRowsArr'), "[exportExcel] selectRowsArr");
            $memberList = join(",", array_keys($this->getConfig('selectedRowsArr'), 1));

            if ($memberList == '') {
                $APerr->setInfo(__FILE__, __LINE__, lang('No Rows selected !'));
                return;
            }

            $idFieldName = resolveField($this->getConfig('idFieldName'));

            //FD20100606 special handling for MemberID without table name
            if (empty($idFieldName['table']) && $idFieldName['column'] == 'MemberID') {
                $idColumn = clubdata_mysqli::replaceDBprefix('`###_Members`.`MemberID`', DB_TABLEPREFIX);
            } else {
                // Filter entries "table" and "column" from idFieldName and join them by '.'
                // If no table entry exists, the column is returned without .
                $idColumn = (!empty($idFieldName['table'])
                    ? $idFieldName['table'] . "." . $idFieldName['column']
                    : $idFieldName['column'] );
            }

            $cond =  $idColumn . " IN ({$memberList})";
            $saveCond = $this->getConfig("cond");
            // Add memberList to original condition.
            // In the worst case, there should be two MemberID IN (...) conditions: The smaller will win
            $this->generateCondition((!empty($saveCond) ? "{$saveCond} AND " : "") . $cond);
        }

        $sql = $this->prepareSQL();

//        print "SQL:$sql<BR>";exit;
        if (!empty($sql)) {
            $rs = $this->db->Execute($sql);

            if ($rs === false) {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$sql}");
            } else {
                // Ignore output made so far
                $tmpOutputContent = ob_get_contents();

                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                $this->generateExcelList($rs, $title);

                // Restart output buffering
                ob_start();
                echo $tmpOutputContent;
            }
        }

        if (!empty($saveCond)) {
            $this->generateCondition($saveCond);
        }
    }

    function getSelectedRowIds() {
/*        $x = array_keys(array_filter($this->getConfig('selectedRowsArr')));
        debug_r('DBLIST',$x , "[DBLIST,getSelectedRowIds],selectedRowsArr ");
*/
        return array_keys(array_filter($this->getConfig('selectedRowsArr')));
    }

    function setSelectedRows($id, $setFlg) {
        debug_backtr('DBLIST');
        $this->setConfig('selectedRowsArr', $setFlg, $id);
        debug_r('DBLIST', $this->getConfig('selectedRowsArr'), "[DBLIST,setSelectedRows],selectedRowsArr ");
    }

    function setAllSelectedRows($setFlg) {
        debug_backtr('DBLIST');
        $sql = $this->prepareSQL();

        // prepareSQL will set idFieldName if not unset by user
        if (empty($this->idFieldName)) {
            debug('DBLIST', 'idFieldName not set !! Cannot set Selectflag to $setFlg');
            return;
        }

        $rs1 = $this->db->Execute($sql);

        if ($rs1 === false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$sql}");
            return false;
        } elseif (!($rs1->EOF)) {
            $selectedRows = array();

            while (!($rs1->EOF) && ($arr = $rs1->fetchRow())) {
                debug('DBLIST', "[setAllSelectedRows] selectedRows[" . $arr[$this->idFieldName] . "]= {$setFlg}");
                $selectedRows[$arr[$this->idFieldName]] = $setFlg;
            }

            $this->setConfig('selectedRowsArr', $selectedRows);
        }
    }

	function prepareSQL() {
        global $APerr;

        foreach ($this->configNames as $key => $value) {
            $this->$key = $this->getConfig($key);
        }

        $sql = $this->sql;

        if ($this->cond <> "") {
            if (preg_match("/\s+WHERE\s+/", $sql)) {
                $sql = preg_replace("/\s+WHERE\s+/", " WHERE {$this->cond} AND ", $sql);
            } elseif (preg_match("/\s+GROUP\s+BY\s+/", $sql)) {
                $sql = preg_replace("/\s+GROUP\s+BY\s+/", " WHERE {$this->cond} GROUP BY ", $sql);
            } else {
                $sql .= " WHERE {$this->cond}";
            }
        }

        if ($this->sort <> "") {
            $sql .= " ORDER BY {$this->sort}";
        }

        if ($this->cols <> "*") {
            $sql = preg_replace("/^SELECT(\s+DISTINCT)?\s+\*/", "SELECT DISTINCT {$this->cols}", $sql);
        }

        return $sql;
    }

    function generateSQL() {
        global $APerr;

        $sql = $this->prepareSQL();

        if (!empty($sql)) {
            if ($this->loadquery($sql, $this->getConfig("maxRowsPerPage")) === false) {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$sql}");
                $sql = null;
            } else {
                // Calculate selected rows by default
                if ($this->getConfig('selectRowsFlg') !== false
                    && $this->getConfig('selectedRows') == 'ALL'
                    && count($this->getConfig('selectedRowsArr')) == 0) {
                    $this->setAllSelectedRows(1);
                }
            }
        }

        return $sql;
    }

    function recordCount() {
        global $APerr;

        $sql = $this->prepareSQL();
        $rs = $this->db->Execute($sql);

        if ($rs === false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->ErrorMsg(), "SQL: {$sql}");
            return false;
        }

        return ($rs->RecordCount());
    }


    function createPdf() {
        require_once("include/createPDF.class.php");

        $pdfObj = new createPDF($this->db);
        $sql  = $this->prepareSQL();

        $pdfObj->_createPDFbySQL($sql);
    }

    function generateExcelList($rs, $titel, $startLine = 0, $startCol = 0) {
        global $skript_prefix, $db;

        $lineNr = $startLine;
        $myxls = new BiffWriter();   // new BiffWriter class
        $cols = $rs->FieldCount();
        $myxls->xlsSetFont('Arial', 10, FONT_NORMAL);     // font 0
        $myxls->xlsSetFont("Arial", 16, FONT_BOLD);      // font 1

        if (!empty($titel)) {
            $myxls->xlsWriteText(
                $lineNr++,
                $cols/2 + $startCol,
                icT($titel),
                -1,
                0,
                FONT_1,
                ALIGN_CENTER
            ); // write text into cell A1

            $lineNr++;
        }

        for ($i = 0; $i < $rs->FieldCount(); $i++) {
            $name[$i] = resolveFieldIndex($rs, $i);
            $myxls->xlsWriteText($lineNr, $i, icT(lang($name[$i]["column"]))); // write text into cell A1
        }

        $lineNr++;

        while ($arr = $rs->FetchRow()) {
            for ($i = 0; $i < $rs->FieldCount(); $i++) {
                $ft = $rs->FetchField($i);
                $wert = $arr[$ft->name];

                if ($name[$i]['raw'] == 'MemberID' && ! is_numeric($wert)) {
                    // Skip pseudo line when generating Infoletters
                    // (Column MemberID is not numeric)
                    break;
                }

                switch ($name[$i]["type"]) {
                    case "myref":
                        if (!empty($wert)) {
                            $wert = getMyRefDescription($db, $wert, $name[$i]);
                        }
                        break;
                    case "mylink":
                        break;
                    case "password":
                        $wert = "*****";
                        break;
                    case "yesno":
                        $wert = $wert != 0 ? "1" : "0";
                        break;
                    default:
                        //    $name = ucfirst($name);
                        //    $wert = Format($wert);
                        break;
                }

                if ($wert == "" || !isset($wert) || $wert == "NULL") {
                    $wert = "";
                }

                if (is_numeric($wert)) {
                    // FD20100620: changed intval to doubleval
                    $myxls->xlsWriteNumber($lineNr, $i, doubleval($wert)); // write text into cell A1
                } else {
                    $myxls->xlsWriteText($lineNr, $i, icT($wert)); // write text into cell A1
                }
            }
            $lineNr++;
        }

        $myxls->xlsParse();
        exit;
    }
}
