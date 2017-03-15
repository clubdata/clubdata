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
require_once("include/dblist.class.php");

/**
 * This class generates a list of member summaries
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Queries
 */
class vMemberSummary {
    var $memberID;
    var $db;
    var $mlist;
    var $feeID;
    var $smarty;
    var $formsgeneration;
    var $selectCMD = "";

    function vMemberSummary($db, $memberID, $smarty, $formsgeneration)
    {

        $this->db = $db;
        $this->memberID = $memberID;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->pageNr = getGlobVar("PageNr","::number::");
        if (empty($this->pageNr) )
        {
            $this->pageNr = 1;
        }

        $selectionArr = getGlobVar("Membertype_ref");
        if ( is_array($selectionArr) )
        {
        // echo "MemberID: " . implode(", ", $MemberID);
            $this->selectCMD = "Membertype_ref in ('" . implode("', '", $selectionArr) ."')";
        }
    }
//             echo "SQL: $sql<BR>";    }

    function getSmartyTemplate()
    {
//         return CdBase::getSmartyTemplate();
        return 'queries/v_MemberSummary.inc.tpl';
    }

    function setSmartyValues()
    {
      global $language;

      if ( !$this->mlist )
      {
        // ID-Spalte muss `Members%Membertype_ref[]` heissen, da dieser Wert als idFieldName ben�tigt wird, damit
        // Detailansicht auf Memberlist funktioniert. (Formsgeneration ben�tigt genau diesen Namen f�r die Suche nach
        // der Membertype_ref !!)
        // Hint: lang('NoOfMembers'); 
        $sql = <<<_EOT_
            SELECT Membertype_ref as `###_Members%Membertype_ref[]`, Count(*) as NoOfMembers
                FROM `###_Members`
           LEFT JOIN `###_Membertype` ON `###_Members`.Membertype_ref = `###_Membertype`.id 
            GROUP BY Membertype_ref, `###_Membertype`.Description_$language
_EOT_;

        $this->mlist = new DbList($this->db, "membersummarylist",
                                array("changeFlg" => FALSE,
                                "sql" => $sql,
                                "selectRowsFlg" => TRUE,
                                "sumCols" => array("NoOfMembers"),
                                "idFieldName" => clubdata_mysqli::replaceDBprefix('###_Members%Membertype_ref[]', DB_TABLEPREFIX),
                                "listLinks" => array ( "Detail" => INDEX_PHP . "?mod=list&view=Memberlist&InitView=1&Command=Member&" . clubdata_mysqli::replaceDBprefix('###_Members%25Membertype_ref_select', DB_TABLEPREFIX) . "=INSelection&",
                                                        ),
                                "linkParams" => "mod=queries&view=MemberSummary"));

        debug_r('M_QUERIES', $this->mlist, "vMemberSummary: (SQL=$sql), MLIST");
      }
		$tmpSort = getGlobVar('sort', '::text::');

		if ( !empty($tmpSort) )
		{
			$this->mlist->setConfig('sort', $tmpSort);
		}

        debug_r('M_QUERIES',$this->mlist->getConfig('selectedRowsArr') , "[QUERIES,setSmartyValues],selectedRowsArr ");
        debug_r('M_QUERIES',$this->mlist , "[QUERIES,setSmartyValues],mlist ");
        $this->mlist->prepareRecordList('');
        $this->smarty->assign_by_ref('MemberSummary', $this->mlist);
        debug_r('M_QUERIES',$this->mlist , "[QUERIES,setSmartyValues],mlist ");

    }

    /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        global $language;

        $this->mlist = new DbList($this->db, "membersummarylist");

        $selectionArr = getGlobVar("Membertype_ref");
        if ( is_array($selectionArr) )
        {
        // echo "MemberID: " . implode(", ", $MemberID);
            $selectCMD = "Membertype_ref in ('" . implode("', '", $selectionArr) ."')";
        }
//             echo "SELECTIONARR: $selectionArr<BR>SELECTCMD: $selectCMD<BR>";
        switch ( $action )
        {
            case 'SETCHECKED':
                $id = getGlobVar('id', '::number::');
                $newState = getGlobVar('newState', 'false|true');

                $this->mlist->setSelectedRows($id, ($newState == 'false' ? 0 : 1));

			      // Exit if ajax call is used
		          if ( getGlobVar('byAjax', 'true|false') == true )
		          {
			      	exit;
		          }
                break;

            case 'SELECTALL':
                $this->mlist->setAllSelectedRows(1);
                break;

            case 'DESELECTALL':
                $this->mlist->setAllSelectedRows(0);
                break;

            case 'EXCEL':
                //Ignore output made so far
                ob_end_clean();
                $this->mlist->exportExcel();
                //Restart output buffering
                ob_start();
                break;
        }
        return true;
    }

/*    function displayView()
    {

        echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
        $this->mlist->showTable();
    }*/
}
?>
