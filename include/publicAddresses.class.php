<?php
/**
 * PublicAddresses class
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
require_once('include/dblist.class.php');

/**
* The Clubdata publicAddresses class
*
* @author Franz Domes <franz.domes@gmx.de>
* @version $Revision: 1.2 $
* @package Clubdata
*/
class publicAddresses extends DbList {

    var $db;
    var $fullList;
    var $formsgeneration;

    function publicAddresses($db, $formsgeneration, $fullList = false, $options=array())
    {
        $this->db = $db;
        $this->fullList = $fullList;
        $this->formsgeneration = $formsgeneration;

        DbList::DbList($db, "publicAddresses", $options);
    }

    function createPublicAddressesByList($mlist)
    {
        global $APerr;

        $adrObj = new Addresses($this->db,$this->formsgeneration);

        $query = $adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID',
                                                    '`###_Members`.MemberID');
        $addresstypes = $adrObj->getAddresstypes();
//         echo "QUERY: $query<PRE>";print_r($addresstypes); echo "</PRE>";
        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $adrID = $addresstypes[$i]['id'];
            $adrObj->setAddressType($adrID);
            $adrColNames[] = $adrObj->getFieldList("`Addresses_$adrID`", array("'' AS Mailingtypes"));
        }
        foreach (array('`###_Members`', '`###_Members_Attributes`') as $table)
        {
            $dbTableObj = new DBTable($this->db,$this->formsgeneration, $table, '1');
            $adrColNames[] = $dbTableObj->getFieldList($table, array('MemberID'));
        }

        $tmpCols = $mlist->getConfig("cols");
        $mlist->setConfig("cols", join(',', $adrColNames));

        // Sort PDF by lastname
        $mlist->setConfig("sort", "`###_Addresses_1`.Lastname");
        //     print("<PRE>");print_r($mlist);print("</PRE>");
        $sql = $mlist->prepareSQL();
    }


    function createPublicAddressesByCond($cond)
    {
        global $APerr;

        $adrObj = new Addresses($this->db,$this->formsgeneration);

        if ( $this->fullList === true )
        {
            $query = $adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID LEFT JOIN `###_Membertype` ON `###_Members`.Membertype_ref = `###_Membertype`.id',
                                                    '`###_Members`.MemberID');
        }
        else
        {
            $query = $adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID LEFT JOIN `###_Membertype` ON `###_Members`.Membertype_ref = `###_Membertype`.id',
                                                    '`###_Members`.MemberID');
        }
        //          echo "<PRE>";print_r($addresstypes); echo "</PRE>";

        $addresstypes = $adrObj->getAddresstypes();
        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $adrID = $addresstypes[$i]['id'];

            // If not Fullview, show only Privat and/or Firm data
            if ( $this->fullList == true || $adrID == 1 || $adrID == 2 )
            {
                $adrObj->setAddressType($adrID);
                $tmpColNames = $adrObj->getFieldList("`Addresses_$adrID`", array("'' AS Mailingtypes"));

                // Modify columns to only print allowed information
                // Preserve allway Salutation, Title, Firstname and Lastname
                //    of private Address, as it is allways needed
                $tmpColNames = preg_replace_callback("/(Addresses_($adrID)\.\w+)/",
                                             create_function(
                                                    // single quotes are essential here,
                                                    // or alternative escape all $ as \$
                                                    '$matches',
                                                    'return ( (
                                                                stristr($matches[1], "Addresses_1.Salutation_ref") === false &&
                                                                  stristr($matches[1], "Addresses_1.Title") === false &&
                                                                  stristr($matches[1], "Addresses_1.Firstname") === false &&
                                                                  stristr($matches[1], "Addresses_1.Lastname") === false
                                                              )
                                                              ?
                                                                  "IF(InfoGiveOut_ref <> " . ($matches[2] + 1) . ", $matches[1], \'\')"
                                                              :
                                                                  $matches[0]);'
                                              ),
                                            $tmpColNames);
                $adrColNames[] = $tmpColNames;
            }
        }

        if ( $this->fullList === true )
        {
            foreach (array('`###_Members`') as $table)
            {
                $dbTableObj = new DBTable($this->db,$this->formsgeneration, $table, '1');
                $adrColNames[] = $dbTableObj->getFieldList($table, array('MemberID'));
            }
        }

        $sql = "SELECT DISTINCT " . join(',', $adrColNames) .
                " FROM $query WHERE `###_Members`.InfoGiveOut_ref > 0 AND ($cond)";
//                " ORDER BY Addresses_1.Lastname";

        $this->setSQL($sql);
    }

    /**
     * handles sort paramter to correct database column from table%column to table.column
     *
     * @see include/Listing::setConfig()
     */
    function setConfig($name, $val, $idx = NULL)
    {
      if ( $name == 'sort' && !empty($val))
      {
        $tmpColArr = unformatColumn($val);
        $val = (empty($tmpColArr['table']) ? "`$tmpColArr[column]`" : "`$tmpColArr[table]`.`$tmpColArr[column]`");
      }
      return parent::setConfig($name, $val, $idx);
    }

}