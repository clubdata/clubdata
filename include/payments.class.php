<?php
/**
 * Payments class
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
class Payments extends DbTable {

  var $db;
  var $memberID;
  var $paymentID;
  var $formsgeneration;

  var $fieldList = 'id,MemberID,InvoiceNumber,Period,Amount,Paydate,Paymode_ref,Checknumber,Remarks,Paytype_ref';

  function Payments($db, $formsgeneration, $selectionArr = array())
  {
      debug_r('PAYMENTS', $selectionArr, '[Class Payments, Payments] selectionArr=');
      $this->db = $db;
      $this->memberID = $memberID;
      $this->formsgeneration = $formsgeneration;

      if ( isset($selectionArr['MemberID']) )
      {
        $this->setPaymentsByMemberID($selectionArr['MemberID']);
      }
      elseif ( isset($selectionArr['PaymentID']) )
      {
        $this->setPaymentsByPaymentID($selectionArr['PaymentID']);
      }
      else
      {
        // Set pseudo table whithout any selected entry (1=0) to initialize tablename and fieldlist
        parent::DbTable($this->db, $this->formsgeneration, '`###_Payments`',"1=0",$this->fieldList);
      }
  }


  function setPaymentsByPaymentID($paymentID)
  {
    $this->paymentID = $paymentID;

    if ( empty($this->paymentID) )
    {
      return false;
    }

    if ( is_array($this->paymentID) )
    {
      $where = "id IN (" . join(',', $this->paymentID) . ")";
    }
    else
    {
      $where = "id IN (" . $this->paymentID . ")";
    }

    parent::DbTable($this->db, $this->formsgeneration, '`###_Payments`',
                     $where,
                     $this->fieldList);
    return true;
  }

  function setPaymentsByMemberID($memberID)
  {
    $this->memberID = $memberID;
    if ( empty($this->memberID) )
    {
      return false;
    }

    $sql = "SELECT id from `###_Payments` WHERE MemberID = $memberID";
    $idArr = $this->db->GetCol($sql);
    debug_r('PAYMENTS', $idArr, "[Class Payments: setPymentsByMemberID] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        return false;
    }
    else
    {
      $this->paymentID = $idArr;

      DbTable::DbTable($this->db, $this->formsgeneration, '`###_Payments`',
                       "MemberID IN ({$this->memberID})",
                       $this->fieldList);
      return true;
    }
  }

  function getPaymentIDsAsList()
  {
    if ( is_array($this->paymentID) )
    {
      return join(',', $this->paymentID);
    }
    else
    {
      return $this->paymentID;
    }
  }

  function getPaymentIDCount()
  {
    if ( empty($this->paymentID) )
    {
      return 0;
    }
    elseif ( is_array($this->paymentID) )
    {
      return count($this->paymentID);
    }
    else
    {
      return 1;
    }
  }

  function getPaymentsForInvoice($invoiceNumber)
  {
    global $APerr;

    if ( empty($invoiceNumber) )
    {
      return false;
    }

    $idArr = array();
    $sql = "SELECT id FROM `###_Payments` WHERE InvoiceNumber = '{$this->invoiceNumber}'";
    $idArr = $this->db->GetCol($sql);
    debug_r('PAYMENTS', $idArr, "[Class Payments: getPaymentsForInvoice] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
    }
    else
    {
        if( empty($idArr) || empty($idArr[0]) )
        {
          //Give up, enough is enough !!
          $APerr->setFatal(sprintf(lang('No payments found for invoice number %s'), $this->invoiceNumber));
        }
        else
        {
          $this->setPaymentsByPaymentID($idArr);
        }
        return $idArr;
    }
  }

  function insertRecord($presetVals = Array())
  {
    debug('PAYMENTS', '[Class Payments, insertRecord]');
    $this->setWhere("1 = 0");
    $insertId = parent::insertRecord();

    if ( !empty($insertId) )
    {
      $this->setPaymentsByPaymentID($insertId);
      logEntry("INSERT", $sql);
    }

    return $insertId;
  }

  function deleteRecord()
  {
    global $APerr;
    
    $sql = "DELETE FROM `###_Payments` WHERE id IN (" . $this->getPaymentIDsAsList() . ")";
    $retVal = $this->db->Execute($sql);
    if ( $retVal === false && $this->db->ErrorNo() != 0 )
    {
      $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
    }
  }
}
