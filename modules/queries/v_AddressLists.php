<?php
/**
 * Clubdata Query Modules
 *
 * Contains classes to generate and show queries in Clubdata.
 *
 * @package Queries
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/publicAddresses.class.php");
require_once("include/createPDF.class.php");

/**
 * This class generates address lists of all kind
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Queries
 */
class vAddressLists {
    var $memberID;
    var $db;
    var $tblObj;
    var $pubAdrObj;
    var $pageNr;
    var $addresslistType;
    var $smartyCreationType;
    var $smarty;
    var $formsgeneration;

    function vAddressLists($db, $memberID, $smarty, $formsgeneration)
    {
        global $language;

        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->addresslistType = getGlobVar('addresslistType','PUBLIC','PG');


        $this->pageNr = getGlobVar('PageNr','::number::');
       if (empty($this->pageNr) )
        {
            $this->pageNr = 1;
        }
        debug('ADDRESSES', "[vAddressLists] PageNr = ". $this->pageNr);
        $this->db = $db;
        $this->memberID = $memberID;
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'queries/v_AddressLists.inc.tpl';
    }

    function setSmartyValues()
    {
        global $smarty;

        $listArr = array('' => '',
                         'PUBLIC' => lang("Public Addresslist"));

        $this->formsgeneration->AddInput(array(
                "TYPE"=>"select",
                "MULTIPLE"=>0,
                "NAME"=>'addresslistType',
                "ID"=>'addresslistType',
                "SIZE"=>1,
                "VALUE"=>'',
                "LABEL"=>lang("Type of address list"),
                "OPTIONS"=>$listArr,
                ));

        $this->smarty->assign_by_ref("adressSelection", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));

    }



    /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        global $language;

        debug('SMARTY', "[v_AddressList, setSmartyValues]: action $action, addresslistType: " . $this->addresslistType);

        switch($this->addresslistType)
        {
            case 'PUBLIC':

                $cond = "`###_Members`.Membertype_ref = `###_Membertype`.id
                            AND `###_Membertype`.IsCancelled_yn = 0
                            AND InfoGiveOut_ref > 0";

                $this->pubAdrObj = new publicAddresses($this->db,$this->formsgeneration, false, array("idFieldName" => "",
                                                    'linkParams' => "&mod=queries&view=AddressLists&addresslistType=PUBLIC&Action=DISPLAY"));
                $this->pubAdrObj->createPublicAddressesByCond($cond);

        		$tmpSort = getGlobVar('sort', '[A-Za-z0-98%]');
        		if ( !empty($tmpSort) )
        		{
        			$this->pubAdrObj->setConfig('sort', $tmpSort);
        		}

                switch ( $action )
                {
                    case 'PDF':
                        $this->pubAdrObj->createPDF();
                        break;

                    case 'EXCEL':
                        $this->pubAdrObj->exportExcel();
                        break;

                    case 'DISPLAY':
                        $this->pubAdrObj->prepareRecordList($this->pageNr);
                        debug_r('SMARTY', $this->pubAdrObj, "[v_AddressList, setSmartyValues]: pbAdrObj");
                        $this->smarty->assign_by_ref('AddressList', $this->pubAdrObj);
                        // Display will be done in displayView()
                        break;
                }
                break;
        }
        return true;
    }

}
?>
