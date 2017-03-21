<?php
/**
 * Table class
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

require_once('include/function.php');
require_once('include/clubdataFG.class.php');

global $db;

define('NO_EDIT', false);
define('EDIT', true);

/**
 * @package Clubdata
 */
class Table {

    var $maxNumCols = 0;

    var $tableBodyRows;
    var $tableBodyAttrs;

    // If this table has dependent tables, subTableArr holds an array
    // of DBTable-Objects (mostly overloaded) to this subtables.
    var $subTableArr = array();  //array of subtable objects

    // If this table has a master table (e.g. it is a subtable),
    // then the following variables holds the id of the master table
    // and the name of this subtable
    var $masterID = NULL;
    var $subTableName = NULL;

    var $formsgeneration;

    function Table(&$formsgeneration) {
        $this->subTable = array();
        $this->formsgeneration = &$formsgeneration;
    }

    function showRecordDetails($edit = false, $title = '') {
    }

    function showRecord($title = '') {
        $this->showRecordDetails(NO_EDIT, $title);
    }

    function editRecord($title = '') {
        $this->showRecordDetails(EDIT, $title);
    }

    function newRecord($presetVals = array(), $presetEditVals = array()) {
    }

    function updateRecord($uploadID = '') {
    }

    function insertRecord($presetVals = array()) {
    }

    /*****************************************************************
     * SUBTABLE RELATED FUNCTIONS
     ****************************************************************/
    /*****************************************************************
     * 1. MASTER FUNCTIONS
     ****************************************************************/
    function addSubTable($subTableName, $subTableObj) {
         $this->subTableArr[$subTableName] = $subTableObj;
    }

    function getSubTableNames() {
        return array_keys($this->subTableArr);
    }

    /*****************************************************************
     * 2. SUBTABLE FUNCTIONS
     ****************************************************************/
    function getRecordAsSubtable($forms) {
        global $APerr;

        $APerr->setFatal(__FILE__,__LINE__,"getRecordAsSubtable must be overloaded !");
    }

    function updateSubtable($subTableName) {
        $this->subTableArr[$subTableName]->updateRecord();
    }

    function setMasterTable($masterID) {
        $this->masterID = $masterID;
    }

    function setSubtableName($subTableName) {
        $this->formsgeneration->NAME=$this->subTableName = $subTableName;
    }
}
