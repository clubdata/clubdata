<?php
/**
 * Clubdata List Modules
 *
 * Contains classes to generate and display serveral lists
 *
 * @package List
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
if (defined('INFOLETTER_CLASS')) {
    return 0;
} else {
    define('INFOLETTER_CLASS', TRUE);
}

require_once('include/membertype_dep.php');
require_once('include/dblist.class.php');
require_once('include/addresses.class.php');
require_once('include/createPDF.class.php');

/**
 * This class generates excel address files for the german infopost manager (and other mass letter systems)
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @package List
 */
class vInfoletter
{
    /** @var name of command type to display*/
    var $command;

    /** @var current list object*/
    var $mlist;

    var $db;

    /** @var set to 1 if new view has to be initialized*/
    var $initView;

    var $selectCMD;

    var $clListId;

    var $smarty;
    var $formsgeneration;

    var $isInvoice;
    /**
    * Constructor of class List
    * @return integer Always OK
    */
    function vInfoletter($db, $command, $initView, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->command = $command;
        $this->initView = $initView;
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        // get List id (if any);
        $this->clListId = getGlobVar('cllist_id',"::text::");

        debug('LIST', "[vInfoletter: vInfoletter]: clListId: $this->clListId");

        // If no list is given, look for member id's
        if ( empty($this->clListId) )
        {
          // Array of member IDs
          $this->memberID = getGlobVar("MemberID","::number::");
        }
        else
        {
          // get all selected Members of list
          $memberlist = new DbList($this->db, $this->clListId, $this->formsgeneration, 'mailinglist');
          $this->memberID = array();
          $this->memberID = join(",",$memberlist->getSelectedRowIds());
          debug_r('LIST', $this->memberID, "[vInfoletter: vInfoletter]: MemberID");
        }

        $Countrycode = getConfigEntry($db, "Countrycode");

        // Which type of mailing is done
        $this->mailingType = getGlobVar('Mailingtype','::number::');

    }

    function generateExcel()
    {
        global $language;

        debug('LIST', "doAction: Mailingtype: $this->mailingType");
        if ( empty($this->mailingType) )
        {
            return;
        }

        $lMemberID = icT(lang("MemberID"));
        $lTitle = icT(lang("Title"));
        $lSalutation = icT(lang("Salutation"));
        $lFirstname = icT(lang("Firstname"));
        $lLastname = icT(lang("Lastname"));
        $lLetterHeadSalutation = icT(lang("Heading salutation"));
        $lLanguage = icT(lang("Language_ref"));
        $lLetterTextSalutation = icT(lang("Text salutation"));
        $lMembertype = icT(lang("Membertype_ref"));
        $lFirmName = icT(lang("Firm"));
        $lFirmDepartment = icT(lang("Department"));
        $lAddress = icT(lang("Address"));
        $lCountry = icT(lang("Country"));
        $lCountryCode = icT(lang("CountryCode"));
        $lZipCode = icT(lang("ZipCode"));
        $lTown = icT(lang("Town"));
        $lLetterPrivat = icT(lang("LetterPrivat_yn"));
        $lAddressL1 = icT(lang("AddressL1"));
        $lAddressL2 = icT(lang("AddressL2"));
        $lAddressL3 = icT(lang("AddressL3"));
        $lAddressL4 = icT(lang("AddressL4"));
        $lAddressL5 = icT(lang("AddressL5"));
        $lAddressL6 = icT(lang("AddressL6"));
        $lAddressL7 = icT(lang("AddressL7"));

        $adrObj = new Addresses($this->db, $this->formsgeneration);
        $addresstypes = $adrObj->getAddresstypes();
        $allFieldsArr = $adrObj->getAllFields();
/*
        foreach ($allFieldsArr as $val)
        {
            $tmpArr[] = "RPAD('$val',200,' ') AS $val";
        }
        $sqlCMD[] = "SELECT DISTINCT RPAD('$lMemberID',200,' ') AS MemberID," .
                    join(',', $tmpArr) .
                    ",RPAD('$lAddressL1',200,' ') AS $lAddressL1, RPAD('$lAddressL2',200,' ') AS $lAddressL2, RPAD('$lAddressL3',200,' ') AS $lAddressL3,
                    RPAD('$lAddressL4',200,' ') AS $lAddressL4, RPAD('$lAddressL5',200,' ') AS $lAddressL5, RPAD('$lAddressL6',200,' ') AS $lAddressL6,
                    RPAD('$lAddressL7',200,' ') AS $lAddressL7,  '', '', '' ";

*/
        unset($adrObj);

        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $selectCMDtmp = '1';
            debug('LIST', "[v_Infoletter, generateExcel] Adresstyp: $i, ID=" . $addresstypes[$i]['id']);
            $adrObj = new Addresses($this->db, $this->formsgeneration, $addresstypes[$i]['id']);
            $from = $adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID',
                                                '`###_Members`.`MemberID`');

            $fieldList[$i] = $adrObj->getFieldList();
            $letterFields = $adrObj->getLetterFields();

            $fieldArr = array();
            foreach ( $allFieldsArr as $val )
            {
                debug('LIST', "[displayMainSection] Adresstyp: $i, check field: $val");
                if (strpos($fieldList[$i], $val) !== false ||
                    $val == 'Addresstype_ref' ||
                    $val == 'id' )
                {
                    $fieldArr[] = formatColumn("Addresses_{$addresstypes[$i]['id']}", $val);
                }
                else
                {
                    for($k=0 ; $k < $i ; $k++)
                    {
                        if ( strpos($fieldList[$k], $val) !== false )
                        {
                            $fieldArr[] = formatColumn("Addresses_{$addresstypes[$k]['id']}", $val);
                            break;
                        }
                    }
                    if ( $k == $i )
                    {
                        $fieldArr[] = "'' AS `Addresses_{$addresstypes[$k]['id']}%$val`";
                    }
                }
            }

            unset($tmpAddrArr);

            if ( preg_match("/((`?Addresses_.`?\.)?`?Country_ref`?)/", $letterFields, $tmpAddrArr) )
            {
                $letterFields = preg_replace("/(`?Addresses_.`?\.)`?Country_ref`?/",
                                            "IF (`###_Country`.`Description_$language` = '', `###_Country`.`DESCRIPTION_UK`, `###_Country`.`DESCRIPTION_$language`)",
                                            $letterFields);
                $from .= " LEFT JOIN `###_Country` ON `###_Country`.id = $tmpAddrArr[1]";
            }

            if ( preg_match("/((`?Addresses_.`?\.)?`?Salutation_ref`?)/", $letterFields, $tmpAddrArr) )
            {
                $letterFields = preg_replace("/(`?Addresses_.\`?\.)`?Salutation_ref`?/",
                                            "`###_Salutation`.`Description`",
                                            $letterFields);
                $from .= ', `###_Salutation`';
                $selectCMDtmp .= " AND `###_Salutation`.id = $tmpAddrArr[1]";
            }

            $from .= ", `###_Addresses_Mailingtypes`";
            $selectCMDtmp .= " AND Addresses_" . $addresstypes[$i]['id'] . ".id = `###_Addresses_Mailingtypes`.AddressID
                            AND `###_Addresses_Mailingtypes`.Mailingtypes_ref IN ( $this->mailingType )
                            AND `###_Members`.MemberID IN ({$this->memberID})";

            $letterFields = join(',', $fieldArr) . ',' . $letterFields;

            if ( !empty($letterFields) )
            {
                unset($matches);
                preg_match_all("/([^,\(]+(\([^\)]*\))?[^,]+)/", $letterFields,$matches,PREG_PATTERN_ORDER);
//                 debug_r('LIST', $matches, '[displayMainSection] matches');
                $letterFields = join(',',array_pad($matches[0],28,"''"));
                eval("\$sqlCMD[] = \"SELECT `###_Members`.MemberID as `$lMemberID`,$letterFields FROM $from WHERE $selectCMDtmp\";");

            }

            unset($adrObj);
        }
        $sql = join("\nUNION \n", $sqlCMD);
        debug('LIST', "[displayMainSection] SQL: $sql");

        //echo "SQL: $sqlCMD<BR>";
        require_once("include/dblist.class.php");

        $mlist = new DbList($this->db, "memberlist");
        $infoletterList = $mlist->copyList('infoletter');

        $infoletterList->setSQL($sql);

        if ( $infoletterList->getConfig("sort") == "" || $infoletterList->getConfig("sort") == "Members.MemberID")
        {
          $infoletterList->setConfig("sort", "`Member ID`");
        }
//         debug_r('LIST', $mlist, "[displayMainSection] MLIST:");
        debug('LIST', "[displayMainSection] OB_GET_LEVEL: " . ob_get_level());
        //Ignore output made so far
        $tmpOutputContent = ob_get_contents();
        $infoletterList->exportExcel();

        //Restart output buffering
        ob_start();
        echo $tmpOutputContent;

    }

    function getSmartyTemplate()
    {
        return 'list/v_Memberlist.inc.tpl';
    }

    function setSmartyValues()
    {
//         $this->clListId->prepareRecordList($this->pageNr);
//         debug_r('SMARTY', $this->mlist, "[V_Memberlist, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('MemberList', $this->clListId);
    }


    function doAction($action)
    {
      global $APerr;

      switch ( $action )
      {
          case 'INVOICE':
            $sql = "SELECT id FROM `###_Mailingtypes` WHERE InvoiceAddr_yn <> 0";
            $idArr = $this->db->GetCol($sql);
            if ( $idArr === false )
            {
              $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            }
            $this->mailingType = (is_array($idArr) ? join(',',$idArr) : $idArr);
            $this->isInvoice = true;
            debug('LIST', "isInvoice = true");
            break;

          default:
            break;
      }
    }

    function display()
    {
      $this->generateExcel();
    }
}
?>