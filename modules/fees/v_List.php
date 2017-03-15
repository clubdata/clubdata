<?php
/**
 * Clubdata Fees Modules
 *
 * Contains classes to administer fees in Clubdata.
 *
 * @package Fees
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/dblist.class.php");

/**
 * List fees
 *
 * @package Fees
 */
class vList {
    var $memberID;
    var $db;
    var $mlist;
    var $feeObj;
    var $pageNr;
    var $smarty;
    var $formsgeneration;

    function vList($db, $memberID, $feeObj, $smarty, $formsgeneration)
    {
        global $APerr;

        debug_r('M_PAYMENTS', $paymentObj, "[Payments, vList, vList], MemberID: $memberID, initView: $initView, paymentObj:");
        $this->db = $db;
        $this->memberID = $memberID;
        $this->feeObj = $feeObj;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->pageNr = getGlobVar('PageNr','::number::');
        if (empty($this->pageNr)  )
        {
            $this->pageNr = 1;
        }

        $this->initView = getGlobVar('InitView','0|1', 'PG');

        $sqlString = <<<_EOT_
        SELECT
            id,
            MemberId,
            IF(InvoiceNumber < 0, '', InvoiceNumber) InvoiceNumber,
            InvoiceDate,
            DueTo,
            Period,
            Amount,
            Remarks,
            DemandLevel
            FROM `###_Memberfees`
_EOT_;

        if ( $this->initView != 1 )
        {
            debug('M_FEES',"[Fees, vList]: REUSE FEESTLIST");
            $this->mlist = new DbList($this->db, 'feeslist');
        }
        else
      {
          if ($this->feeObj->getFeeIDCount() == 0 )
          {
            $APerr->setWarn(sprintf(lang("No fees found")));

          }

          $this->mlist = new DbList($db, "feeslist",
                                      array("changeFlg" => getUserType(UPDATE, "Payments"),
                                      "sql" => $sqlString,
                                      "cond" => ($this->feeObj->getFeeIDCount() == 0 ? "1=0" : "id IN (" . $this->feeObj->getFeeIDsAsList() . ")"),
                                      "selectRowsFlg" => TRUE,
                                      "listLinks" => array ( "Detail" => INDEX_PHP . "?mod=fees&view=Detail",
                                                              "Edit" => INDEX_PHP . "?mod=fees&view=Edit",
                                                              "Delete" => INDEX_PHP . "?mod=fees&view=List&Action=DELETE",
                                                              "MemberID" => INDEX_PHP . "?mod=members&view=Fees",
                                                          ),
                                      "linkParams" => "mod=fees&view=List"));
          $tmp = $this->mlist->generateCondition();
        }

  		$tmpSort = getGlobVar('sort', '::text::');

		if ( !empty($tmpSort) )
		{
			$this->mlist->setConfig('sort', $tmpSort);
		}
        elseif ( $this->mlist->getConfig('sort') == '' )
        {
            $this->mlist->setConfig('sort', 'Period');
        }
//             echo $this->mlist->generateSQL();

    }

        /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
      global $APerr;

      debug('M_FEES', "[Fees,v_List]: doAction: $action");
      switch($action)
      {
        case 'EXCEL':
          $saveCond = $this->mlist->getConfig("cond");
          $this->mlist->generateCondition("id IN (" . $this->feeObj->getFeeIDsAsList() . ")" );

          //Ignore output made so far
          ob_end_clean();

          $this->mlist->exportExcel();

          //Restart output buffering
          ob_start();
          $this->mlist->generateCondition($saveCond);
          break;

        case 'DELETE':
          $this->feeObj->deleteRecord();
          $this->feeObj->setFeesByMemberID($this->memberID);
          break;
      }
      return true;
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'fees/v_List.inc.tpl';
    }

    function setSmartyValues()
    {

        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('M_FEES', $this->mlist, "[V_Fees, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('PaymentList', $this->mlist);
    }

/*    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable();
    }*/
}
?>
