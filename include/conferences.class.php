<?php
/**
 * Conferences class
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
require_once('include/dbtable.class.php');

/**
 *
 * @package Clubdata
 */
class Conferences extends DbTable {

    public $db;
    public $memberID;
    public $conferenceID;
    public $formsgeneration;

    public $fieldList = 'id, Description_UK , Description_DE , Description_FR , Startdate , Starttime , Enddate '
                    .', Endtime , DescrLong_UK , DescrLong_DE , DescrLong_FR , PriceMember , PriceNoMember , Active_yn';

    /**
     * Constructor Conferences
     *
     * This constructor creates an instance of the class Conferences.
     *
     * @param object $db Databaseobject
     * @param object $formsgeneration Object of Formsgeneration
     * @param array $selectionArr Associative Array with either "MemberID" or "ConferenceID" as keys and the appropriate
     *                            IDs as values or as comma separated list
     */
    public function __construct($db, $formsgeneration, $selectionArr = array()) {
        debug_r('M_CONFERENCES', $selectionArr, '[Class Conferences, Conferences] selectionArr=');
        $this->db = $db;
        $this->formsgeneration = $formsgeneration;

        if (isset($selectionArr['MemberID'])) {
            $this->setConferencesByMemberID($selectionArr['MemberID']);
        } elseif (isset($selectionArr['ConferenceID'])) {
            $this->setConferencesByConferenceID($selectionArr['ConferenceID']);
        } else {
            // Set pseudo table whithout any selected entry (1=0) to initialize tablename and fieldlist
            parent::__construct($this->db, $this->formsgeneration, '`###_Conferences`', "1=0", $this->fieldList);
        }
    }


    public function setConferencesByConferenceID($conferenceID) {
        $this->conferenceID = $conferenceID;

        if (empty($this->conferenceID)) {
            return false;
        }

        if (is_array($this->conferenceID)) {
            $where = "id IN (" . join(',', $this->conferenceID) . ")";
        } else {
            $where = "id IN (" . $this->conferenceID . ")";
        }

        // FIXME This shouldn't be done like this
        parent::__construct($this->db, $this->formsgeneration, '`###_Conferences`', $where, $this->fieldList);

        return true;
    }

    public function setConferencesByMemberID($memberID) {
        $this->memberID = $memberID;

        if (empty($this->memberID)) {
            return false;
        }

        $sql = "SELECT id from `###_Conferences` WHERE MemberID = {$memberID}";
        $idArr = $this->db->GetCol($sql);

        debug_r(
            'M_CONFERENCES',
            $idArr,
            "[Class Conferences: setConferencesByMemberID] SQL: {$sql}, COUNT: " . count($idArr)
        );

        if ($idArr === false && $this->db->ErrorNo() != 0) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return false;
        } else {
            $this->conferenceID = $idArr;

            // FIXME This shouldn't be done like this
            parent::__construct(
                $this->db,
                $this->formsgeneration,
                '`###_Conferences`',
                "MemberID IN ({$this->memberID})",
                $this->fieldList
            );

            return true;
        }
    }

    public function getConferenceIDsAsList() {
        return (is_array($this->conferenceID)) ? join(',', $this->conferenceID) : $this->conferenceID;
    }

    public function getConferenceIDCount() {
        if (empty($this->conferenceID)) {
            return 0;
        } elseif (is_array($this->conferenceID)) {
            return count($this->conferenceID);
        } else {
            return 1;
        }
    }

    public function getConferencesForInvoice($invoiceNumber) {
        global $APerr;

        if (empty($invoiceNumber)) {
            return false;
        }

        $idArr = array();
        $sql = "SELECT id FROM `###_Conferences` WHERE InvoiceNumber = '{$this->invoiceNumber}'";
        $idArr = $this->db->GetCol($sql);

        debug_r(
            'M_CONFERENCES',
            $idArr,
            "[Class Conferences: getConferencesForInvoice] SQL: $sql, COUNT: " . count($idArr)
        );

        if ($idArr === false && $this->db->ErrorNo() != 0) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
        } else {
            if (empty($idArr) || empty($idArr[0])) {
                //Give up, enough is enough !!
                $APerr->setFatal(sprintf(lang('No conferences found for invoice number %s'), $this->invoiceNumber));
            } else {
                $this->setConferencesByConferenceID($idArr);
            }

            return $idArr;
        }
    }

    public function insertRecord() {
        debug('M_CONFERENCES', '[Class Conferences, insertRecord]');

        $this->setWhere("1 = 0");
        $insertId = parent::insertRecord();

        if (!empty($insertId)) {
            $this->setConferencesByConferenceID($insertId);
            logEntry("INSERT", $sql);
        }

        return $insertId;
    }

    public function deleteRecord() {
        global $APerr;

        $sql = "DELETE FROM `###_Conferences` WHERE id IN (" . $this->getConferenceIDsAsList() . ")";
        $retVal = $this->db->Execute($sql);

        if ($retVal === false && $this->db->ErrorNo() != 0) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
        }
    }
}
