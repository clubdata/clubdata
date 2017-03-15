<?php
/**
 * Clubdata Conferences Modules
 *
 * Contains classes to administer conferences in Clubdata.
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
require_once('include/function.php');
require_once('include/cdbase.class.php');
require_once('include/conferences.class.php');

/**
 * Module class to administer conferences in Clubdata
 *
 * @package Conferences
 */
class cdConferences extends CdBase {

  /**
   * @var integer ID of the conference
   */
  var $conferenceID;

  /**
   * MemberID of the member which should be associated with a conference
   *
   * @var integer
   */
  var $memberID;

  /**
   * @todo not yet used
   */
  var $invoiceNumber;

  /**
   * @var integer 1 if list has to be (re-)created, 0 (or unset) if list can be reused
   */
  var $initView;

  /**
   * @var object Object of Conference class
   */
  var $conferenceObj;
     
  function cdConferences()
  {
      global $APerr;

      CdBase::CdBase();

//           phpinfo(INFO_VARIABLES);

      $view = getGlobVar('view',
                          'Conferences|Subscribe|List|Detail|Edit|Add|Help',
                          'PGS');

      $this->conferenceObj = new Conferences($this->db, $this->formsgeneration);

      $this->memberID = getGlobVar('MemberID','::number::', 'PG');
//         echo "MemberID = $this->memberID<BR>";

      $this->initView = getGlobVar('InitView','0|1', 'PG');

      $conferenceID = getGlobVar('ConferenceID','::numberlist::');
      if ( empty($conferenceID) )
      {
        $conferenceID = getGlobVar('id','::numberlist::');
      }

      if ( !empty($conferenceID) )
      {
        debug('M_CONFERENCES', "[CONFERENCE, cdConference] setConferencesByConferenceID($conferenceID)");
        $this->conferenceObj->setConferencesByConferenceID($conferenceID);
      }
      elseif ( !empty($invoiceNumber) )
      {
        if ( $view != 'Add' )
        {
          debug('M_CONFERENCES', "[CONFERENCE, cdConference] getConferencesForInvoice($invoiceNumber)");
          $conferenceID = $this->conferenceObj->getConferencesForInvoice($invoiceNumber);

          if ( $conferenceID === false || empty($conferenceID) )
          {
            //Give up, enough is enough !!
            $APerr->setFatal(sprintf(lang('No conferences found for invoice number %s'), $this->invoiceNumber));
          }
        }
      }
      else
      {
          $conferenceID = getGlobVar('ConferenceID','::numberlist::','S');
        debug('M_CONFERENCES', "[CONFERENCE, cdConference] ConferenceID: setConferencesByConferenceID($conferenceID)");
          $this->conferenceObj->setConferencesByConferenceID($conferenceID);
      }

      debug('M_CONFERENCES', "[CONFERENCES, Conferences] CONFERENCEID: $conferenceID");

      // Save ConferenceID only if a conference id is expected
      // e.g. not with view == ADD
      if ( $view != 'Add' && $view != 'List'  && $view != 'Subscribe')
      {
        $_SESSION['ConferenceID'] = $this->conferenceObj->getConferenceIDsAsList();
      }

      // Check if single conferenceID or a list of conferenceIDs (comma separated)
      // If list, force to show List tab if detail or edit should be selected
      if ( $this->conferenceObj->getConferenceIDCount() > 1 && ($view == 'Edit' || $view == 'Detail') )
      {
          $view = 'List';
      }
      $this->setAktView($view);

      return true;
  }

  function getDefaultView()
  {
      return 'List';
  }

  function getModuleName()
  {
      return 'conferences';
  }

/**/
  function getModulePerm($action = '')
  {
  	if ( !isLoggedIn() )
  	{
  		return false;
  	}
      // Calculate Authorization constant from action string:
      // 'VIEW' => VIEW (3), etc.
      if ( $action == 'EXCEL' ) $action = 'VIEW';
//         echo ("\$userRight = " . (empty($action) ? 'VIEW' : strtoupper("'".$action."'")) . ';');
//         eval ("\$userRight = " . (empty($action) ? 'VIEW' : strtoupper("'".$action."'")) . ';');
      $userRight = (empty($action) ? 'VIEW' :
                      /* ELSE */ (defined($action) ? constant($action) :
                        /* ELSE */ ($action == 'SUBSCRIBE' ? UPDATE :
                          /* ELSE */ $action)));

//          echo "USERRIGHT: $userRight<BR>";

      if ( getClubUserInfo('MemberOnly') === true )
      {
          $retVal = ($this->view == 'Detail') ? true : false;
      }
      elseif ( $this->view == 'Subscribe' )
      {
          $retVal = getUserType($userRight, 'Members');
      }
      else
      {
          $retVal = getUserType($userRight, 'Conferences');
      }

      if ( $retVal == true )
      {
          $viewObjName = 'v' . $this->view;
          $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->conferenceObj, $this->initView, $this->smarty, $this->formsgeneration);
      }
      return $retVal;
  }

  function setMemberID($memberID = '')
  {
      return true;
  }

  function getTabulators()
  {
      $la = array();
      return $la;

      if ( $this->view != 'List' && $this->conferenceObj->getConferenceIDCount() == 1 )
      {
          $la['Detail'] = lang('Detail');

          if ( getUserType(UPDATE, 'Conferences') )
          {
              $la['Edit'] = lang('Edit');
          }
      }
      else
      {
              $la['List'] = lang('List');
      }
      if ( getUserType(INSERT, 'Conferences') )
      {
          $la['Add'] = lang('Add');
      }
      $la['Help'] = lang('Help');
      return $la;
  }

  function getNavigationElements()
  {
      $cols = array();

      // No buttons in menue view !!
      if ( $this->view == 'Conferences' )
      {
        return;
      }

      if ( $this->view == 'List' )
      {
              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit",
                      "NAME"=>"Submit",
                      "VALUE"=>lang("Select all"),
                      "CLASS"=>"BUTTON",
                      "ONCLICK"=>"doAction('conferences','$this->view','SELECTALL');",
                      "SubForm"=>"buttonbar"
              ));

              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit_1",
                      "NAME"=>"Submit_1",
                      "VALUE"=>lang("Deselect all"),
                      "CLASS"=>"BUTTON",
                      "ONCLICK"=>"doAction('conferences','$this->view','DESELECTALL');",
                      "SubForm"=>"buttonbar"
              ));
              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit_Excel",
                      "NAME"=>"Submit_Excel",
                      "VALUE"=>lang("Export to Excel"),
                      "CLASS"=>"BUTTON",
                      "ONCLICK"=>"doAction('conferences','$this->view','EXCEL');",
                      "SubForm"=>"buttonbar"
              ));
      }
      if ( $this->view != 'List' && $this->view != 'Detail' && $this->view != 'Help')
      {
          if ( $this->view == 'Edit' && getUserType(UPDATE, 'Conferences') )
          {
              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit_Update",
                      "NAME"=>"Submit_Update",
                      "VALUE"=>lang("Update entry"),
                      "CLASS"=>"BUTTON",
                      "ONCLICK"=>"doAction('conferences','$this->view','UPDATE');",
                      "SubForm"=>"buttonbar"
              ));
          }
          if ( $this->view == 'Add' && getUserType(INSERT, 'Conferences') )
          {
              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit_Insert",
                      "NAME"=>"Submit_Insert",
                      "VALUE"=>lang("Insert entry"),
                      "CLASS"=>"BUTTON",
//                         "ONCLICK"=>"doAction('conferences','$this->view','INSERT');",
                      "ONCLICK"=>"doAction('conferences','$this->view','INSERT');",
                      "SubForm"=>"buttonbar"
              ));
          }

          if ( $this->view == 'Subscribe' && getUserType(UPDATE, 'Member') )
          {
              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit_Subscribe",
                      "NAME"=>"Submit_Subscribe",
                      "VALUE"=>lang("Subscribe to conference"),
                      "CLASS"=>"BUTTON",
//                         "ONCLICK"=>"doAction('conferences','$this->view','INSERT');",
                      "ONCLICK"=>"doAction('conferences','$this->view','SUBSCRIBE');",
                      "SubForm"=>"buttonbar"
              ));
          }

          $this->buttons->AddInput(array(
                      "TYPE"=>"button",
                      "ID"=>"ConferencesReset",
                      "NAME"=>"ConferencesReset",
                      "CLASS"=>"BUTTON",
                      "VALUE"=>lang("Reset"),
                      "ONCLICK"=>"reset();",
                      "SubForm"=>"buttonbar"
              ));

/*            array_push($cols,array ( 'type' => 'reset',
                                    'name' => 'MemberReset',
                                    'value' => '',
                                    'label' => lang('Reset'),
                        ));
 */
      }

      if ( !empty($this->memberID) )
      {
             $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit_ReturnToMember",
                      "NAME"=>"Submit_ReturnToMember",
                      "VALUE"=>lang("Return to member"),
                      "CLASS"=>"BUTTON",
                      "ONCLICK"=>"doSubmit('members','Conferences');",
                      "SubForm"=>"buttonbar"
              ));
      }
      return $cols;
  }

  function doAction($action)
  {
      debug('M_CONFERENCES', "[Conferences, doAction] Action: $action");
      $retCode = $this->viewObj->doAction($action);
      if ( $retCode === true )
      {
        $this->setAktView('List');
        $this->getModulePerm();
      }
//          echo "RETCODE: $retCode, CONFERENCEID: $this->conferenceID<BR>";
/*        if ( $action == 'INSERT' )
        {
            $insertID = $retCode;
            if ( !empty($insertID) )
            {
                $this->conferenceID = $insertID;
                $this->setAktView('Detail');
                $this->getModulePerm();
            }
        }
*/
  }

  function getHeaderText()
  {
    global $APerr;

    $headArr = array();

    if ( method_exists($this->viewObj, 'getHeadTxt') )
    {
      $headArr[0] = $this->viewObj->getHeadTxt();
    }
    else
    {
      $headArr[0] = lang('Conferences');
    }
//        print('<PRE>');print_r($headArr);print('</PRE>');
    return $headArr;
  }

  function displayMainSection()
  {
      include('javascript/calendar.js.php');
      if ( is_object($this->viewObj) )
      {
          return $this->viewObj->displayView();
      }
  }

}
?>