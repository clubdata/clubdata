<?php
/**
 * Clubdata Administration Modules
 *
 * Contains classes to administrate Clubdata.
 *
 * @package Admin
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
*/
if (defined('ADMIN_CLASS')) {
    return 0;
} else {
    define('ADMIN_CLASS', TRUE);
}

require_once("include/function.php");
require_once("include/cdbase.class.php");

/**
 * Module class to administrate Clubdata
 *
 * @package Admin
 */
class CdAdmin extends CdBase
{
    /**
     * @var array Array of tables which can be administrated by this module
     */
    var $tables = array('Log','Users','Configuration','Salutation','Membertype','Paymode','Paytype','Country',
                        'Attributes','Addresstype','Language','Mailingtypes', 'Help');

    /**
     * @var string Name of table without prefix
     */
    var $table;

    /**
     * @var string Name of table with prefix, if any
     */
    var $tableWithPrefix;


    /** @var handle database handle*/
    var $admintype;

    // AdminMode: Simple or Advanced
    var $adminmode;

    var $columnsViewObj;

    /**
     * @var integer Number of actual pages displayed
     */
    var $pageNr;

    /**
     * @var integer Id of entry to manipulate (parameter id)
     */
    var $key;

    /**
    * Constructor of class Admin
    * @return integer Always OK
    */
    function CdAdmin()
    {
        CdBase::CdBase();

        $this->view = getGlobVar("view","Database|Admin|Detail|Add|Edit|List|Help|Backup|" . join('|', $this->tables),"PG");
        $this->table = getGlobVar("Table", join('|', $this->tables), "PG");
        $this->key = getGlobVar("id");
        $this->pageNr = getGlobVar("PageNr", "::number::");
        if ( empty($this->pageNr) || $this->pageNr < 1 )
        {
            $this->pageNr = 1;
        }


        debug('M_ADMIN', "VIEW: $this->view, TABLE: $this->table");
        if ( empty($this->view) )
        {
            $this->view = "Users";
        }

        if ( $this->view == "Backup" || $this->view == "Admin" || $this->view == "Database")
        {
            debug('M_ADMIN', "VIEW/ADMIN: $this->view, TABLE: $this->table");
        }
        elseif ( empty($this->table) ||
                 $this->view != "List" && $this->view != "Add" && $this->view != "Help" &&
                 $this->view != "Edit" && $this->view != "Detail" && $this->view != "Delete")
        {
            $this->table = $this->view;
            $this->view = "List";
            $this->key = -1;         // Set initial view
            debug('M_ADMIN', "[CdAdmin] Initialize View");
        }
        $this->tableWithPrefix = clubdata_mysqli::replaceDBprefix("`###_" . $this->table . "`", DB_TABLEPREFIX);

        $this->setAktView($this->view);
    }

    function getDefaultView()
    {
        return 'List';
    }

    /**
    * get name of Module
    * @return TEXT : Name of module
    */
    function getModuleName()
    {
        return "admin";
    }

   /**
    * Determines the permissions of the current user for this class
    * Might configure environment to ensure, the user only sees what he is allowed to see.
    * @return integer false: User doesn't have permission, true: User has permission
    */
    function getModulePerm($action = '')
    {
        if ( /* !isLoggedIn() || */ getClubUserInfo("MemberOnly") === true || getUserType("ADMINISTRATOR") === false)
        {
            return false;
        }

        $viewObjName = "v" . $this->view;
        $this->viewObj = new $viewObjName($this->db, $this->table, $this->key, $this->smarty, $this->formsgeneration);
        return true;
    }

    /**
    * Determines the tabulators to display
    * @return array key: name of tabulator, value: link to access tabulator
    */
    function getTabulators()
    {
//        foreach($this->tables as $table)
//        {
//          // Users, Configuration and Log table are displayed elsewhere
//          if ( $table != "Log" && $table != "Users" && $table != "Configuration" )
//          {
//            $la[$table] = lang($table);
//          }
//        }
///*        $la["1"] = "";
//        $la["Log"] = lang("Log");
//        $la["2"] = "";
//        $la["Backup"] = lang("Backup");*/
//        $la["3"] = "";
//        $la["Help"] = lang("Help");
//
        return $la;
    }

    /**
    * Returns the current tabulator displayed
    * @return string name of current tabulator
    */
    function getCurrentView()
    {
        return ( $this->view == "List" ) ? $this->table : CdBase::getCurrentView();
    }

    /**
    * Returns an array of text (HTML) to be displayed as header.
    * The return value must be an array. The values are displayed side by side.
    * @return array text (HTML) to be displayed as header
    */
    function getHeaderText()
    {
//         debug('M_ADMIN', "TABLE: $this->table");
        switch ($this->view)
        {
            case "Database":
            case "Admin":
            case "Log":
            case "Backup":
                $headTxt = lang($this->view);
                break;

            default:
                switch ($this->table)
                {
                    case "Users":
                        $headTxt = lang("User administration");
                        break;
                    case "Log":
                        $headTxt = lang("View log entries");
                        break;
                    default:
                        $headTxt = lang($this->table);
                        break;

                }
                break;
        }
        return array($headTxt);
    }

    /**
    * Returns an array of elements to be displayed in the navigation bar
    * The return value must be an array. The values are displayed side by side.
    *
    * The navigation elements must be displayed in this order
    * @return array of assiciative array to display in navigator bar
    */
    function getNavigationElements()
    {
        $cols = array();
        switch ( $this->view )
        {
            case "List":
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Select all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('admin','$this->view','SELECTALL');",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_1",
                        "NAME"=>"Submit_1",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('admin','$this->view','DESELECTALL');",
                        "SubForm"=>"buttonbar"
                ));

                if ( $this->table != "Log" )
                {
                  $this->buttons->AddInput(array(
                          "TYPE"=>"submit",
                          "ID"=>"Submit_add",
                          "NAME"=>"Submit_add",
                          "VALUE"=>lang("Add entry"),
                          "CLASS"=>"BUTTON",
                          "ONCLICK"=>"doSubmit('admin','Add');",
                          "SubForm"=>"buttonbar"
                  ));
                }
                break;

            case "Edit":
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Update",
                        "NAME"=>"Submit_Update",
                        "VALUE"=>lang("Update entry"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('admin','$this->view','UPDATE');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"AdminReset",
                        "NAME"=>"AdminReset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
               break;

            case "Add":
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Insert",
                        "NAME"=>"Submit_Insert",
                        "VALUE"=>lang("Insert entry"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('admin','$this->view','INSERT');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"AdminReset",
                        "NAME"=>"AdminReset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
               break;

            default:
                if ( method_exists($this->viewObj, 'getNavigationElements') )
                {
                    $this->viewObj->getNavigationElements($this->buttons);
                }
                break;
        }

        return $cols;
    }

    function deleteLanguageColumns()
    {
      global $APerr;


      $tableArr = array('Addresstype', 'Attributes', 'Conferences', 'Configuration', 'Country', 'Help', 'InfoGiveOut',
                        'InfoWWW', 'Language', 'Mailingtypes', 'Membertype','Paymode', 'Paytype', 'Salutation');

      foreach ( $tableArr as $table )
      {
        $colNamesArr = $this->db->MetaColumns("###_" . $table);

        debug_r("DBTABLE", $colNamesArr, "[Admin, v_Add, deleteLanguageColumns] Table: $table, ColNamesArr:");

        foreach ( $colNamesArr as $colNameUpper => $column )
        {
          if ( !substr_compare(strtoupper($column->{'name'}), "_" . $this->key, -(strlen($this->key)+1)) )
          {
            $colName = $column->{'name'};
            $sql = "ALTER TABLE `###_{$table}` DROP `{$colName}`";
            debug("DBTABLE", "[Admin, v_Add, deleteLanguageColumns] Table: $table, ColName: $colName, SQL: $sql");
            if ( $this->db->Execute($sql) === false )
            {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql",sprintf(lang('Cannot alter table %1$s ! Please remove column %2$s manually'), $table, $colName));
            }
            else
            {
                logEntry('ALTER', $sql);
            }
          }
        }
      }
    }

    /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        global $APerr;

//         echo "ACTION: $action, VIEW: $this->view<BR>";
        switch ( $action )
        {
          case 'UPDATE':
          case 'INSERT':
            $this->viewObj->doAction($action);
            $this->setAktView("List");
            $this->getModulePerm();
          break;


          case 'DELETE':
            switch ( $this->table )
            {
              case 'Language':
                $this->deleteLanguageColumns();
                $sql = "DELETE FROM $this->tableWithPrefix WHERE id = '$this->key'";
                break;

              case 'Country':
                $sql = "DELETE FROM $this->tableWithPrefix WHERE id = '$this->key'";
                break;

              default:
                $sql = "DELETE FROM $this->tableWithPrefix WHERE id = $this->key";
                break;
            }
//             debug('M_ADMIN', "SQLCMD: $sql");
            $rs = $this->db->Execute($sql) or
                 $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            $this->setAktView("List");
            $this->getModulePerm();
            break;

          case 'BACKUP':
          default:
            $this->viewObj->doAction($action);
            break;
        }
        return true;
    }

}