<?php
/**
 * Clubdata Administration Modules (View List)
 *
 * Contains the class to view a list of database records
 * This is used for administrative purposes, to view a list of database records
 * This class is called by Class Admin.
 *
 * @package Admin
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

require_once("include/dblist.class.php");

/**
 * List all rows of an administrive database table
 *
 * @package Admin
 */
class vList {

    private $app;

    public $memberID;
    public $db;
    public $mlist;
    public $feeID;
    public $tableid;
    public $pageNr;
    public $smarty;
    public $formsgeneration;

    public function __construct($db, $table, $key, $smarty, $formsgeneration) {
        $this->app = \Clubdata\Application::instance();

        $this->db = $db;
        $this->table= $table;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->tableid = "admin" . $this->table . "list";
        $this->pageNr = getGlobVar("PageNr", "::number::");

        if (empty($this->pageNr)) {
            $this->pageNr = 1;
        }

        $sqlString = "SELECT * FROM `###_{$this->table}`";

        if ($key != -1) {
            debug('M_ADMIN', "[vList] Reuse ({$key}) {$this->tableid}");
            $this->mlist = new DbList($db, $this->tableid);
        } else {
            debug('M_ADMIN', "[vList] Initialize View ({$key})");

            $nav = $this->app->getNavigation();

            $this->mlist = new DbList(
                $db,
                $this->tableid,
                array(
                    "changeFlg"     => getUserType(ADMINISTRATOR),
                    "sql"           => $sqlString,
                    "changeFlg"     => (($this->table == "Log") ? false : true),
                    "selectRowsFlg" => true,
                    "selectedRows"  => (getConfigEntry($this->db, "CheckedCheckboxes") ? "ALL" : "NONE"),
                    //"maxRowsPerPage" => 20,
                    "listLinks" => array(
                        'Detail' => $nav->getUrl('admin-detail', array('Table' => $this->table)),
                        'Edit'   => $nav->getUrl('admin-edit', array('Table' => $this->table)),
                        'Delete' => $nav->getUrl('admin-list-delete', array('Table' => $this->table)),
                        "Delete" => INDEX_PHP . "?mod=admin&view=List&Action=DELETE&Table=$this->table"
                    ),
                    "linkParams" => "mod=admin&view=List&Table=$this->table"
                )
            );
        }
//             $tmp = $this->mlist->generateCondition();

        $tmpSort = getGlobVar('sort', '::text::');

        if (!empty($tmpSort)) {
            $this->mlist->setConfig('sort', $tmpSort);
        } elseif ($this->mlist->getConfig("sort") == "") {
            $this->mlist->setConfig("sort", "id");
        }
//             echo $this->mlist->generateSQL();
    }

    public function getSmartyTemplate() {
//         return CdBase::getSmartyTemplate();
        return 'admin/v_List.inc.tpl';
    }

    public function setSmartyValues() {
        $this->formsgeneration->AddInput(array(
            "TYPE"=>"hidden",
            "NAME"=>'Table',
            "ID"=>'Table',
            "VALUE"=>$this->table
        ));

        $this->mlist->prepareRecordList($this->pageNr);
//         debug_r('SMARTY', $this->mlist, "[v_List, setSmartyValues]: mlist");
        $this->smarty->assign_by_ref('AdminList', $this->mlist);
        $this->smarty->assign('Table', $this->table);
    }

    public function getLastPage() {
        return $this->mlist->pagecount;
    }

    /**
     * saves values passed via POST
     * @return boolean true : save ok, false: error
     */
    public function doAction($action) {
        debug('ADMIN', "[v_List, doAction]: Action: $action");

        switch ($action) {
            case 'SETCHECKED':
                $id = getGlobVar('id', '::number::');
                $newState = getGlobVar('newState', 'false|true');

                $this->mlist->setSelectedRows($id, ($newState == 'false' ? 0 : 1));

                // Exit if ajax call is used
                if (getGlobVar('byAjax', 'true|false') == true) {
                    exit;
                }
                break;
            case 'SELECTALL':
                $this->mlist->setAllSelectedRows(1);
                break;
            case 'DESELECTALL':
                $this->mlist->setAllSelectedRows(0);
                break;
        }
    }

/*    function displayView()
    {
        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable($this->pageNr);
    }
*/
}
