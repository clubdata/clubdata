<?php
/**
 * Clubdata Member Modules
 *
 * Contains the class to list and manipulate conferences participation for a member
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
require_once("include/dblist.class.php");
require_once("include/fees.class.php");

/**
 * @package Members
 */
class vFees {
    var $memberID;
    var $db;
    var $mlist;
    var $pageNr;

    var $smarty;
    var $formsgeneration;

    function vFees($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        /* addresstype not used */
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $this->pageNr = getGlobVar('PageNr','::number::');

        $sqlString = <<<_EOT_
        SELECT
            id,
            IF(InvoiceNumber < 0, '', InvoiceNumber) InvoiceNumber,
            InvoiceDate,
            DueTo,
            Period,
            Amount,
            Remarks,
            DemandLevel
            FROM `###_Memberfees`
_EOT_;

        $this->mlist = new DbList($db, "feeslist",
                                array("changeFlg" => getUserType(UPDATE, "Fees"),
                                        "sql" => $sqlString,
                                        "cond" => "MemberID = $this->memberID",
                                        "selectRowsFlg" => FALSE,
                                        "listLinks" => array (  "Detail" => INDEX_PHP . "?mod=fees&view=Detail",
                                                                "Edit" => INDEX_PHP . "?mod=fees&view=Edit",
                                                                "Delete" => INDEX_PHP . "?mod=members&view=Fees&Action=DELETE",
                                                                "InvoiceNumber" => INDEX_PHP . "?mod=payments&view=Detail&InitView=1&InvoiceNumber_select=Exact"
                                                            ),
                                        "linkParams" => "mod=members&view=Fees&MemberID=$this->memberID"));

		$tmpSort = getGlobVar('sort', '::text::');

		if ( !empty($tmpSort) )
		{
			$this->mlist->setConfig('sort', $tmpSort);
		}
        elseif ( $this->mlist->getConfig("sort") == "" )
        {
            $this->mlist->setConfig("sort", "Period");
        }
    }

    function doAction($action)
    {
      debug('M_MEMBER', "[V_Fees, doAction]: action: $action");
      switch($action)
      {
        case 'INSERT':
          $this->feeObj = new Fees($this->db, $this->formsgeneration);
          $this->feeObj->newRecord();
          $insertID = $this->feeObj->insertRecord();
          break;

        case 'DELETE':
          $id = getGlobVar('id','::number::','PG');
          $this->feeObj = new Fees($this->db, $this->formsgeneration, array('FeeID' => $id));
          $this->feeObj->deleteRecord();
        break;
      }
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'members/v_Fees.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('SMARTY', $this->mlist, "[V_Fees, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('FeeList', $this->mlist);
    }


    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable($this->pageNr);
    }
}
?>
