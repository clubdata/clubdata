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
 * @package Clubdata
 */
class Attributes extends DbTable {

    protected $adrTypeArr = null;

    protected $memberID;

    public function __construct($db, &$formsgeneration, $memberID = '') {
        global $APerr;

        $this->memberID = $memberID;

        // Make this table a subtable !!
        $this->setMasterTable($memberID);
        $this->subTableName = 'Attributes';

        $table = '';
        $where = '';
        $fields = '*';

        if (!empty($this->memberID)) {
            $table = "`###_Attributes` LEFT JOIN `###_Members_Attributes`
                                              ON `###_Attributes`.id = `###_Members_Attributes`.Attributes_ref
                                                 AND `###_Members_Attributes`.MemberID = {$this->memberID}";
            $where = '1=1';
            $fields = 'id, `###_Members_Attributes`.Attributes_ref';
        }

        parent::__construct($db, $formsgeneration, $table, $where, $fields);
    }

    public function getRecordAsSubtable($forms) {
        global $APerr;

        $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$this->where}";
        $res = $this->db->Execute($sql);

        if ($res === false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return;
        }

        while ($mgAttrArr = $res->FetchRow()) {
            $descr = getMyRefDescription($this->db, $mgAttrArr["id"], resolveFieldName($res, "Attributes_ref"));
            $checked = (!empty($mgAttrArr["Attributes_ref"])) ? 1 : 0;

            $forms->AddInput(array(
                "TYPE"     => "checkbox",
                "CLASS"    => "CHECKBOX",
                "CHECKED"  => $checked,
                "LABEL"    => $descr,
                "NAME"     => "Attributes",
                "ID"       => "Attributes{$mgAttrArr['id']}",
                "VALUE"    => $mgAttrArr['id'],
                "MULTIPLE" => 1,
                "SubForm"  => $this->subTableName
            ));
        }
    }

    public function updateRecord($uploadID = '') {
        global $APerr;

        if (getGlobVar('SETAttributes', '0|1', 'PG') == 1) {
            $sqlcmd = "DELETE FROM `###_Members_Attributes` WHERE `###_Members_Attributes`.MemberID = {$this->masterID}";

            if (!$this->db->Execute($sqlcmd)) {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
                return false;
            }

            $attrArr = getGlobVar("Attributes", '::number::', 'PG');

            if (!empty($attrArr) && is_array($attrArr)) {
                foreach ($attrArr as $key => $val) {
                    $sql = "INSERT INTO `###_Members_Attributes` (MemberID, Attributes_ref) VALUES ({$this->masterID}, {$val})";

                    if (!$this->db->Execute($sql)) {
                        $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
                        return false;
                    } else {
                        logEntry("UPDATE MEMBER ATTRIBUTES", $sql);
                    }
                }
            }
        }
    }
}
