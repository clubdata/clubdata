<?php
/**
 * Clubdata Fees Modules
 *
 * Contains classes to administer fees in Clubdata.
 *
 * @package Fees
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/*
    fees.php: Module for managing fees
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
require_once("include/search.class.php");
require_once("include/fees.class.php");

/**
 * @package Fees
 */
class cdFees extends CdBase {
    var $feeObj;
    var $memberID;

    function cdFees()
    {
        global $APerr;

        CdBase::CdBase();

        $view = getGlobVar("view",
                            "List|Detail|Edit|Add|Help",
                            "PGS");

        $this->memberID = getGlobVar("MemberID","::number::",'PG');

        $feeID = getGlobVar("id","::numberlist::");

        $this->invoiceNumber = getGlobVar("InvoiceNumber");

        $this->feeObj = new Fees($this->db, $this->formsgeneration);

        debug('M_FEES', "[Fees,Fees]: Parameter MemberID = $this->memberID, FeeID = $feeID, InvoiceNumber = $this->invoiceNumber");
        // Check how parameters are passed (if any).
        // 3 posibilities must be checked:
        // 1. The parameter id is set, but not id_select
        //      (which is only set by search queries).
        //      Assume id_select equals EQUAL and use variable feeID
        // 2. The parameter InvoiceNumber is set, but not InvoiceNumber_select
        //      (which is only set search queries).
        //    Look up equivalent fee ID(s) and assign them to feeID.
        //    If more than one fee id is found, show list tab instead of
        //    any other tab (except ADD and HELP)
        // 3. Check for parameters *_select. If set, a query was executed.
        //      The sql statement will be generated when creating the table
        //      so do nothing here
        //
        // EXCEPION: When adding a fee there is no fee id yet,
        //              it will be generated later on doAction().
        //              So ignore any invoice numbers also !!!
        // If nothing is set, check session variable "Feeid"
        if ( $view != 'Add' )
        {
          if ( ! checkGlobNameExists("_select$") )
          {
            debug('M_FEES', "[Fees,Fees]: _select does not exists !");
            if ( !empty($feeID) )
            {
              $this->feeObj->setFeesByFeeID($feeID);
            }
            elseif ( !empty($this->invoiceNumber) )
            {
              // Check if invoice number is set,
              // if yes, look for correspondig feeID.
              // if more than one fee id is found, switch to fee list
              $this->feeObj->setFeesByInvoiceNumber($this->invoiceNumber);
            }
            elseif ( !empty($_SESSION["FeeID"]) )
            {
              $this->feeObj->setFeesByFeeID($_SESSION["FeeID"]);
            }
//            else
//            {
//              $APerr->setFatal(lang("No fees found"));
//            }
          }
          else
          {
              debug('M_FEES', "[Fees,Fees]: _select exists !");
              $searchObj = new Search($this->db, $this->formsgeneration, "Fees", 'Fees');

              $cond = $searchObj->generateSelectCMD();
              debug('M_FEES', "[Fees,Fees]: cond = $cond");

              $this->feeObj->setFeesByCondition($cond);
          }

//          if ( $this->feeObj->getFeeIDCount() == 0 )
//          {
                                //Give up, enough is enough !!
//            $APerr->setFatal(sprintf(lang("No fees found")));
//          }

        }
//         echo "FEE-ID: $feeID<BR>";
//         phpinfo(INFO_VARIABLES);
        $_SESSION["FeeID"] = $feeID;

        // Check if single feeID or a list of feeIDs (comma separated)
        // If list, force to show List tab if detail or edit should be selected
        if ( $this->feeObj->getFeeIDCount() > 1 && ($view == "Edit" || $view == "Detail") )
        {
            $view = "List";
        }

        $this->setAktView($view);

        return true;
    }

    function getDefaultView()
    {
        return 'Detail';
    }

    function getModuleName()
    {
        return "fees";
    }

/**/
    function getModulePerm($action = "")
    {
        // Calculate Authorization constant from action string:
        // "VIEW" => VIEW (3), etc.
        if ( $action == "EXCEL" ) $action = "VIEW";
        eval ("\$userRight = " . (empty($action) ? "VIEW" : strtoupper($action)) . ";");

        if ( !isLoggedIn() )
        {
        	return false;
        }  
        
        $retVal = false;
        if ( getClubUserInfo("MemberOnly") === true )
        {
            $retVal = ($this->view == "Detail") ? true : false;
        }
        else
        {
            if ( ! getUserType($userRight, "Fees") )
            {
                $retVal = false;
            }
            else
            {
                $retVal = true;
            }
        }
        if ( $retVal == true )
        {
            $viewObjName = "v" . $this->view;
            $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->feeObj, $this->smarty, $this->formsgeneration);
        }
        return $retVal;
    }

    function getTabulators()
    {
        $la = array();
        return $la;

        $la["List"] = lang("List");

        if ( $this->view != 'List' && $this->feeObj->getFeeIDCount() == 1  )
        {
          $la["Detail"] = lang("Detail");

          if ( getUserType(UPDATE, "Fees") )
          {
              $la["Edit"] = lang("Edit");
          }
        }
        if ( getUserType(INSERT, "Fees") )
        {
            $la["Add"] = lang("Add");
        }
        $la["Help"] = lang("Help");
        return $la;
    }

    function getNavigationElements()
    {
        $cols = array();
        if ( $this->view == "List" )
        {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Select all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('fees','$this->view','SELECTALL');",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_1",
                        "NAME"=>"Submit_1",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('fees','$this->view','DESELECTALL');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Excel",
                        "NAME"=>"Submit_Excel",
                        "VALUE"=>lang("Export to Excel"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('fees','$this->view','EXCEL');",
                        "SubForm"=>"buttonbar"
                ));
/*
        array_push($cols,array ( 'type' => 'button',
                            'name' => 'Action',
                            'label' => lang("Select all"),
                            'javascript' => "onClick=\"SetChecked(1,'$idTag');\""
                ));
        array_push($cols,array ( 'type' => 'button',
                            'name' => 'Action',
                            'label' => lang("Deselect all"),
                            'javascript' => "onClick=\"SetChecked(0,'$idTag'); return false;\""
                ));
        array_push($cols,array ( 'type' => 'submit',
                    'name' => 'Action',
                    'value' => 'EXCEL',
                    'label' => lang("Export to Excel"),
        ));
*/
        }
        if ( $this->view != "List" && $this->view != "Detail" && $this->view != "Help")
        {
            if ( $this->view == "Edit" && getUserType(UPDATE, "Fees") )
            {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Update",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Update entry"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('fees','$this->view','UPDATE');",
                        "SubForm"=>"buttonbar"
                ));
/*                array_push($cols,array ( 'type' => 'submit',
                                        'name' => 'Action',
                                        'value' => 'UPDATE',
                                        'label' => lang("Update entry"),
                            ));*/
            }
            if ( $this->view == "Add" && getUserType(INSERT, "Fees") )
            {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Insert",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Insert entry"),
                        "CLASS"=>"BUTTON",
//                         "ONCLICK"=>"doAction('fees','$this->view','INSERT');",
                        "ONCLICK"=>"doAction('members','Fees','INSERT');",
                        "SubForm"=>"buttonbar"
                ));
/*                array_push($cols,array ( 'type' => 'submit',
                                        'name' => 'Action',
                                        'value' => 'INSERT',
                                        'label' => lang("Insert entry"),
                            ));*/
            }

            $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"FeesReset",
                        "NAME"=>"FeesReset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
/*            array_push($cols,array ( 'type' => 'reset',
                                    'name' => 'MemberReset',
                                    'label' => lang("Reset"),
                        ));*/
        }

        if ( !empty($this->memberID) )
        {

               $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_ReturnToMember",
                        "NAME"=>"Submit_ReturnToMember",
                        "VALUE"=>lang("Return to member"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doSubmit('members','Fees');",
                        "SubForm"=>"buttonbar"
                ));
        }
/*        array_push($cols,array ( 'type' => 'button',
                    'name' => 'Action',
                    'label' => lang("Return to member"),
                    'javascript' => "onClick=\"doSubmit('members','Fees');\""
        ));
        return $cols;*/
    }

    function doAction($action)
    {
//        echo "ACTION: $action<BR>";
        debug('M_FEES', "[Fees,doAction]: ACTION: $action");
        $retCode = $this->viewObj->doAction($action);
        if ( $retCode === true )
        {
            $this->setAktView("List");
            $this->getModulePerm();
        }
    }

    function getHeaderText()
    {
        global $APerr;

        $headArr = array();

        if ( $this->view != 'List' && $this->feeObj->getFeeIDCount() > 0 )
        {
            $sql = "SELECT DISTINCT MemberID FROM `###_Memberfees` WHERE id in (" . $this->feeObj->getFeeIDsAsList() . ")";
            $rs = $this->db->Execute($sql);
            if ( $rs === false )
            {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            }
            else
            {
                if ( $rs->recordCount() == 1 )
                {
                    $memberID = $rs->fields['MemberID'];
                    $mgArr = $this->db->GetRow("SELECT Firstname, Lastname FROM `###_Addresses` WHERE Addresstype_ref = 1 AND Adr_MemberID = " . $memberID);
                    $headArr[0] = $memberID;
                    $headArr[1] = $mgArr['Firstname']. "&nbsp;" . $mgArr['Lastname'];
                    $mgArr = $this->db->GetRow("SELECT FirmName_ml FROM `###_Addresses` WHERE Addresstype_ref = 2 AND Adr_MemberID = " . $memberID);
                    $headArr[2] = $mgArr['FirmName_ml'];
                }
                else
                {
                    $headArr[0] = lang("List of fees");
                }
            }
        }
        else
        {
            $headArr[0] = lang("List of fees");
        }
        return $headArr;
    }

    function displayMainSection()
    {
        echo "<INPUT TYPE=\"HIDDEN\" NAME=\"MemberID\" VALUE=\"$this->memberID\">";
        echo "<INPUT TYPE=\"HIDDEN\" NAME=\"id\" VALUE=\"$feeID\">\n";

        include("javascript/calendar.js.php");

        if ( is_object($this->viewObj) &&
            ($this->view == "Add" || !empty($feeID)))
        {
            return $this->viewObj->displayView();
        }
    }

}
?>
