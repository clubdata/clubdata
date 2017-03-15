<?php
/**
 * Memberinfo class
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
 * @package Clubdata
 */
class Memberinfo extends DbTable {

    var $db;
    var $memberID;
    var $formsgeneration;
        
    var $fieldList = 'Membertype_ref, MembershiptypeSince, MainMemberID, Entrydate,
                      Language_ref, InfoGiveOut_ref, InfoWWW_ref,
                      LoginPassword_pw,
                      "" as Attributes,
                      Selection_ml, Remarks_ml, Birthdate';

    function Memberinfo($db, $formsgeneration, $memberID)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->formsgeneration = $formsgeneration;
        
        DbTable::DbTable($this->db, $this->formsgeneration, '`###_Members`',
                         "MemberID = $this->memberID",
                         $this->fieldList);
    }


    function getMemberInfo($MemberID)
    {
        global $db;
        
        if ( ! empty($MemberID) )
        {
            $sql = 'SELECT Firstname, Lastname FROM `###_Addresses` WHERE Addresstype_ref = 1 AND Adr_MemberID = ' . $MemberID;
            $res = $db->Execute($sql);
            $mainArr = $res->FetchRow();
        
            return "<a href=INDEX_PHP . '?mod=members&view=Overview&MemberID=$MemberID'>$MemberID ($mainArr[Lastname], $mainArr[Firstname])</a>";
        }
        else
        {
            return '';
        }
    }

    function getRecord()
    {
        $tableArr = DbTable::getRecord();

        if ( !empty($tableArr['MainMemberID']))
        {
            debug('MAIN', "[Memberinfo|getRecord]: MainMemberID: $tableArr[MainMemberID]");
            $sql = 'SELECT Adr_MemberID, Firstname, Lastname FROM `###_Addresses` WHERE Addresstype_ref = 1 AND Adr_MemberID = ' . $tableArr['MainMemberID'];
            $res = $this->db->Execute($sql);
            $tableArr['MainMemberID'] = $res->FetchRow();
        }
        $tableArr['associatedMembers'] = getAssociateMembers($tableArr['Membertype_ref'], $this->memberID);

        debug_r('MAIN',  $tableArr , "[Memberinfo|getRecord]: MEMBERINFO:");
        return $tableArr;
    }
}
?>
