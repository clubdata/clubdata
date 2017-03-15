<?php
/**
 * Clubdata Settings Modules
 *
 * Contains classes to set personal preferences in Clubdata.
 *
 * @package Settings
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/addresses.class.php');
require_once('include/dbtable.class.php');

/**
 * Class to set columns displayed in member lists
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.2 $
 * @package Settings
 */
class vPersonal extends Table {
    var $db;

    /**
     * @var object $module  Reference to calling module
     */
    var $module;

    var $table;
    var $columnNames;
    var $selectedFields;

    var $adrObj;

    var $idField  = '`###_Members`.`MemberID`';
    var $smarty;

    function vPersonal($db, &$module)
    {
        $this->module = &$module;

        parent::Table($formsgeneration);

        parent::Table($module->formsgeneration);

        $this->db = $db;
        $this->smarty = &$module->smarty;

        // Shortcut for convenience
        $this->personalSettings = &$module->personalSettings;

        $this->personalSettings->editRecord();

    }

    function getSmartyTemplate()
    {
        return 'settings/v_Personal.inc.tpl';
    }

    function setSmartyValues()
    {
      $this->smarty->assign_by_ref("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function doAction($action)
    {
        global $auth, $APerr;

        switch ( $action )
        {
            case 'UPDATE':
              $this->personalSettings->updateRecord();


              break;
        }
    }
}
?>