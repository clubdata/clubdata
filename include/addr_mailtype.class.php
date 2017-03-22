<?php
/**
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/dbtable.class.php');

/**
 * Addresses class to mailingtypes of addresses
 * @package Clubdata
 */
class Addresses_Mailtype extends DbTable {

    public function __construct($db, $formsgeneration, $addressID = '') {
        global $APerr;

        // Make this table a subtable !!
        $this->setMasterTable($addressID);
        $this->subTableName = 'Mailingtypes';

        $table = '`###_Mailingtypes`
                  LEFT JOIN `###_Addresses_Mailingtypes`
                         ON `###_Mailingtypes`.id = `###_Addresses_Mailingtypes`.Mailingtypes_ref
                            AND `###_Addresses_Mailingtypes`.AddressID = '.
                            (empty($this->masterID) ? "NULL" : $this->masterID);
        $fields = '###_Mailingtypes.id, ###_Addresses_Mailingtypes.Mailingtypes_ref';

        parent::__construct($db, $formsgeneration, $table, '1=1', $fields);
    }

    public function getRecordAsSubtable($forms) {
        global $APerr;

        $sql = "SELECT {$this->fields}
                  FROM {$this->table}
                 WHERE {$this->where}";

        $res = $this->db->Execute($sql);

        if ($res === false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return;
        }

        while ($mgAttrArr = $res->FetchRow()) {
            $descr = getMyRefDescription($this->db, $mgAttrArr["id"], resolveFieldName($res, "Mailingtypes_ref"));
            $checked = (!empty($mgAttrArr["Mailingtypes_ref"])) ? 1 : 0;

            $forms->AddInput(array(
                "TYPE"     => "checkbox",
                "CLASS"    => "CHECKBOX",
                "CHECKED"  => $checked,
                "LABEL"    => $descr,
                "NAME"     => "Mailingtypes",
                "ID"       => "Mailingtypes{$mgAttrArr['id']}",
                "MULTIPLE" => 1,
                "VALUE"    => $mgAttrArr['id'],
                "SubForm"  => $this->subTableName
            ));
        }
    }

    public function showRecord($title = '') {
    }

    public function updateRecord($uploadID = '') {
        global $APerr;

        if (getGlobVar('SETMailingtypes', '0|1', 'PG') == 1) {
            $sql = "DELETE FROM `###_Addresses_Mailingtypes` WHERE AddressID = {$this->masterID}";

            if (!$this->db->Execute($sql)) {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
                return false;
            }

            $attrArr = (array)getGlobVar("Mailingtypes", '::number::', 'PG');

            if (!empty($attrArr) && is_array($attrArr)) {
                foreach ($attrArr as $key => $val) {
                    $sql = "INSERT INTO `###_Addresses_Mailingtypes` (AddressID, Mailingtypes_ref) VALUES ({$this->masterID}, {$val})";

                    if (!$this->db->Execute($sql)) {
                        $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
                        return false;
                    } else {
                        logEntry("UPDATE ADDRESS MAILINGTYPES", $sql);
                    }
                }
            }
        }
    }
}
