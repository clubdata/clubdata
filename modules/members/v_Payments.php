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
require_once('include/dblist.class.php');
require_once('include/payments.class.php');

/**
 * @package Members
 */
class vPayments {
    var $memberID;
    var $db;
    var $mlist;
    var $pageNr;

    function vPayments($db, $memberID, $addresstype, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        /* addresstype not used */
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;

        $this->pageNr = getGlobVar('PageNr', '::number::');


        $sqlString = <<<_EOT_
            SELECT
                `###_Payments`.id,
                IF(InvoiceNumber < 0, '', InvoiceNumber) InvoiceNumber,
                Paytype_ref,
                period,
                Amount,
                Paydate,
                Paymode_ref,
                Checknumber,
                Remarks
                FROM `###_Payments`
_EOT_;

        $this->mlist = new DbList($db, 'paymentslist',
                                    array('changeFlg' => getUserType(UPDATE, 'Payments'),
                                    'sql' => $sqlString,
                                    'cond' => "MemberID = $this->memberID",
                                    'selectRowsFlg' => FALSE,
                                    'listLinks' => array ( 'Detail' => INDEX_PHP . '?mod=payments&view=Detail',
                                                            'Edit' => INDEX_PHP . '?mod=payments&view=Edit',
                                                            'Delete' => INDEX_PHP . '?mod=members&view=Payments&Action=DELETE',
                                                            'InvoiceNumber' => INDEX_PHP . '?mod=fees&view=Detail&InvoiceNumber_select=Exact'
                                                        ),
                                    'linkParams' => "mod=members&view=Payments&MemberID=$this->memberID"));

		$tmpSort = getGlobVar('sort', '::text::');

		if ( !empty($tmpSort) )
		{
			$this->mlist->setConfig('sort', $tmpSort);
		}
        elseif ( $this->mlist->getConfig('sort') == '' )
        {
            $this->mlist->setConfig('sort', 'Period');
        }

    }

    function doAction($action)
    {
      debug('M_MEMBER', "[V_Payments, doAction]: action: $action");
      switch($action)
      {
        case 'INSERT':
          $this->paymentObj = new Payments($this->db, $this->formsgeneration);
          $this->paymentObj->newRecord();
          $insertID = $this->paymentObj->insertRecord();
          break;

        case 'DELETE':
          $id = getGlobVar('id','::number::','PG');
          $this->paymentObj = new Payments($this->db, $this->formsgeneration, array('PaymentID' => $id));
          $this->paymentObj->deleteRecord();
        break;
      }
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'members/v_Payments.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('SMARTY', $this->mlist, "[V_Payments, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('PaymentList', $this->mlist);
    }

    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable($this->pageNr);
    }
}
?>
