<?php
/**
 * Clubdata Conference Subscription Module
 *
 * Contains classes to subscribe to Conferences.
 *
 * @package Conferences
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/*
    conferences.php: Module for managing conferences
    Copyright (C) 2003 Franz Domes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as publishedby
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/

/**
 *
 */
require_once("include/conferences.class.php");
require_once("include/dbtable.class.php");
require_once("include/subscription.class.php");

/**
 * Class to subscribe to a conference
 *
 * @package Conferences
 */
class vSubscribe {
    var $memberID;
    var $db;
    var $conferenceObj;
    var $tableObj;
    var $smarty;
    var $formsgeneration;

    var $subscriptionID;

    /**
     * Database row with information about actual subscription
     *
     * @var array
     */
    var $subscriptionRow;

    function vSubscribe($db, $memberID, $conferenceObj, $initView, $smarty, $formsgeneration)
    {
        global $APerr;

        debug_r('M_CONFERENCES', $conferenceObj, "[Conferences, vSubscribe, vSubscribe], MemberID: $memberID, initView: $initView, conferenceObj:");
        $this->db = $db;
        $this->memberID = $memberID;
        $this->conferenceObj = $conferenceObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

//         $this->conferenceObj->editRecord();

        $this->subscriptionID = getGlobVar('SubscriptionID', '::number::', 'PG');
        debug('M_CONFERENCES', "[Conferences, vSubscribe, vSubscribe], this->subscriptionID: {$this->subscriptionID}");

        if ( empty($this->subscriptionID) )
        {
          debug('M_CONFERENCES', "[Conferences, vSubscribe, vSubscribe], conferenceObj->conferenceID: {$conferenceObj->conferenceID}");
          if ( !empty($conferenceObj->conferenceID) )
          {
            $sql = "SELECT SubscriptionID, Conferences_ref, NumPersons FROM `###_Members_Conferences`
                     WHERE MemberID = {$this->memberID}
                       AND Conferences_ref = {$conferenceObj->conferenceID}";

            $this->subscriptionRow = $this->db->GetRow($sql);
            if ( $this->subscriptionRow === false )
            {
              $APerr->setFatal(sprintf(lang('Error in finding Subscription for conference %s'),$conferenceObj->conferenceID ));
            }
            debug_r('M_CONFERENCES', $this->subscriptionRow, "[Conferences, vSubscribe, vSubscribe], subscriptionRow:");
          }
          $this->subscriptionID = $this->subscriptionRow['SubscriptionID'];
        }
        else
        {
          $sql = "SELECT SubscriptionID, Conferences_ref, NumPersons FROM `###_Members_Conferences`
                   WHERE SubscriptionID = {$this->subscriptionID}";
          $this->subscriptionRow = $this->db->GetRow($sql);
          if ( $this->subscriptionRow === false )
          {
            debug('M_CONFERENCES',"Error in finding Subscription for subscription {$this->subscriptionID}: " . $this->db->ErrorMsg());
            $APerr->setFatal(sprintf(lang('Error in finding Subscription for subscription %s'),$this->subscriptionID));
          }
        }
        $this->subscriptionObj = new Subscription($this->db, $this->formsgeneration, array('SUBSCRIPTION_ID' => $this->subscriptionID));
    }

    function getSmartyTemplate()
    {
        return 'conferences/v_Subscribe.inc.tpl';
    }

    function setSmartyValues()
    {
        $listArr = array('' => '') + (array)Subscription::getConferenceSelectionArr($this->db);
//         print_r($listArr);
        $this->smarty->assign_by_ref("subscription", $listArr);
        $this->smarty->assign('SubscriptionID', $this->subscriptionID);
        $this->smarty->assign('subscriptionSelected', $this->subscriptionRow['Conferences_ref']);
        $this->smarty->assign('numPersons', $this->subscriptionRow['NumPersons']);
    }

    function doAction($action)
    {
      global $APerr;
      $retVal = false;

//         return $this->conferenceObj->updateRecord();
      switch($action)
      {
        case 'SUBSCRIBE':

          $numPersons = getGlobVar('numPart', '::number::', 'PG');

          if ( !empty($this->subscriptionID) )
          {
            $this->subscriptionObj->updateRecord($this->memberID, $this->conferenceObj->conferenceID, $numPersons);
          }
          else
          {
            $this->subscriptionObj->insertRecord($this->memberID, $this->conferenceObj->conferenceID, $numPersons);
          }
          header('Location: ' . INDEX_PHP . "?mod=members&view=Conferences");
          exit;
          break;
      }
      return $retVal;
    }

    function getHeadTxt()
    {
        return lang("Subscribe conference");
    }

}
?>
