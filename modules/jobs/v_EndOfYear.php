<?php
/**
 * Clubdata Jobs Modules
 *
 * @package Jobs
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/table.class.php');
require_once('include/dblist.class.php');
//     include("javascript/calendar.js.php");

/**
 * Class to perform End of Year tasks
 *
 * @package Jobs
 */
class vEndOfYear {
  var $db;
  var $tblObj;
  var $listObj;
  var $state;

  var $states = array(
          /* Old state => New state */
          ''                     => array('CheckVar' => '',
                                          'NewState' => 'S_P_CancelledMembers',
  										  // lang('End of Year Updates');
  										  'HeaderText' => 'End of Year Updates',
  										  // lang('Start End of Year Updates');
  										  'ButtonText' => 'Start End of Year Update'),
          'S_P_CancelledMembers' => array('CheckVar' => 'eoy_proccancelled',
                                          'NewState' => 'S_CancelledMembers',
  										  // lang('Job: Cancel membership');
  										  'HeaderText' => 'Job: Cancel membership',
  										  // lang('Cancel members');
  										  'ButtonText' => 'Cancel members'),
          'S_CancelledMembers'   => array('CheckVar' => 'eoy_proccancelled',
                                          'NewState' => 'S_P_InsertFees',
  										  // lang('Job: Cancel membership, 1');
  										  'HeaderText' => 'Job: Cancel membership, 1',
  										  // lang('Start End of Year Update, 1');
  										  'ButtonText' => 'Start End of Year Update, 1'),
          'S_P_InsertFees'       => array('CheckVar' => 'eoy_procfees',
                                          'NewState' => 'S_InsertFees',
  										  // lang('Job: Insert fees');
  										  'HeaderText' => 'Job: Insert fees',
  										  // lang('Insert fees');
  										  'ButtonText' => 'Insert fees'),
          'S_InsertFees'         => array('CheckVar' => 'eoy_procfees',
                                          'NewState' => 'S_P_InsertDirectDebit',
  										  // lang('Job: Insert fees');
  										  'HeaderText' => 'Job: Insert fees',
  										  // lang('Insert fees');
  										  'ButtonText' => 'Insert fees'),
          'S_P_InsertDirectDebit'=> array('CheckVar' => 'eoy_procdirectdeb',
                                          'NewState' => 'S_InsertDirectDebit',
  										  // lang('Job: Insert direct debit');
  										  'HeaderText' => 'Job: Insert direct debit',
  										  // lang('Insert direct debit');
  										  'ButtonText' => 'Insert direct debit'),
          'S_InsertDirectDebit'  => array('CheckVar' => 'eoy_procdirectdeb',
                                          'NewState' => '',
  										  // lang('Job: Insert direct debit');
  										  'HeaderText' => 'Job: Insert direct debit',
  										  // lang('Insert direct debit');
  										  'ButtonText' => 'Insert direct debit')
          );

  var $eoy_proccancelled;
  var $eoy_procdirectdeb;
  var $eoy_procfees;
  var $canList;
  var $smarty;
  var $formsgeneration;

  function vEndOfYear($db, $smarty, $formsgeneration)
  {
    $this->db = $db;
    $this->smarty = $smarty;
    $this->formsgeneration = $formsgeneration;

    debug_r('M_JOBS', $_POST);

    $this->state = getGlobVar('State',"^$" . join('|', array_keys($this->states)),'PG');
    $this->eoy_proccancelled = getGlobVar('EOY_PROCCANCELLED', 'YES|NO');
    $this->eoy_procdirectdeb = getGlobVar('EOY_PROCDIRECTDEB', 'YES|NO');
    $this->eoy_procfees = getGlobVar('EOY_PROCFEES', 'YES|NO');

    debug('M_JOBS', "State: {$this->state}");
    $this->tblObj = new Table($this->formsgeneration);
    $this->listObj = new Listing('EndOfYear');

    if ( empty($this->state) )
    {
      unset($_SESSION['endofyear']);
      unset($_SESSION['EOY_PROCCANCELLED']);
      unset($_SESSION['EOY_PROCDIRECTDEB']);
      unset($_SESSION['EOY_PROCFEES']);
      unset($_SESSION['EOY_PERIOD']);
    }
  }

  function getSmartyTemplate()
  {
      switch ( $this->state )
      {
          case 'S_P_CancelledMembers':
          case 'S_CancelledMembers':
          case 'S_InsertFees':
          case 'S_InsertDirectDebit':
              return 'jobs/v_EndOfYear_default.inc.tpl';
              return NULL;

          default:
              return 'jobs/v_EndOfYear_default.inc.tpl';
      }
  }

  function setSmartyValues()
  {
      passParameterAsSession ($_POST,array("mod","view",'Action','State'));
      switch ( $this->state )
      {
          case 'S_P_CancelledMembers':
          $this->enterParameters($this->state);
          $this->canList->prepareRecordList(1);
          $this->smarty->assign_by_ref('cancelList', $this->canList);
          break;

          case 'S_P_InsertFees':
          $this->enterParameters($this->state);
          $this->smarty->assign_by_ref("insertFees", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
          break;

          case 'S_P_InsertDirectDebit':
          $this->enterParameters($this->state);
          $this->smarty->assign_by_ref("directDebit", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
          break;

          case 'S_CancelledMembers':
          case 'S_InsertFees':
          case 'S_InsertDirectDebit':
          $this->smarty->assign_by_ref('JobList', $this->tblObj);
          debug_r('SMARTY', $this->tblObj, "[v_List, setSmartyValues]: mlist");
          break;

          default:
          $yesNo[0]['text'] = lang('Yes');
          $yesNo[0]['value'] = 'YES';
          $yesNo[0]['selected'] = true;
          $yesNo[1]['text'] = lang('No');
          $yesNo[1]['value'] = 'NO';
          $yesNo[1]['selected'] = false;

          $line=0;
          break;
      }

      $this->smarty->assign('NewState', $this->getNewState());
  }

  function getNewState()
  {
      debug('M_JOBS', "[v_EndOfYear, getNewState] OldState: {$this->state}, NewState: {$this->states[$this->state]['NewState']}");
     return $this->states[$this->state]['NewState'];
  }

  function getStateStatus()
  {
      if ( !empty($this->states[$this->state]['CheckVar']) )
      {
          $var = $this->states[$this->state]['CheckVar'];
          debug('M_JOBS', "OldState: {$this->state}, Status: {$this->$var}");
          return ($this->$var == 'YES' ? true : false);
      }
      else
      {
          return true;
      }
  }

  function getActState()
  {
      return $this->state;
  }

  function getActHeaderText()
  {
      return $this->states[$this->state]['HeaderText'];
  }

  function getActButtonText()
  {
      return $this->states[$this->state]['ButtonText'];
  }

  function enterParameters($state)
  {
    $line=0;

    switch ($state)
    {
      case 'S_P_CancelledMembers':
        $sql = <<<_EOT_
            SELECT DISTINCT `###_Members`.MemberID,Membertype_ref,
                   `###_Addresses_1`.`Salutation_ref` AS `Privat_Salutation_ref`,
                   `###_Addresses_1`.`Firstname` AS `Privat_Vorname`,
                   `###_Addresses_1`.`Lastname` AS `Privat_Nachname`,
                   `###_Addresses_2`.`FirmName_ml` AS `Firma_FirmName_ml`
              FROM `###_Membertype`, `###_Members`
         LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID
         LEFT JOIN `###_Addresses` ON `###_Members`.MemberID = `###_Addresses`.`Adr_MemberID`
         LEFT JOIN `###_Addresses_Mailingtypes` ON `###_Addresses`.`id` = `###_Addresses_Mailingtypes`.`AddressID`
         LEFT JOIN `###_Addresses` `###_Addresses_1` ON `###_Members`.MemberID = `###_Addresses_1`.`Adr_MemberID` AND `###_Addresses_1`.`Addresstype_ref` = 1
         LEFT JOIN `###_Addresses_Mailingtypes` `###_Addresses_Mailingtypes_1` ON `###_Addresses_1`.`id` = `###_Addresses_Mailingtypes_1`.`AddressID`
         LEFT JOIN `###_Addresses` `###_Addresses_2` ON `###_Members`.MemberID = `###_Addresses_2`.`Adr_MemberID` AND `###_Addresses_2`.`Addresstype_ref` = 2
         LEFT JOIN `###_Addresses_Mailingtypes` `###_Addresses_Mailingtypes_2` ON `###_Addresses_2`.`id` = `###_Addresses_Mailingtypes_2`.`AddressID`
         LEFT JOIN `###_Addresses` `###_Addresses_3` ON `###_Members`.MemberID = `###_Addresses_3`.`Adr_MemberID` AND `###_Addresses_3`.`Addresstype_ref` = 3
         LEFT JOIN `###_Addresses_Mailingtypes` `###_Addresses_Mailingtypes_3` ON `###_Addresses_3`.`id` = `###_Addresses_Mailingtypes_3`.`AddressID`
             WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
               AND `###_Membertype`.isCancelled_yn =0
               AND `###_Members_Attributes`.Attributes_ref IN ('4')
          ORDER BY `###_Members`.MemberID
_EOT_;

        if ( !is_object($this->canList) )
        {
          debug('M_JOBS', "[v_EndOfYear, enterParameters] State: {$state}, SQL: {$sql}");
          $this->canList = new DbList($this->db, 'endofyear',
                              array(
                                  'sql' => $sql,
                                  'MaxRowsPerPage' => 500,
                                  'selectRowsFlg' => true,
                                  'idFieldName' => 'MemberID',
                                  'linkParams' => 'mod=jobs&view=EndOfYear&State=' . $this->state,
                              ));

        }
    break;

    case 'S_P_InsertFees':
              $errTxt[] .= $this->formsgeneration->AddInput(array(
              "TYPE"=>"text",
              "NAME"=>'INVOICEDATE',
              "ID"=>'INVOICEDATE',
              "LABEL"=>lang("Invoice date"),
      ));
              $errTxt[] .= $this->formsgeneration->AddInput(array(
              "TYPE"=>"text",
              "NAME"=>'PAYUNTIL',
              "ID"=>'PAYUNTIL',
              "LABEL"=>lang("Payable until"),
      ));
      break;

    case 'S_P_InsertDirectDebit':
              $errTxt[] .= $this->formsgeneration->AddInput(array(
              "TYPE"=>"text",
              "NAME"=>'PAYMENTDATE',
              "ID"=>'PAYMENTDATE',
              "LABEL"=>lang("Payment date"),
      ));
      break;
    }
  }

  function processCancelledMembers()
  {
    global $APerr;

    $memberIdArr = (array)getGlobVar('MemberID','::number::','PG');

    debug_r('M_JOBS', $memberIdArr);
    if ( !empty($memberIdArr) )
    {
      $memberIdList = join(",", $memberIdArr);
      /*
        Get id of membership type which has the isCancelled flag set
        This id will be assigned to the members
      */
      $sqlCMD = "SELECT ID FROM `###_Membertype` WHERE `###_Membertype`.IsCancelled_yn <> 0";
      echo "<!-- $sqlCMD -->\n";
      $cancelledType = $this->db->GetOne($sqlCMD) or $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD");
      $cancelledTypeName = getMyRefDescription($this->db, $cancelledType, array('reftable' => 'Membertype'));
      $sqlCMD = <<<_EOT_
UPDATE `###_Members` SET `###_Members`.Membertype_ref = $cancelledType,
`###_Members`.MembershiptypeSince = CONCAT({$this->period},'-1-1')
WHERE `###_Members`.MemberID IN ($memberIdList)
_EOT_;

      echo "<!-- $sqlCMD -->\n";
      $rs = $this->db->Execute($sqlCMD) or $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD");
      if ( ($affectedRows = $this->db->affected_Rows()) === false )
      {
      		$affectedRows = '';
      }

      logEntry("UPDATE CANCELED MEMBER", "ID=$memberIdList");
      $APerr->setInfo(sprintf(lang('%s member(s) set to membertype "%s"'),$affectedRows,$cancelledTypeName));
    }

  }

  function processFees()
  {
    global $APerr;

    $sqlCMD = <<<_EOT_
        SELECT MemberID, Amount
          FROM `###_Members`, `###_Membertype`
         WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
           AND `###_Membertype`.Amount > 0
           AND `###_Membertype`.IsCancelled_yn = 0
_EOT_;

    $invoiceDate = getGlobVar('INVOICEDATE','::date::','PG');
    $payDate = getGlobVar('PAYUNTIL','::date::','PG');

    $sqlInvoiceDate = phpDateToMyDate($invoiceDate);
    $sqlPayDate = phpDateToMyDate($payDate);

    ($rs = $this->db->Execute($sqlCMD)) or $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD");

    $affectedRows = 0;
    while ( $mgArtArr = $rs->FetchRow() )
    {
      $invoiceNumber = sprintf(getConfigEntry($this->db, "InvoiceNumberFormat"),
                                getConfigEntry($this->db, "InvoiceNumber"));

      $sqlCMD = <<<_EOT_
        INSERT INTO `###_Memberfees` (MemberID, InvoiceNumber, InvoiceDate, DueTo,
                    Period, Amount, DemandLevel)
              VALUES ($mgArtArr[MemberID], '$invoiceNumber', $sqlInvoiceDate,
                    $sqlPayDate, {$this->period}, $mgArtArr[Amount],0)
_EOT_;

      echo "<!-- $sqlCMD -->\n";
      if ( $this->db->Execute($sqlCMD) === false )
      {
          $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD","MemberID: $mgArtArr[MemberID]");
      }
      else
      {
        logEntry("INSERT MEMBERFEES", "INVOICENUMBER=$invoiceNumber,INVOICE=$invoiceDate,PAYDATE=$payDate,EOY_PERIOD=$this->period");
        /* Increase invoice number (id = 24) by one */
        $sqlCMD = "UPDATE `###_Configuration` SET value=value+1 WHERE id=24";
        $this->db->Execute($sqlCMD) or $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD");
        logEntry("UPDATE", $sqlCMD);

        $affectedRows++;
      }
    }
    $APerr->setInfo(lang("Numbers of membership fees inserted") . ": " . $affectedRows);
  }

  function processDirectDebit()
  {
    global $APerr;

    $sqlCMD = <<<_EOT_
      SELECT `###_Memberfees`.*
        FROM `###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID,
             `###_Memberfees` LEFT JOIN `###_Payments` ON `###_Memberfees`.InvoiceNumber = `###_Payments`.InvoiceNumber
       WHERE `###_Members`.MemberID = `###_Memberfees`.MemberID
         AND `###_Memberfees`.Period = {$this->period}
         AND `###_Payments`.InvoiceNumber IS NULL
         AND `###_Members_Attributes`.Attributes_ref IN ('1')
_EOT_;

    $paymentDate = getGlobVar('PAYMENTDATE','::date::','PG');
    $sqlPaymentDate = phpDateToMyDate($paymentDate);

    ($rs = $this->db->Execute($sqlCMD)) or $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD");

    $affectedRows = 0;
    $memberArr = array();
    while ( $mgArtArr = $rs->FetchRow() )
    {
          /*
          * 2 = Paymode_ref: Direct debit
          * 1 = Paytype_ref: Membership fee
          */
          $sqlCMD = <<<_EOT_
              INSERT INTO `###_Payments` (MemberID, InvoiceNumber, period, Amount, Paydate, Remarks,
                                    Paymode_ref, Paytype_ref)
                  VALUES ($mgArtArr[MemberID], '$mgArtArr[InvoiceNumber]', $mgArtArr[Period],
                          $mgArtArr[Amount],
                          $sqlPaymentDate, 'Direct debit', 2, 1)
_EOT_;

          echo "<!-- $sqlCMD -->\n";
          if ( $this->db->Execute($sqlCMD) === false )
          {
              $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sqlCMD","MemberID: $mgArtArr[MemberID]");
          }
          else
          {
              logEntry("INSERT DIRECT DEBIT", "YEAR=$this->period;MemberID=$mgArtArr[MemberID];AMOUNT=$mgArtArr[Amount]");
              $affectedRows++;
              $memberArr[] = $mgArtArr["MemberID"];
          }
      }

      if ( count($memberArr) > 0 )
      {
        $APerr->setInfo(lang("Number of payments per direct debit inserted") . ": " . $affectedRows);
      }
      else
      {
        $APerr->setWarn(lang("No insertion of payments by direct debit !"));
      }

  }

  function doAction($action)
  {
    switch ($action)
    {
      case 'SETCHECKED':
          $this->canList = new DBList($this->db, 'endofyear');
          $id = getGlobVar('id', '::number::');
          $newState = getGlobVar('newState', 'false|true');

//          echo "NEW STATE: ID=$id, State=$newState<BR>";
          $this->canList->setSelectedRows($id, ($newState == 'false' ? 0 : 1));
                
	      // Exit if ajax call is used
          if ( getGlobVar('byAjax', 'true|false') == true )
          {
	      	exit;
          }
          break;

      case 'SELECTALL':
//             echo "NEW STATE: ID=$id, State=$newState<BR>";
          $this->canList = new DBList($this->db, 'endofyear');
          $this->canList->setAllSelectedRows(1);
          break;

      case 'DESELECTALL':
//             echo "NEW STATE: ID=$id, State=$newState<BR>";
          $this->canList = new DBList($this->db, 'endofyear');
          $this->canList->setAllSelectedRows(0);
          break;

      case 'ContinueJob':
      case 'StartJob':
        $this->period = getGlobVar("EOY_PERIOD", "[0-9]{4}");

        switch ( $this->state)
        {
          case 'S_P_CancelledMembers':
          case 'S_P_InsertFees':
          case 'S_P_InsertDirectDebit':
            while ( !$this->getStateStatus()  )
            {
              $this->state = $this->getNewState();
            }
            break;

          case 'S_CancelledMembers':
            $this->processCancelledMembers();
            $this->state = $this->getNewState();
            break;

          case 'S_InsertFees':
            $this->processFees();
            $this->state = $this->getNewState();
            break;

          case 'S_InsertDirectDebit':
            $this->processDirectDebit();
            $this->state = $this->getNewState();
            break;
        }

    }
  }
}
?>