<?php
/**
 * Clubdata Query Modules
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
require_once("include/listing.class.php");

/**
 * Class to show statistics about memberships
 *
 * @package Queries
 */
class vStatistics {
    var $memberID;
    var $db;
    var $mlist;
    var $smarty;
    var $formsgeneration;

    var $queryArr = array(
                        //HINT: lang("Paying members");
                        array("Description" => "Paying members",
                                "Sql" => "SELECT count(*) FROM `###_Members`, `###_Membertype`
                                        WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                        AND `###_Membertype`.Amount > 0"),
                        //HINT: lang("Not paying members");
                        array("Description" => "Not paying members",
                                "Sql" => "SELECT count(*) FROM `###_Members`, `###_Membertype`
                                        WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                        AND `###_Membertype`.Amount = 0
                                        AND  isCancelled_yn = 0"),
                        //HINT: lang("Active members, (Selected by Default)");
                        array("Description" => "Active members, (Selected by Default)",
                                "Sql" => "SELECT count(*) FROM `###_Members`, `###_Membertype`
                                        WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                        AND `###_Membertype`.SelectByDefault_yn = 1"),
                        //HINT: lang("Other members, (Not Selected by Default)");
                        array("Description" => "Other members, (Not Selected by Default)",
                                "Sql" => "SELECT count(*) FROM `###_Members`, `###_Membertype`
                                        WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                        AND `###_Membertype`.SelectByDefault_yn <> 1
                                        AND  isCancelled_yn = 0"),
                        //HINT: lang("Informations send per email");
                        array("Description" => "Informations send per email",
                                "Sql" => "SELECT count( * )
                                            FROM `###_Members`, `###_Membertype`, `###_Members_Attributes`
                                            WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                            AND `###_Membertype`.SelectByDefault_yn =1
                                            AND `###_Membertype`.isCancelled_yn =0
                                            AND `###_Members_Attributes`.MemberID = `###_Members`.MemberID
                                            AND `###_Members_Attributes`.Attributes_ref =3"),
                        //HINT: lang("Informations send per letter");
                        array("Description" => "Informations send per letter",
                                "Sql" => "SELECT count( * )
                                            FROM `###_Members`
                                        LEFT JOIN `###_Members_Attributes` ON `###_Members_Attributes`.MemberID = `###_Members`.MemberID
                                            AND `###_Members_Attributes`.Attributes_ref =3, `###_Membertype`
                                            WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                            AND `###_Membertype`.SelectByDefault_yn =1
                                            AND isCancelled_yn =0
                                            AND `###_Members_Attributes`.MemberID IS NULL "),
                        //HINT: lang("Canceled by end of the year");
                        array("Description" => "Canceled by end of the year",
                                "Sql" => "SELECT count( * )
                                            FROM `###_Members`, `###_Membertype`, `###_Members_Attributes`
                                            WHERE `###_Members`.Membertype_ref = `###_Membertype`.id
                                            AND `###_Membertype`.isCancelled_yn =0
                                            AND `###_Members_Attributes`.MemberID = `###_Members`.MemberID
                                            AND `###_Members_Attributes`.Attributes_ref =4"),
                        //HINT: lang("Informations send per letter");
                        );

    function vStatistics($db, $memberID,$smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->memberID = $memberID;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->mlist = new Listing("memberstatisticlist",
                                array(
//                                     "selectRowsFlg" => true,
//                                     "selectedRows" => (getConfigEntry($this->db, "CheckedCheckboxes") == 1 ? "ALL" : "NONE"),
                                    "columnNames" => array ("StatisticText" => lang("Statistic"),
                                                            "Value" => lang("Value")),
                                    "maxRowsPerPage" => getConfigEntry($this->db, "MaxRowsPerPage"),
                                    "linkParams" => "mod=queries&view=Statistics"));

        for( $i=0 ; $i < count($this->queryArr); $i++)
        {
            $query =  &$this->queryArr[$i];
            $queryVal = $db->GetOne($query["Sql"]);
            $this->mlist->addRow(lang($query["Description"]), $queryVal);
        }

		$tmpSort = getGlobVar('sort', '::text::');

		#TODO: Sort doesn't work with class listing yet !
		if ( !empty($tmpSort) )
		{
			$this->mlist->setConfig('sort', $tmpSort);
		}

    }

    function getSmartyTemplate()
    {
        return 'queries/v_Statistics.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->mlist->prepareRecordList('');
        $this->smarty->assign_by_ref('Statistics', $this->mlist);

    }

        /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        debug("M_QUERIES", "[v_Statistics, doAction] ACTION: $action");
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
    }

//     function displayView()
//     {
//
//         echo "<input type=hidden name=\"MemberID\" Value=\"$this->memberID\">";
// //             $this->mlist->showTable();
//     }
}
?>
