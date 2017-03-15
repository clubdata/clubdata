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
require_once('include/dbtable.class.php');

/**
 * @package Clubdata
 */
class Fees extends DbTable {

  var $db;
  var $memberID;
  var $feeID;
  var $formsgeneration;

  var $fieldList = 'id,MemberID,InvoiceNumber,InvoiceDate,DueTo,Period,Amount,Remarks,DemandLevel';

  function Fees($db, $formsgeneration, $selectionArr = array())
  {
      debug_r('FEES', $selectionArr, '[Class Fees, Fees] selectionArr=');
      $this->db = $db;
      $this->memberID = $memberID;
      $this->formsgeneration = $formsgeneration;

      if ( isset($selectionArr['MemberID']) )
      {
        $this->setFeesByMemberID($selectionArr['MemberID']);
      }
      elseif ( isset($selectionArr['FeeID']) )
      {
        $this->setFeesByFeeID($selectionArr['FeeID']);
      }
      else
      {
        // Set pseudo table whithout any selected entry (1=0) to initialize tablename and fieldlist
        parent::DbTable($this->db, $this->formsgeneration, '`###_Memberfees`',"1=0",$this->fieldList);
      }
  }


  function setFeesByFeeID($feeID)
  {
    $this->feeID = $feeID;

    if ( empty($this->feeID) )
    {
      return false;
    }

    if ( is_array($this->feeID) )
    {
      $where = "id IN (" . join(',', $this->feeID) . ")";
    }
    else
    {
      $where = "id IN (" . $this->feeID . ")";
    }

    parent::DbTable($this->db, $this->formsgeneration, '`###_Memberfees`',
                     $where,
                     $this->fieldList);
    return true;
  }

  function setFeesByMemberID($memberID)
  {
    $this->memberID = $memberID;
    if ( empty($this->memberID) )
    {
      return false;
    }

    $sql = "SELECT id from `###_Memberfees` WHERE MemberID = $memberID";
    $idArr = $this->db->GetCol($sql);
    debug_r('FEES', $idArr, "[Class Fees: setFeesByMemberID] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        return false;
    }
    else
    {
      $this->feeID = $idArr;

      DbTable::DbTable($this->db, $this->formsgeneration, '`###_Memberfees`',
                       "MemberID IN ({$this->memberID})",
                       $this->fieldList);
      return true;
    }
  }

  function setFeesByInvoiceNumber($invoiceNumber)
  {
    $this->invoiceNumber = $invoiceNumber;
    if ( empty($this->invoiceNumber) )
    {
      return false;
    }

    $sql = "SELECT id from `###_Memberfees` WHERE InvoiceNumber = '{$this->invoiceNumber}'";
    $idArr = $this->db->GetCol($sql);
    debug_r('FEES', $idArr, "[Class Fees: setFeesByInvoiceNumber] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        return false;
    }
    else
    {
      $this->feeID = $idArr;

      DbTable::DbTable($this->db, $this->formsgeneration, '`###_Memberfees`',
                       "InvoiceNumber = '{$this->invoiceNumber}'",
                       $this->fieldList);
      return true;
    }
  }

  function setFeesByCondition($cond)
  {
    // empty $cond means all entries !!
    if ( !empty($cond) && substr($cond,0,5) != "WHERE" )
    {
      $whereCond = "WHERE $cond";
    }

    $sql = "SELECT id from `###_Memberfees` $whereCond";
    $idArr = $this->db->GetCol($sql);
    debug_r('FEES', $idArr, "[Class Fees: setFeesByCondition] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        return false;
    }
    else
    {
      $this->feeID = $idArr;

      DbTable::DbTable($this->db, $this->formsgeneration, '`###_Memberfees`',
                       "$cond",
                       $this->fieldList);
      return true;
    }
  }

  function getFeeIDsAsList()
  {
    if ( is_array($this->feeID) )
    {
      return join(',', $this->feeID);
    }
    else
    {
      return $this->feeID;
    }
  }

  function getFeeIDCount()
  {
    if ( empty($this->feeID) )
    {
      return 0;
    }
    elseif ( is_array($this->feeID) )
    {
      return count($this->feeID);
    }
    else
    {
      return 1;
    }
  }

  function getFeesForInvoice($invoiceNumber)
  {
    global $APerr;

    if ( empty($invoiceNumber) )
    {
      return false;
    }

    $idArr = array();
    $sql = "SELECT id FROM `###_Memberfees` WHERE InvoiceNumber = '{$this->invoiceNumber}'";
    $idArr = $this->db->GetCol($sql);
    debug_r('FEES', $idArr, "[Class Fees: getFeesForInvoice] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
    }
    else
    {
        if( empty($idArr) || empty($idArr[0]) )
        {
          //Give up, enough is enough !!
          $APerr->setFatal(sprintf(lang('No fees found for invoice number %s'), $this->invoiceNumber));
        }
        else
        {
          $this->setFeesByFeeID($idArr);
        }
        return $idArr;
    }
  }

  function insertRecord($presetVals = Array())
  {
    global $APerr;

    debug('FEES', '[Class Fees, insertRecord]');
    $this->setWhere("1 = 0");
    $insertId = parent::insertRecord();

    if ( !empty($insertId) )
    {
      $this->setFeesByFeeID($insertId);
      logEntry("INSERT", $sql);
      $sql = "UPDATE `###_Configuration` SET value=value+1 WHERE id=24";
      $this->db->Execute($sql) or
              $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
}

    return $insertId;
  }

  function deleteRecord()
  {
    global $APerr;

    $sql = "DELETE FROM `###_Memberfees` WHERE id IN (" . $this->getFeeIDsAsList() . ")";
    $retVal = $this->db->Execute($sql);
    if ( $retVal === false && $this->db->ErrorNo() != 0 )
    {
      $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
    }
  }
}
