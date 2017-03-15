<?php
/**
 * Clubdata Administration Modules (View Detail)
 *
 * Contains the class to view details of a database record
 * This is used for administrative purposes, to view a database record in detail
 * This class is called by Class Admin.
 *
 * @package Admin
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once("include/dbtable.class.php");

/**
 * Class to show details of a row of a administratrive database table
 *
 * @package Admin
 */
class vDetail {
    var $db;
    var $table;
    var $tableObj;
    var $key;
    var $smarty;
    var $formsgeneration;


    function vDetail($db, $table, $key,&$smarty,&$formsgeneration)
    {
        $this->db = $db;
        $this->table = $table;
        $this->key = $key;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->tableObj = new DbTable($this->db, $this->formsgeneration, "`###_" . $this->table . "`", "id = '$this->key'");
        $this->tableObj->showRecord();
    }

    function getSmartyTemplate()
    {
        return 'admin/v_Detail.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->formsgeneration->AddInput(array(
                            "TYPE"=>"hidden",
                            "NAME"=>'Table',
                            "ID"=>'Table',
                            "VALUE"=>$this->table,
                            ));
        $this->formsgeneration->AddInput(array(
                            "TYPE"=>"hidden",
                            "NAME"=>'Table',
                            "ID"=>'Table',
                            "VALUE"=>$this->table,
                            ));


//         print("<PRE>" . $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl') . "</PRE>");
        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
    }
}
?>
