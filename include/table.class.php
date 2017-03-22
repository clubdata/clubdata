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

    protected $maxNumCols = 0;

    protected $tableBodyRows;
    protected $tableBodyAttrs;

    // If this table has dependent tables, subTableArr holds an array
    // of DBTable-Objects (mostly overloaded) to this subtables.
    protected $subTableArr = array();  //array of subtable objects

    // If this table has a master table (e.g. it is a subtable),
    // then the following variables holds the id of the master table
    // and the name of this subtable
    protected $masterID = null;
    protected $subTableName = null;

    protected $formsgeneration;

    public function __construct(&$formsgeneration) {
        $this->subTable = array();
        $this->formsgeneration = &$formsgeneration;
    }

    public function showRecordDetails($edit = false, $title = '') {
    }

    public function showRecord($title = '') {
        $this->showRecordDetails(NO_EDIT, $title);
    }

    public function editRecord($title = '') {
        $this->showRecordDetails(EDIT, $title);
    }

    public function newRecord($presetVals = array(), $presetEditVals = array()) {
    }

    public function updateRecord($uploadID = '') {
    }

    public function insertRecord($presetVals = array()) {
    }

    /*****************************************************************
     * SUBTABLE RELATED FUNCTIONS
     ****************************************************************/
    /*****************************************************************
     * 1. MASTER FUNCTIONS
     ****************************************************************/
    public function addSubTable($subTableName, $subTableObj) {
        $this->subTableArr[$subTableName] = $subTableObj;
    }

    public function getSubTableNames() {
        return array_keys($this->subTableArr);
    }

    /*****************************************************************
     * 2. SUBTABLE FUNCTIONS
     ****************************************************************/
    public function getRecordAsSubtable($forms) {
        global $APerr;

        $APerr->setFatal(__FILE__, __LINE__, "getRecordAsSubtable must be overloaded !");
    }

    public function updateSubtable($subTableName) {
        $this->subTableArr[$subTableName]->updateRecord();
    }

    public function setMasterTable($masterID) {
        $this->masterID = $masterID;
    }

    public function setSubtableName($subTableName) {
        $this->formsgeneration->NAME=$this->subTableName = $subTableName;
    }
}
