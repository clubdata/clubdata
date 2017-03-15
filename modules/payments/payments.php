<?php
/**
 * Clubdata Payments Modules
 *
 * Contains classes to administer payments in Clubdata.
 *
 * @package Payments
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */
/*
    payments.php: Module for managing payments
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
require_once('include/function.php');
require_once('include/cdbase.class.php');
require_once('include/payments.class.php');


/**
 * @package Payments
 */
class cdPayments extends CdBase {
    var $paymentID;
    var $memberID;
    var $invoiceNumber;

    var $initView;

    function cdPayments()
    {
        global $APerr;

        CdBase::CdBase();

//           phpinfo(INFO_VARIABLES);

        $view = getGlobVar('view',
                            'List|Detail|Edit|Add|Help',
                            'PGS');

        $this->paymentObj = new Payments($this->db, $this->formsgeneration);

        $this->memberID = getGlobVar('MemberID','::number::', 'PG');
//         echo "MemberID = $this->memberID<BR>";

        $this->initView = getGlobVar('InitView','0|1', 'PG');

        $paymentID = getGlobVar('id','::numberlist::');
        $invoiceNumber = getGlobVar('InvoiceNumber');
        // Change array of payment IDs to list of payment IDs

        if ( !empty($paymentID) )
        {
          debug('M_PAYMENTS', "[PAYMENT, cdPayment] setPaymentsByPaymentID($paymentID)");
          $this->paymentObj->setPaymentsByPaymentID($paymentID);
        }
        elseif ( !empty($invoiceNumber) )
        {
          if ( $view != 'Add' )
          {
            debug('M_PAYMENTS', "[PAYMENT, cdPayment] getPaymentsForInvoice($invoiceNumber)");
            $paymentID = $this->paymentObj->getPaymentsForInvoice($invoiceNumber);

            if ( $paymentID === false || empty($paymentID) )
            {
              //Give up, enough is enough !!
              $APerr->setFatal(sprintf(lang('No payments found for invoice number %s'), $this->invoiceNumber));
            }
          }
        }
        else
        {
            $paymentID = getGlobVar('PaymentID','::numberlist::','S');
          debug('M_PAYMENTS', "[PAYMENT, cdPayment] PaymentID: setPaymentsByPaymentID($paymentID)");
            $this->paymentObj->setPaymentsByPaymentID($paymentID);
        }

        debug('M_PAYMENTS', "[PAYMENTS, Payments] PAYMENTID: $paymentID");

        // Save PaymentID only if a payment id is expected
        // e.g. not with view == ADD
        if ( $view != 'Add' && $view != 'List')
        {
          $_SESSION['PaymentID'] = $this->paymentObj->getPaymentIDsAsList();
        }

        // Check if single paymentID or a list of paymentIDs (comma separated)
        // If list, force to show List tab if detail or edit should be selected
        if ( $this->paymentObj->getPaymentIDCount() > 1 && ($view == 'Edit' || $view == 'Detail') )
        {
            $view = 'List';
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
        return 'payments';
    }

/**/
    function getModulePerm($action = '')
    {
        // Calculate Authorization constant from action string:
        // 'VIEW' => VIEW (3), etc.
        if ( $action == 'EXCEL' ) $action = 'VIEW';
//         echo ("\$userRight = " . (empty($action) ? 'VIEW' : strtoupper("'".$action."'")) . ';');
        eval ("\$userRight = " . (empty($action) ? 'VIEW' : strtoupper("'".$action."'")) . ';');

//          echo "USERRIGHT: $userRight<BR>";

        if ( ! isLoggedIn() )
        {
        	return false;
        }
        
        $retVal = false;
        if ( getClubUserInfo('MemberOnly') === true )
        {
//            $this->setMemberID(getClubUserInfo('MemberID'));
            $retVal = ($this->view == 'Detail') ? true : false;
        }
        else
        {
            if ( ! getUserType($userRight, 'Payments') )
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
            $viewObjName = 'v' . $this->view;
            $this->viewObj = new $viewObjName($this->db, $this->memberID, $this->paymentObj, $this->initView, $this->smarty, $this->formsgeneration);
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

        if ( $this->view != 'List' && $this->paymentObj->getPaymentIDCount() == 1 )
        {
            $la['Detail'] = lang('Detail');

            if ( getUserType(UPDATE, 'Payments') )
            {
                $la['Edit'] = lang('Edit');
            }
        }
        else
        {
                $la['List'] = lang('List');
        }
        if ( getUserType(INSERT, 'Payments') )
        {
            $la['Add'] = lang('Add');
        }
        $la['Help'] = lang('Help');
        return $la;
    }

    function getNavigationElements()
    {
        $cols = array();
        if ( $this->view == 'List' )
        {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Select all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('payments','$this->view','SELECTALL');",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_1",
                        "NAME"=>"Submit_1",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('payments','$this->view','DESELECTALL');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Excel",
                        "NAME"=>"Submit_Excel",
                        "VALUE"=>lang("Export to Excel"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('payments','$this->view','EXCEL');",
                        "SubForm"=>"buttonbar"
                ));
        }
        if ( $this->view != 'List' && $this->view != 'Detail' && $this->view != 'Help')
        {
            if ( $this->view == 'Edit' && getUserType(UPDATE, 'Payments') )
            {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Update",
                        "NAME"=>"Submit_Update",
                        "VALUE"=>lang("Update entry"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('payments','$this->view','UPDATE');",
                        "SubForm"=>"buttonbar"
                ));
            }
            if ( $this->view == 'Add' && getUserType(INSERT, 'Payments') )
            {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Insert",
                        "NAME"=>"Submit_Insert",
                        "VALUE"=>lang("Insert entry"),
                        "CLASS"=>"BUTTON",
//                         "ONCLICK"=>"doAction('payments','$this->view','INSERT');",
                        "ONCLICK"=>"doAction('members','Payments','INSERT');",
                        "SubForm"=>"buttonbar"
                ));
            }

            $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"PaymentsReset",
                        "NAME"=>"PaymentsReset",
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
                        "ONCLICK"=>"doSubmit('members','Payments');",
                        "SubForm"=>"buttonbar"
                ));
        }
        return $cols;
    }

    function doAction($action)
    {
        debug('M_PAYMENTS', "[Payments, doAction] Action: $action");
        $retCode = $this->viewObj->doAction($action);
        if ( $retCode === true )
        {
          $this->setAktView('List');
          $this->getModulePerm();
        }
//          echo "RETCODE: $retCode, PAYMENTID: $this->paymentID<BR>";
/*        if ( $action == 'INSERT' )
        {
            $insertID = $retCode;
            if ( !empty($insertID) )
            {
                $this->paymentID = $insertID;
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

        // Show Member to which the paymentID belongs, if and only if
        // the payment id is unique (e.g. set and no array)
        if ( $this->view != 'List' && $this->paymentObj->getPaymentIDCount() > 0 )
        {
            $sql = "SELECT DISTINCT MemberID FROM `###_Payments` WHERE id in (" . $this->paymentObj->getPaymentIDsAsList() . ")";
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
//                  print("<PRE>");print_r($headArr);print("</PRE>");
                }
                else
                {
                    $headArr[0] = lang('List of payments');
                }
            }
        }
        else
        {
            $headArr[0] = $this->viewObj->getHeadTxt();
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