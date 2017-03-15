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
require_once('include/addr_mailtype.class.php');

/**
 * Addresses class to support up to 9 addresses per member
 * @package Clubdata
 */
class Addresses extends DbTable {

	var $db;
	var $addresstype;
	var $fieldList;
	var $memberID;

	var $adrTypeArr = NULL;
	var $adrMailTypeObj = NULL;
	var $addressID;

	function Addresses($db, &$formsgeneration, $addresstype = '', $memberID = '')
	{
		global $APerr;

		$this->db = $db;
		$this->memberID = $memberID;
		$this->setAddressType($addresstype);

		if ( !empty($addresstype) &&  !empty($this->memberID) )
		{
			DbTable::DbTable($this->db, $formsgeneration, '###_Addresses',
                                "Addresstype_ref = $this->addresstype AND ADR_MemberID = $this->memberID",
			$this->fieldList);

			$tmpAddressID = $this->getCol();
			if ( count($tmpAddressID) == 1 )
			{
				$this->addressID = $tmpAddressID[0];
			}
			elseif ( count($tmpAddressID) == 0 )
			{
				// Addresse of this type for this member not yet created
				$this->addressID = NULL;
			}
			else
			{
				$APerr->setFatal(__FILE__,__LINE__,lang("Invalid return of DBTable::getCol():") . count($tmpAddressID), $this->db->errormsg());
				$APerr->setFatal(__FILE__,__LINE__,"QUERY: Addresstype_ref = $this->addresstype AND ADR_MemberID = $this->memberID");
			}

		}
		else
		{
			DbTable::DbTable($this->db, $formsgeneration, '','');
			$this->addressID = NULL;
		}

		$adrMailTypeObj = new Addresses_Mailtype($db, $formsgeneration, $this->addressID);
		$this->addSubTable('Mailingtypes', $adrMailTypeObj);
	}

	function setAddressType($addresstype)
	{
		$this->addresstype = $addresstype;
		unset($this->fieldList);
		if ( !empty($this->addresstype) )
		{
			$this->fieldList = $this->getFieldList() . ", '' AS Mailingtypes";
		}
	}

	/**
	 * get information record of current addresstype
	 * @return ARRAY : Array of available descriptions
	 */
	function getAddressInfo()
	{
		global $APerr;

		$sql = "SELECT * FROM ###_Addresstype WHERE id = $this->addresstype";
		$addressInfo = $this->db->GetRow($sql);

		if ( $addressInfo === false && $this->db->ErrorNo != 0 )
		{
			$APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
		}

		return $addressInfo;
	}

	function getAllFields($table = '###_Addresses')
	{
		return $this->db->MetaColumnNames($table);
	}

	/**
	 * get fields available for current addresstype
	 * The fields used by an addresstype are defined by the table "Addresstype" and
	 * are a subset of all available fields of table "Addresses"
	 * @param  tablename: Prepend each column by tablename (default = '')
	 * @return TEXT : Comma separated list of fields
	 */
	function getFieldList($tablename = '',$exclude = array(), $include=array())
	{
		global $APerr;

		if ( empty($this->fieldList) )
		{
			$sql = "SELECT fieldList FROM `###_Addresstype` WHERE id = $this->addresstype";
			$fieldList = $this->db->GetOne($sql);
			debug('ADDRESSES',"[Addresses, getFieldList] SQL: $sql, (fieldList = $fieldList)");

			if ( $fieldList === false && $this->db->ErrorNo != 0)
			{
				$APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
			}
		}
		else
		{
			$fieldList = $this->fieldList;
			debug('ADDRESSES',"[Addresses, getFieldList] Fieldlists: ($this->fieldList = $fieldList)");
		}

		$colArr = preg_split("/\s*,\s*/", $fieldList,-1,PREG_SPLIT_NO_EMPTY);
		debug_r('ADDRESSES',$colArr,"[Addresses, getFieldList] ($fieldList) = colArr:");

		if ( !empty($exclude) || !empty($include) )
		{
			$tmpArr = array();
			foreach ($colArr as $column)
			{
				if ( in_array($column, $include) || !in_array($column, $exclude) )
				{
					$tmpArr[] = $column;
				}
			}
			$colArr = $tmpArr;
		}

		$subTableNameArr = $this->getSubTableNames();

		if ( !empty($tablename))
		{
			for ( $i=0; $i < count($colArr); $i++ )
			{
				if ( strpos($colArr[$i], 'AS') === false )
				{
					$colArr[$i] = formatColumn($tablename, $colArr[$i]);
					//                     $colArr[$i] = $tablename . '.' . $colArr[$i] . ' AS `' . $tablename . "%" . $colArr[$i] . '`';
				}
			}
		}

		foreach ( $subTableNameArr as $subTableName )
		{
			$tmpColArr = preg_grep("/\s+AS\s+$subTableName/i", $colArr);
			foreach ( $tmpColArr as $key => $val)
			{
				// FD20100925: Added ###_ to Addresses_... Alias, so that advances search works
				$colArr[$key] = "`###_Addresses_{$subTableName}_{$this->addresstype}`.`{$subTableName}_ref` AS `###_Addresses_{$subTableName}_{$this->addresstype}%{$subTableName}_ref`";
			}
		}

		$fieldList = join(',', $colArr);
		return $fieldList;
	}


	/**
	 * get fields available for current addresstype
	 * The fields used by an addresstype are defined by the table "Addresstype" and
	 * are a subset of all available fields of table "Addresses"
	 * @param  tablename: Prepend each column by tablename (default = '')
	 * @return TEXT : Comma separated list of fields
	 */
	function getLetterFields($tablename = '',$exclude = array(), $include=array())
	{
		global $APerr;

		if ( empty($this->letterFields) )
		{
			$sql = "SELECT LetterFields_ml FROM ###_Addresstype WHERE id = $this->addresstype";
			$letterFields = $this->db->GetOne($sql);

			if ( $letterFields === false )
			{
				$APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
			}
		}
		else
		{
			$letterFields = $this->letterFields;
		}

		$colArr = preg_split("/\s*,\s*/", $letterFields,-1,PREG_SPLIT_NO_EMPTY);

		$this->letterFields = join(', ', $colArr);

		return $this->letterFields;
	}

	function getAddresstypes()
	{
		global $APerr;

		if ( empty($this->adrTypeArr) )
		{
			$sql = "SELECT * FROM ###_Addresstype";
			$this->adrTypeArr = $this->db->GetAll($sql);
			if ( $this->adrTypeArr === false )
			{
				$APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
			}
		}

		return $this->adrTypeArr;
	}

	function generateAdrTableList($from, $refID)
	{
		$addresstypes = $this->getAddresstypes();

		for ( $i=0; $i < count($addresstypes) ; $i++ )
		{
			$adrID = $addresstypes[$i]['id'];
			$from .= " LEFT JOIN ###_Addresses `Addresses_$adrID`
                         ON $refID = `Addresses_$adrID`.`Adr_MemberID`
                        AND `Addresses_$adrID`.`Addresstype_ref` = $adrID
                       LEFT JOIN `###_Addresses_Mailingtypes` `###_Addresses_Mailingtypes_$adrID`
                         ON `Addresses_$adrID`.`id` = `###_Addresses_Mailingtypes_$adrID`.`AddressID`";
		}
		return $from;
	}

	function updateRecord($uploadID = '')
	{
		$uploadID = $this->memberID . "_" . $this->addresstype;
		parent::updateRecord($uploadID);
	}

	function insertRecord($presetVals = array())
	{
		$presetVals = array ('Adr_MemberID' => $this->memberID,
                             'Addresstype_ref' => $this->addresstype);

		parent::insertRecord($presetVals);
	}

	/**
	 *
	 * Function to generate SQL to get all necessary columns to address a letter
	 *
	 * @param $mailingType Type of mailing, see database table MAILINGTYPES
	 * @param $cols List or array of additional columns, which will be added to the SQL. @see $from
	 * @param $extraFrom SQL-part which defines an additional FROM-part
	 * @param $refID Name of the reference-column, which connects the $from-part to the address part
	 * @return string SQL-string
	 */
	function generateLetterAddressfields($mailingType, $cols, $extraFrom, $refID)
	{
        global $language;

        debug('ADDRESSES', "doAction: Mailingtype: $this->mailingType");
        if ( empty($mailingType) )
        {
            return;
        }

        // If $cols is an array, transform it to a comma-separated list
        if ( is_array($cols) )
        {
          $cols = join(',', $cols);
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

        $addresstypes = $this->getAddresstypes();
        $allFieldsArr = $this->getAllFields();
        $defautFrom = $this->generateAdrTableList($extraFrom,$refID);
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
        $maxLetterFieldCount = 0;
        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $adrObj = new Addresses($this->db, $this->formsgeneration, $addresstypes[$i]['id']);

            $letterFields[$i] = $adrObj->getLetterFields();
            //Split letter fields to array and take care of commas in sql functions (like CONCAT(a,b,c))
            preg_match_all("/([^,\(]+(\([^\)]*\))?[^,]+)/", $letterFields[$i],$matches,PREG_PATTERN_ORDER);
            $letterFieldsArr[$i]=$matches[0];

            $maxLetterFieldCount = max($maxLetterFieldCount, count($letterFieldsArr[$i]));

            $fieldListArr[$i] = explode(',', $adrObj->getFieldList());
            unset($adrObj);
        }

        for ( $i=0; $i < count($addresstypes) ; $i++ )
        {
            $selectCMDtmp = '1';
            debug('ADDRESSES', "[Addresses, generateLetterAddressfields] Adresstyp: $i, ID=" . $addresstypes[$i]['id']);

            $fieldArr = array();
            foreach ( $allFieldsArr as $val )
            {
                debug('ADDRESSES', "[Addresses, generateLetterAddressfields] Adresstyp: $i, check field: $val");
                if (in_array($val, $fieldListArr[$i]) ||
                    $val == 'Addresstype_ref' ||
                    $val == 'id' )
                {
                    $fieldArr[] = formatColumn("Addresses_{$addresstypes[$i]['id']}", $val);
                }
                else
             {
                  for($k=0 ; $k < count($addresstypes) ; $k++)
                  {
                      if ( in_array($val, $fieldListArr[$k]) )
                      {
                          $fieldArr[] = "'' AS `Addresses_{$addresstypes[$k]['id']}%$val`";
                          break;
                      }
                  }
                }
            }

            $from = $defautFrom;

            unset($tmpAddrArr);

            if ( preg_match("/((`?Addresses_.`?\.)?`?Country_ref`?)/", $letterFields[$i], $tmpAddrArr) )
            {
                $letterFields[$i] = preg_replace("/(`?Addresses_.`?\.)`?Country_ref`?/",
                                            "IF (`###_Country`.`Description_$language` = '', `###_Country`.`DESCRIPTION_UK`, `###_Country`.`DESCRIPTION_$language`)",
                                            $letterFields[$i]);
                $from .= " LEFT JOIN `###_Country` ON `###_Country`.id = $tmpAddrArr[1]";
            }

            if ( preg_match("/((`?Addresses_.`?\.)?`?Salutation_ref`?)/", $letterFields[$i], $tmpAddrArr) )
            {
                $letterFields[$i] = preg_replace("/(`?Addresses_.\`?\.)`?Salutation_ref`?/",
                                            "`###_Salutation`.`Description`",
                                            $letterFields[$i]);
                $from .= ', `###_Salutation`';
                $selectCMDtmp .= " AND `###_Salutation`.id = $tmpAddrArr[1]";
            }

            $from .= ", `###_Addresses_Mailingtypes`";
            $selectCMDtmp .= " AND Addresses_" . $addresstypes[$i]['id'] . ".id = `###_Addresses_Mailingtypes`.AddressID
                            AND `###_Addresses_Mailingtypes`.Mailingtypes_ref IN ( $mailingType )

                            ";

            $tmpLetterFields = join(',', $fieldArr) . ',' . $letterFields[$i];

            for ($k = count($letterFieldsArr[$i]); $k < $maxLetterFieldCount; $k++)
            {
              $addLetterField = ", '' as TMP_$k";

              for ($j = 0; j < count($addresstypes); $j++)
              {
//                print("LETTERFIELD:" . $letterFieldsArr[$j][$k] . "<BR>");
                if (isset($letterFieldsArr[$j][$k]) && preg_match("/\s+([Aa][Ss]\s+.*$)/", $letterFieldsArr[$j][$k], $matches))
                {
//                  print("<PRE>");print_r($matches);print("</PRE>");
                  $addLetterField = ", '' " . $matches[1];
                  break;
                }
              }
              $tmpLetterFields .= $addLetterField;
            }
//                print("TMPLETTERFIELD:" . $tmpLetterFields . "<BR>");

            if ( !empty($cols))
            {
              $tmpLetterFields .= ", $cols";
            }

            eval("\$sqlCMD[] = \"SELECT `###_Members`.MemberID as `$lMemberID`,$tmpLetterFields FROM $from WHERE $selectCMDtmp\";");
        }
        $sql = join("\nUNION \n", $sqlCMD);
        debug('ADDRESSES', "[Addresses, generateLetterAddressfields] SQL: $sql");

        return $sql;
	}
}
?>