<?php
/**
 * Clubdata Conference Modules
 *
 * Contains classes to administer conferences in Clubdata.
 *
 * @package Conferences
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/dblist.class.php');

/**
 *  List all conferences
 *
 * @package Conferences
 */
class vList {
    var $memberID;
    var $db;
    var $mlist;
    var $conferenceObj;
    var $smarty;
    var $formsgeneration;

    function vList($db, $memberID, $conferenceObj, $initView, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->conferenceObj = $conferenceObj;
        $this->initView = $initView;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->pageNr = getGlobVar('PageNr','::number::');
        if (empty($this->pageNr) )
        {
            $this->pageNr = 1;
        }

        $sqlString = "SELECT " . $this->conferenceObj->fieldList . " FROM `###_Conferences`";


        if ( $this->initView != 1 )
        {
            debug('M_CONFERENCES',"[Conferences, vList]: REUSE CONFERENCELIST");
            $this->mlist = new DbList($this->db, 'conferenceslist');
        }
        else
        {
            debug('M_CONFERENCES',"[Conferences, vList]: NEW CONFERENCELIST");
            $this->mlist = new DbList($this->db, 'conferenceslist',
                                    array('changeFlg' => getUserType(UPDATE, 'Conferences'),
                                    'sql' => $sqlString,
                                    'selectRowsFlg' => TRUE,
                                     'selectedRows' => (getConfigEntry($this->db, "CheckedCheckboxes") ? "ALL" : "NONE"),
                                    'listLinks' => array ( 'Detail' => INDEX_PHP . '?mod=conferences&view=Detail',
                                                            'Edit' => INDEX_PHP . '?mod=conferences&view=Edit',
                                                            'Delete' => INDEX_PHP . '?mod=conferences&view=List&Action=DELETE'),
                                    'linkParams' => 'mod=conferences&view=List'));

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
            $this->mlist->setConfig('sort', 'Startdate');
        }
    }

        /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        global $APerr;

        $mlist = new DbList($this->db, 'conferenceslist');
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
                $this->conferenceObj->deleteRecord();
                break;
        }
        return true;
    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'conferences/v_List.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList($this->pageNr);
        debug_r('M_CONFERENCES', $this->mlist, "[V_Conferences, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('ConferenceList', $this->mlist);
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
      return lang('List of conferences');
    }

}
?>
