<?php
/**
 * Clubdata Payments Modules
 *
 * Contains classes to administer payments in Clubdata.
 *
 * @package Payments
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/dblist.class.php');

/**
 * List payments
 *
 * @package Payments
 */
class vList {
    var $memberID;
    var $db;
    var $mlist;
    var $paymentObj;
    var $smarty;
    var $formsgeneration;

    function vList($db, $memberID, $paymentObj, $initView, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->paymentObj = $paymentObj;
        $this->initView = $initView;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->pageNr = getGlobVar('PageNr','::number::');
        if (empty($this->pageNr) )
        {
            $this->pageNr = 1;
        }

        $sqlString = <<<_EOT_
            SELECT
                `###_Payments`.id,
                IF(InvoiceNumber < 0, '', InvoiceNumber) InvoiceNumber,
                MemberID,
                Paytype_ref,
                Period,
                Amount,
                Paydate,
                Paymode_ref,
                Checknumber,
                Remarks
                FROM `###_Payments`
_EOT_;

        if ( $this->initView != 1 )
        {
            debug('M_PAYMENTS',"[Payments, vList]: REUSE PAYMENTLIST");
            $this->mlist = new DbList($this->db, 'paymentslist');
        }
        else
        {
            debug('M_PAYMENTS',"[Payments, vList]: NEW PAYMENTLIST");
            $this->mlist = new DbList($this->db, 'paymentslist',
                                    array('changeFlg' => getUserType(UPDATE, 'Payments'),
                                    'sql' => $sqlString,
                                    'selectRowsFlg' => TRUE,
                                    'listLinks' => array ( 'Detail' => INDEX_PHP . '?mod=payments&view=Detail',
                                                            'Edit' => INDEX_PHP . '?mod=payments&view=Edit',
                                                            'Delete' => INDEX_PHP . '?mod=payments&view=List&Action=DELETE'),
                                    'linkParams' => 'mod=payments&view=List'));

            // Generate conditions from Search result.
            // In no search, reuse old conditions
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
    }

        /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        global $APerr;

        $mlist = new DbList($this->db, 'paymentslist');
        switch ( $action )
        {
            case 'SETCHECKED':
                $id = getGlobVar('id', '::number::');
                $newState = getGlobVar('newState', 'false|true');

    //             echo "NEW STATE: ID=$id, State=$newState<BR>";
                $mlist->setSelectedRows($id, ($newState == 'false' ? 0 : 1));

                // Exit if ajax call is used
                if ( getGlobVar('byAjax', 'true|false') == true )
                {
                	exit;
                }
                break;

            case 'SELECTALL':
//             echo "NEW STATE: ID=$id, State=$newState<BR>";
                $mlist->setAllSelectedRows(1);
                break;

            case 'DESELECTALL':
//             echo "NEW STATE: ID=$id, State=$newState<BR>";
                $mlist->setAllSelectedRows(0);
                break;

            case 'EXCEL':
                $mlist->exportExcel();
                break;

            case 'DELETE':
                $this->paymentObj->deleteRecord();
                break;
        }
        return true;
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'payments/v_List.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('M_PAYMENTS', $this->mlist, "[V_Payments, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('PaymentList', $this->mlist);
    }

    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        if ( is_object($this->mlist) )
        {
          $this->mlist->showTable($this->pageNr);
        }
    }

    function getHeadTxt()
    {
      return lang('List of payments');
    }

}
?>
