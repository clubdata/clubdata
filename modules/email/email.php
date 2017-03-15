<?php
/**
 * Clubdata Email Modules
 *
 * Contains the classes of the main menu
 *
 * @package Email
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */
/*
    email.php: Module for managing email
    Copyright (C) 2003 Franz Domes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
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
require_once("include/function.php");
require_once("include/cdbase.class.php");
require_once("include/dblist.class.php");

/**
 * Module class to send emails
 *
 * @package Email
 */
class cdEmail extends CdBase {
  var $memberID;
  var $mailID;
	var $mailingType;

  function cdEmail()
  {
      global $APerr;

      CdBase::CdBase();

      $view = getGlobVar("view",
                          "Submit|Create|Help",
                          "PGS");

      // get List id (if any);
      $this->clListId = getGlobVar('cllist_id',"::text::");

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
        $this->memberID = $memberlist->getSelectedRowIds();
        debug_r('M_MAIL', $this->memberID, "[EMAIL: EMAIL]: MemberID");
      }

      // id of mail to resend (if any)
      $this->mailID = getGlobVar("id","::number::");

      if ( !empty($this->mailID) )
      {
          // Get Mailingtype from database
          $sql = "SELECT EmailEmailtype
                    FROM `###_Emails`
                   WHERE `###_Emails`.id = $this->mailID";

          $this->mailingType = $this->db->GetOne($sql);
          if ( $this->mailingType === false )
          {
              $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
          }
      }
      else
      {
          // Which type of mailing is done
          $this->mailingType = getGlobVar('Mailingtype','::number::');
      }
      $tmpMailtypeArr = getOptionArray('Mailingtypes');

      $this->mailingTypeName = isset($tmpMailtypeArr[$this->mailingType]) ?
                                     $tmpMailtypeArr[$this->mailingType] : '';
      $this->setAktView($view);

      return true;
  }

  function getDefaultView()
  {
      return 'Create';
  }

  function getModuleName()
  {
      return "email";
  }

/**/
  function getModulePerm($action = "")
  {
      $retVal = false;
      if ( !isLoggedIn() /* || getClubUserInfo("MemberOnly") === true */ )
      {
//            $this->setMemberID(getClubUserInfo("MemberID"));
          $retVal = false;
      }
      else
      {
          if ( ! getUserType("Create", "Email") )
          {
              $retVal = false;
          }
          else
          {
//                 $this->setMemberID();
              $retVal = true;
          }
      }
      if ( $retVal == true )
      {
          $viewObjName = "v" . $this->view;
          $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->mailID, $this->mailingType, $this->smarty, $this->formsgeneration);
      }
      return $retVal;
  }

  function setMemberID($memberID = "")
  {
      $this->memberID = empty($memberID) ? getGlobVar("MemberID","::number::") : $memberID;

//                  echo "MEMBERID: " . $this->memberID . "<BR>";
      $_SESSION["MemberID"] = $this->memberID;
//                 echo "MEMBERID: " . $this->memberID . "<BR>";


  }

  function getTabulators()
  {
      $la = array();

      return $la;
  }

  function getNavigationElements()
  {
      switch ( $this->view )
      {
          case "Create":
          	if ( !isMember() )
          	{
              $this->buttons->AddInput(array(
                      "TYPE"=>"submit",
                      "ID"=>"Submit",
                      "NAME"=>"Submit",
                      "VALUE"=>lang("Send email"),
                      "CLASS"=>"BUTTON",
                      "ONCLICK"=>"doAction('email','Submit','SENDMAIL');",
                      "SubForm"=>"buttonbar"
              ));
          	}
          break;
      }
  }

  function doAction($action)
  {
      $retCode = $this->viewObj->doAction($action);
      if ( $action == "SUBMIT" )
      {
          $insertID = $retCode;
          if ( !empty($insertID) )
          {
              $this->mailID = $insertID;
              $this->setAktView("Detail");
              $this->getModulePerm();
          }
      }
  }

  function getHeaderText()
  {
    global $APerr;

    $headArr = array();

  	$headArr[0] = (!empty($this->mailingTypeName) ? ($this->mailingTypeName . "-") : '') . lang("Email");
    return $headArr;
  }


  function displayMainSection()
  {
      echo "<INPUT TYPE=\"HIDDEN\" NAME=\"MemberID\" VALUE=\"$this->memberID\">";
      echo "<INPUT TYPE=\"HIDDEN\" NAME=\"id\" VALUE=\"$this->mailID\">\n";

      if ( is_object($this->viewObj) )
      {
          return $this->viewObj->displayView();
      }
  }

}
?>
