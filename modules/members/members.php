<?php
/**
 * Clubdata Member Modules
 *
 * Contains the classes to manipulate member entries
 * The views which are called by this class correspond to the tabs shown on the member page
 *
 * @package Members
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
if (defined('MEMBERS_LIST')) {
    return 0;
} else {
    define('MEMBERS_LIST', TRUE);
}

require_once("include/function.php");
require_once("include/membertype_dep.php");
require_once("include/cdbase.class.php");

/**
 * Module class to show and manipulate a member
 *
 * @package Members
 */
class CdMembers extends CdBase {
    var $memberID;
    var $addresstype;

    // Which tabulator belongs to which user right.
    var $viewAuthType = array('Overview' => 'Member',
                              'Addresses' => 'Member',
                              'Memberinfo' => 'Member',

                             );

    function CdMembers()
    {
        CdBase::CdBase();

        $view = getGlobVar("view",
                            "Addresses_[0-9]+|Overview|Privat|Firm|Memberinfo|Payments|Fees|Emails|Conferences|Help",
                            "PGS");

        $_SESSION['navigator_menu'] = 'MEMBERS';
        $this->setAktView($view);
    }

    function getDefaultView()
    {
        return 'Overview';
    }

    /**
    * get name of Module
    * @return TEXT : Name of module
    */
    function getModuleName()
    {
        return "members";
    }

    function getModulePerm($action = '')
    {
    	if ( !isLoggedIn() )
    	{
    		return false;
    	}

        if ( $this->view == 'Help' )
        {
          return true;
        }

        // If action is empty set to VIEW, else check if the action exists as constant value. If so use it.
        // If not fall back to uppercase ACTION.
        $userRight = empty($action) ? VIEW : (defined($action) ? constant($action) : strtoupper($action));

        $retVal = false;
        if ( getClubUserInfo("MemberOnly") === true )
        {
            $this->setMemberID(getClubUserInfo("MemberID"));
            $retVal = true;
        }
        else
        {

            debug('MAIN',"[Members, getModulePerm] Action: $action, userRight: $userRight, view: {$this->view}");
//             $authTab = isset($this->viewAuthType[$tab]) ? $this->viewAuthType[$tab]: ucfirst($tab);
//             $ret = getUserType($userRight, $this->viewAuthType[$this->view]);

            /*
             * If action is INSERT or DELETE check against rights on "Member".
             * all other actions (include VIEW) are checked against the actual VIEW to display
             * This is, because only a member can be inserted or deleted. (Independent of the actual view),
             * but views itself can neither be inserted nor deleted but only updated
             */
            if ( $action == 'INSERT' || $action == 'DELETE' )
            {
              $retVal = getUserType($userRight, 'Member');
            }
            else
            {
              $retVal = getUserType($userRight, ucfirst($this->view));
            }

            if ( $retVal )
            {
                $this->setMemberID();
            }
        }
        return $retVal;
    }

    function setMemberID($memberID = "")
    {
//         phpinfo();
        $memberIdWithPrefix = clubdata_mysqli::replaceDBprefix("###_Members%MemberID", DB_TABLEPREFIX);

        debug('MAIN',"setMemberID: Numbers: $memberID, " .
                getGlobVar($memberIdWithPrefix,"::number::") . ", ".
                getGlobVar("Members%MemberID","::number::") . ", ".
                getGlobVar("MemberID","::number::") . ", " .
                getGlobVar("id","::number::"));

        if ( !empty($memberID) )
        {
            $this->memberID=$memberID;
        }
        else
        {
            $tmp = getGlobVar($memberIdWithPrefix,"::number::");
            if ( !empty($tmp) )
            {
                $this->memberID=$tmp;
            }
            else
            {
              $tmp = getGlobVar("Members%MemberID","::number::");
              if ( !empty($tmp) )
              {
                  $this->memberID=$tmp;
              }
              else
              {
                  $tmp = getGlobVar("MemberID","::number::");
                  if ( !empty($tmp) )
                  {
                      $this->memberID=$tmp;
                  }
                  else
                  {
                      $this->memberID = getGlobVar("id","::number::");
                  }
              }
            }
        }
//                  echo "MEMBERID: " . $this->memberID . "<BR>";
        if ( empty($this->memberID) || !is_numeric($this->memberID) )
        {
            $this->memberID = getFirstRecord($this->db);
        }
        $_SESSION["MemberID"] = $this->memberID;
//                 echo "MEMBERID: " . $this->memberID . "<BR>";

    }
    function getTabulators()
    {
        global $APerr;

        $la = array();

        $tabs = explode(";", getConfigEntry($this->db, "TabsShown"));
        foreach($tabs as $tab)
        {
//             $authTab = isset($this->viewAuthType[$tab]) ? $this->viewAuthType[$tab]: ucfirst($tab);
            $authTab = ucfirst($tab);
            if ( $tab == 'Overview' && getUserType(VIEW, 'Overview') )
            {
              $la[$tab] = lang($tab);
            }
            elseif ( getUserType(INSERT,$authTab) ||
                    getUserType(UPDATE,$authTab) || getUserType(DELETE,$authTab) )
            {
                // Hints for translation tool:
                //lang("Overview"), lang("Privat"), lang("Firm"), lang("Memberinfo")
                //lang("Payments"), lang("Fees"), lang("Emails"), lang("Conferences")
                //lang("Help")
                if ( $tab == 'Addresses' )
                {
                    $sql = "SELECT * FROM `###_Addresstype` ORDER BY id";
                    $rs = $this->db->execute($sql);
                    if ( $rs === false )
                    {
                        $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
                    }
                    else
                    {
                        while ( $anArr = $rs->fetchRow() )
                        {
                            $adrId = $anArr['id'];
                            $txt = getDescriptionTxt($anArr);
                            $la['Addresses_' . $adrId] = lang($txt);
                        }
                    }
                }
                else
                {
                    $la[$tab] = lang($tab);
                }
            }
        }
        $la["Help"] = lang("Help");
        return $la;
    }

    function getCurrentView()
    {
        if ( $this->view == 'Addresses' )
        {
            return $this->view . "_" . $this->addresstype;
        }
        else
        {
            return CdBase::getCurrentView();
        }
    }

    function getHeaderText()
    {

        $tmpFetchMode = $this->db->setFetchMode(ADODB_FETCH_ASSOC);
        $mgArr = $this->db->GetRow("SELECT Firstname, Lastname FROM `###_Addresses` WHERE Addresstype_ref = 1 AND Adr_MemberID = " . $this->memberID);
        $headArr[0] = $this->memberID;
        $headArr[1] = $mgArr['Firstname']. "&nbsp;" . $mgArr['Lastname'];
        $mgArr = $this->db->GetRow("SELECT FirmName_ml FROM `###_Addresses` WHERE Addresstype_ref = 2 AND Adr_MemberID = " . $this->memberID);
        $headArr[2] = $mgArr['FirmName_ml'];
        $this->db->setFetchMode($tmpFetchMode);
        return $headArr;
    }

    function getNavigationElements()
    {
        if ($this->view == "Help" )
        {
            return array();
        }


        if (getClubUserInfo("MemberOnly") == false)
        {
            $firstM = getFirstRecord($this->db);
            $prevM  = getPrevRecord($this->db, $this->memberID);
            $nextM  = getNextRecord($this->db, $this->memberID);
            $lastM  = getLastRecord($this->db);

            $this->buttons->AddInput(array(
                    "TYPE"=>"image",
                    "ID"=>"image_first",
                    "SRC"=>$this->smarty->get_template_vars('STYLE_DIR') . "images/start.gif",
                    "ALT"=>"First",
                    "STYLE"=>"width: 32px; height: 32px; border-width: 0px; padding: 0px;",
                    "ONCLICK"=>"window.location='" . INDEX_PHP . "?mod=members&MemberID=$firstM';return false;",
                    "SubForm"=>"buttonbar"
            ));
            $this->buttons->AddInput(array(
                    "TYPE"=>"image",
                    "ID"=>"image_prev",
                    "SRC"=>$this->smarty->get_template_vars('STYLE_DIR') . "images/back.gif",
                    "ALT"=>"Prev",
                    "STYLE"=>"width: 32px; height: 32px; border-width: 0px; padding: 0px;",
                    "ONCLICK"=>"window.location='" . INDEX_PHP . "?mod=members&MemberID=$prevM';return false;",
                    "SubForm"=>"buttonbar"
            ));
            $this->buttons->AddInput(array(
                    "TYPE"=>"text",
                    'NAME' => 'newMemberID',
                    'ID' => 'newMemberID',
                    'ONKEYPRESS'=>'return bottomMemberOrSearch(event, this);',
                    "SIZE"=>10,
                    "VALUE"=>$this->memberID,
                    "CLASS"=>"TEXT",
                    "SubForm"=>"buttonbar"
            ));
            $this->buttons->AddInput(array(
                    "TYPE"=>"image",
                    "ID"=>"image_next",
                    "SRC"=>$this->smarty->get_template_vars('STYLE_DIR') . "images/forward.gif",
                    "ALT"=>"Next",
                    "STYLE"=>"width: 32px; height: 32px; border-width: 0px; padding: 0px;",
    /*                "STYLE"=>"border-width: 0px;",*/
                    "ONCLICK"=>"window.location='". INDEX_PHP . "?mod=members&MemberID=$nextM';return false;",
                    "SubForm"=>"buttonbar"
            ));
            $this->buttons->AddInput(array(
                    "TYPE"=>"image",
                    "ID"=>"image_last",
                    "SRC"=>$this->smarty->get_template_vars('STYLE_DIR') . "images/finish.gif",
                    "ALT"=>"Last",
                    "STYLE"=>"width: 32px; height: 32px; border-width: 0px; padding: 0px;",
                    "ONCLICK"=>"window.location='". INDEX_PHP . "?mod=members&MemberID=$lastM';return false;",
                    "SubForm"=>"buttonbar"
            ));
        }
        if ( getUserType(UPDATE, $this->view) )
        {
            switch ($this->view)
            {
                case 'Addresses':
                    $this->buttons->AddInput(array(
                            "TYPE"=>"submit",
                            "ID"=>"Submit",
                            "NAME"=>"Submit",
                            "VALUE"=>lang("Save entry"),
                            "CLASS"=>"BUTTON",
                            "ONCLICK"=>"doAction('members','{$this->view}_{$this->addresstype}','UPDATE');",
                            "SubForm"=>"buttonbar"
                    ));
                    $this->buttons->AddInput(array(
                            "TYPE"=>"button",
                            "ID"=>"MemberReset",
                            "NAME"=>"MemberReset",
                            "CLASS"=>"BUTTON",
                            "VALUE"=>lang("Reset"),
                            "ONCLICK"=>"reset();",
                            "SubForm"=>"buttonbar"
                    ));
                    break;
                case "Privat":
                case "Firm":
                case "Memberinfo":
                    $this->buttons->AddInput(array(
                            "TYPE"=>"submit",
                            "ID"=>"Submit",
                            "NAME"=>"Submit",
                            "VALUE"=>lang("Save entry"),
                            "CLASS"=>"BUTTON",
                            "ONCLICK"=>"doAction('members','$this->view','UPDATE');",
                            "SubForm"=>"buttonbar"
                    ));
                    $this->buttons->AddInput(array(
                            "TYPE"=>"button",
                            "ID"=>"MemberReset",
                            "NAME"=>"MemberReset",
                            "CLASS"=>"BUTTON",
                            "VALUE"=>lang("Reset"),
                            "ONCLICK"=>"reset();",
                            "SubForm"=>"buttonbar"
                    ));
                    break;
                case "Payments":
                    $this->buttons->AddInput(array(
                            "TYPE"=>"submit",
                            "ID"=>"PaymentSubmit",
                            "NAME"=>"PaymentSubmit",
                            "VALUE"=>lang("New Payment"),
                            "CLASS"=>"BUTTON",
                            "ONCLICK"=>"doSubmit('payments','Add');",
                            "SubForm"=>"buttonbar"
                    ));
                    break;
                 case "Fees":
                     $this->buttons->AddInput(array(
                            "TYPE"=>"submit",
                            "ID"=>"FeeSubmit",
                            "NAME"=>"FeeSubmit",
                            "VALUE"=>lang("New Fee"),
                            "CLASS"=>"BUTTON",
                            "ONCLICK"=>"doSubmit('fees','Add');",
                            "SubForm"=>"buttonbar"
                        ));
                        break;
                    case "Conferences":
                        $this->buttons->AddInput(array(
                                "TYPE"=>"submit",
                                "ID"=>"ConferencesSubmit",
                                "NAME"=>"ConferencesSubmit",
                                "VALUE"=>lang("Subscribe Conference"),
                                "CLASS"=>"BUTTON",
                                "ONCLICK"=>"doAction('conferences','Subscribe');",
                                "SubForm"=>"buttonbar"
                        ));
                        break;
                }
            }

            if ( getUserType(DELETE, "Member") )
            {
                $l_delete = sprintf(lang("Do you really want to delete member %s?\\nAttention: All datas (Addresses, EMails, Attributes, Conferences) are deleted also!!"), $this->memberID);
                $this->buttons->AddInput(array(
                        "TYPE"=>"image",
                        "ID"=>"deleteMember",
                        "SRC"=>$this->smarty->get_template_vars('STYLE_DIR') . "images/delMember.gif",
                        "ALT"=>lang('Delete Member'),
                        "STYLE"=>"width: 32px; height: 32px; border-width: 0px; padding: 0px;",
                        "ONCLICK"=>"return deleteMember($this->memberID,'$l_delete');",
                        "SubForm"=>"buttonbar_right"
                ));
            }


        //print("<PRE>");print_r($cols);print("</PRE>");
//         return $cols;
    }

    function doAction($action)
    {
      global $APerr;

      debug('M_MEMBER',"View: $this->view, action: $action");
      if ( $this->view == "Payments" || $this->view == "Fees" || $this->view == "Conferences" )
      {
        /* Create view object to update values.
        */
        $viewObjName = "v" . $this->view;
        $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->addresstype, $this->smarty, $this->formsgeneration);

        debug('M_MEMBER',"doAction for Payments or Fees: " . (method_exists( $this->viewObj, 'doAction')  ? 'true' : 'false'));
        if (method_exists( $this->viewObj, 'doAction') )
        {
          debug('M_MEMBER',"call to doAction of $this->view");
          $retCode = $this->viewObj->doAction($action);
        }
      }
      else // handle member actions
      {
        if ( $action == "INSERT" && getClubUserInfo("MemberOnly") === false )
        {
            $neuesMitglied = true;
            $this->setMemberID(getLastRecord($this->db) + 1);

            $APerr->setInfo(lang("New MemberID") . ": " . $this->memberID);

            /* OK, create new member !! */
            $res = $this->db->Execute("INSERT INTO `###_Members` (MemberID,InfoGiveOut_ref,InfoWWW_ref,Language_ref) VALUES ($this->memberID,-1,-1,'" . DEFAULT_LANGUAGE . "')")
                        or die(__LINE__ . ": " . $this->db->ErrorMsg() . "<BR>" . $sql);
            logEntry("INSERT MEMBER", "MemberID=" . $this->memberID);
            $this->setAktView('Memberinfo');

        }
        if ( $action == "DELETE" && getClubUserInfo("MemberOnly") === false )
        {
            $sql = "DELETE `###_Members`, `###_Members_Attributes`,`###_Members_Conferences`,`###_Members_Emails`, `###_Addresses`, `###_Addresses_Mailingtypes`
                           FROM `###_Members`
                      LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID
                      LEFT JOIN `###_Members_Conferences` ON `###_Members`.MemberID = `###_Members_Conferences`.MemberID
                      LEFT JOIN `###_Members_Emails` ON `###_Members`.MemberID = `###_Members_Emails`.MemberID
                      LEFT JOIN `###_Addresses` ON `###_Addresses`.Adr_MemberID=`###_Members`.MemberID
                      LEFT JOIN `###_Addresses_Mailingtypes` ON `###_Addresses`.id = `###_Addresses_Mailingtypes`.AddressID
                          WHERE `###_Members`.MemberID={$this->memberID}";

            $res = $this->db->Execute($sql)
                        or die(__LINE__ . ": " . $this->db->ErrorMsg() . "<BR>" . $sql);

            logEntry("DELETE MEMBER", "MemberID=" . $this->memberID);

            $this->setMemberID(getNextRecord($this->db,$this->memberID));

        }
        elseif ( $action == "UPDATE" )
        {
                /* Create view object to update values.
                */
                $viewObjName = "v" . $this->view;
                $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->addresstype, $this->smarty, $this->formsgeneration);

          $this->viewObj->doAction($action);

    // 			$this->setMemberID($this->memberID);
        }
      }
    }

    /* Overwrite function display to show view
     * Must be delayed until now, as we don't know the correct memberID earlier
     * (It might be overwriten by an action !!)
     */
    function display($display = true, $template = 'main.tpl')
    {

        if ( getUserType(VIEW,$this->view) || getUserType(INSERT,$this->view) ||
                    getUserType(UPDATE,$this->view) || getUserType(DELETE,$this->view) )
        {
          if ( !is_object($this->viewObj) )
          {
              $viewObjName = "v" . $this->view;
              $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->addresstype, $this->smarty, $this->formsgeneration);
          }
        }
        parent::display();
    }
}
?>
