<?php
/**
 * Clubdata Administration Modules (View Edit)
 *
 * Contains the class to edit a database record
 * This is used for administrative purposes, to edit a record
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
 * Class to edit an entry of an administrative database table
 *
 * @package Admin
 */
class vEdit {
    var $db;
    var $table;
    var $tableObj;
    var $key;
    var $smarty;
    var $formsgeneration;

    function vEdit($db, $table, $key, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->table = $table;
        $this->key = $key;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $key = is_numeric($this->key) ? $this->key : "'$this->key'";

        $this->tableObj = new DbTable($this->db, $this->formsgeneration, "`###_" . $this->table . "`", "id = $key");
        $this->formsgeneration->AddInput(array(
                            "TYPE"=>"hidden",
                            "NAME"=>'Table',
                            "ID"=>'Table',
                            "VALUE"=>$this->table,
                            ));

        $this->tableObj->editRecord();
    }

    function getSmartyTemplate()
    {
        return 'admin/v_Edit.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction()
    {
        $this->tableObj->updateRecord();
    }
}
?>
