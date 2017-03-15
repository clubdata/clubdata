<?php
/**
 * Subscription class
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
class Subscription extends DbTable
{

  var $db;
  var $memberID;
  var $subscriptionID;
  var $formsgeneration;

  var $fieldList = 'SubscriptionID, MemberID, Conferences_ref, SubscriptionsID, Firstname, Lastname, FirmName, NumPersons';

  /**
   * Constructor Subscription
   *
   * This constructor creates an instance of the class Subscription
   *
   * @param object $db Databaseobject
   * @param object $formsgeneration Object of Formsgeneration
   * @param array $selectionArr Associative Array with either "MemberID" or "SubscriptionID" as keys and the appropriate IDs as values or as comma separated list
   */
  function Subscription($db, $formsgeneration, $selectionArr = array())
  {
      debug_r('SUBSCRIPTION', $selectionArr, '[Class Subscriptions, Subscriptions] selectionArr=');

      $this->db = $db;
      $this->formsgeneration = $formsgeneration;
      if ( isset($selectionArr['MemberID']) )
      {
        $this->setSubscriptionByMemberID($selectionArr['MemberID']);
      }
      elseif ( isset($selectionArr['SubscriptionID']) )
      {
        $this->setSubscriptionBySubsciptionID($selectionArr['SubscriptionID']);
      }
      else
      {
        // Set pseudo table whithout any selected entry (1=0) to initialize tablename and fieldlist
        parent::DbTable($this->db, $this->formsgeneration, '`###_Subscription`',"1=0",$this->fieldList);
      }

  }

  function setSubscriptionsBySubscriptionID($subscriptionID)
  {
    $this->subscriptionID = $subscriptionID;

    if ( empty($this->subscriptionID) )
    {
      return false;
    }

    if ( is_array($this->subscriptionID) )
    {
      $where = "id IN (" . join(',', $this->subscriptionID) . ")";
    }
    else
    {
      $where = "id IN (" . $this->subscriptionID . ")";
    }

    parent::DbTable($this->db, $this->formsgeneration, '`###_Subscriptions`',
                     $where,
                     $this->fieldList);
    return true;
  }

  function setSubscriptionsByMemberID($memberID)
  {
    $this->memberID = $memberID;
    if ( empty($this->memberID) )
    {
      return false;
    }

    $sql = "SELECT id from `###_Subscriptions` WHERE MemberID = $memberID";
    $idArr = $this->db->GetCol($sql);
    debug_r('M_CONFERENCES', $idArr, "[Class Subscriptions: setSubscriptionsByMemberID] SQL: $sql, COUNT: " . count($idArr));
    if ( $idArr === false && $this->db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        return false;
    }
    else
    {
      $this->subscriptionID = $idArr;

      DbTable::DbTable($this->db, $this->formsgeneration, '`###_Subscriptions`',
                       "MemberID IN ({$this->memberID})",
                       $this->fieldList);
      return true;
    }
  }

  function getSubscriptionIDsAsList()
  {
    if ( is_array($this->subscriptionID) )
    {
      return join(',', $this->subscriptionID);
    }
    else
    {
      return $this->subscriptionID;
    }
  }

  function getSubscriptionIDCount()
  {
    if ( empty($this->subscriptionID) )
    {
      return 0;
    }
    elseif ( is_array($this->subscriptionID) )
    {
      return count($this->subscriptionID);
    }
    else
    {
      return 1;
    }
  }

  function insertRecord($memberID, $conferencesID, $numPersons)
  {
    global $APerr;

    $insArr = array('MemberID' => $memberID,
                  'Conferences_ref' => $conferencesID,
                  'NumPersons' => $numPersons

                 );

    if ( ($retVal = $this->db->AutoExecute('###_Members_Conferences', $insArr, 'INSERT', false, true, false)) === false )
    {
      $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
    }
  }

  function updateRecord($memberID, $conferencesID, $numPersons)
  {
    global $APerr;

    $insArr = array('MemberID' => $memberID,
                  'Conferences_ref' => $conferencesID,
                  'NumPersons' => $numPersons

                 );

    if ( is_array($this->subscriptionID) )
    {
      $where = "id IN (" . join(',', $this->subscriptionID) . ")";
    }
    else
    {
      $where = "id IN (" . $this->subscriptionID . ")";
    }

    if ( ($retVal = $this->db->AutoExecute('###_Members_Conferences', $insArr, 'UPDATE', $where, true, false)) === false )
    {
      $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
    }
  }

  function deleteRecord()
  {
    global $APerr;

    if ( is_array($this->subscriptionID) )
    {
      $where = "id IN (" . join(',', $this->subscriptionID) . ")";
    }
    else
    {
      $where = "id IN (" . $this->subscriptionID . ")";
    }

    $sql = "DELETE FROM `###_Members_Conferences` $where";
    if ( ($retVal = $this->db->Execute($sql)) === false )
    {
      $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"XSQL: $sql");
    }
  }

  /*********************************************************************************************************************
   *                                   STATIC FUNCTIONS
   ********************************************************************************************************************/
  public static function getConferenceSelectionArr($db, $onlyActive = true)
  {
    global $APerr;

    if ( $onlyActive == true )
    {
      $where = "WHERE Active_yn = 1";
    }

    $sql = "SELECT * FROM `###_Conferences` $where";

    if ( ($confArr = $db->GetAll($sql)) === false )
    {
        $APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
    }
    debug_r('M_CONFERENCES', $confArr, "[Class Subscriptions: getSubscriptionSelectionArr] SQL: $sql, confArr");

    foreach ( $confArr as $line )
    {
      $confSelectArr[$line['id']] = getDescriptionTxt($line);
    }
    debug_r('M_CONFERENCES', $confSelectArr, "[Class Subscriptions: getSubscriptionSelectionArr] SQL: $sql, confSelectArr");

    return $confSelectArr;
  }

}
?>