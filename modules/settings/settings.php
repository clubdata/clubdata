<?php
/**
 * Clubdata Settings Modules
 *
 * Contains classes to set parameters in Clubdata.
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
if (defined('SETTINGS_CLASS')) {
    return 0;
} else {
    define('SETTINGS_CLASS', TRUE);
}

require_once('include/function.php');
require_once('include/membertype_dep.php');
require_once('include/cdbase.class.php');

/**
 * The Clubdata Settings module class
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.2 $
 * @package Settings
 */
class CdSettings extends CdBase
{

    /**
      * @var stores object of column view
      */
    var $columnsViewObj;

    /**
    * Constructor of class Settings
    *
    * @return nothing
    */
    function CdSettings()
    {
        CdBase::CdBase();

        // unset MemberID, as it would be preset to the settings form
        // we don't need it here
        unset($GLOBALS['MemberID']);

        $this->view = getGlobVar("view","Settings|Columns|Personal","PG");

        $this->setAktView($this->view);
    }

    function getDefaultView()
    {
        return 'Settings';
    }

    /**
    * get name of Module
    * @return TEXT : Name of module
    */
    function getModuleName()
    {
        return "settings";
    }

    /**
    * Determines the permissions of the current user for this class
    * Might configure environment to ensure, the user only sees what he is allowed to see.
    *
    * @return integer false: User doesn't have permission, true: User has permission
    */
    function getModulePerm()
    {
        if ( !isLoggedIn() || getClubUserInfo("MemberOnly") === true )
        {
            return false;
        }

        // Column view is already loaded at startup
        $viewObjName = "v" . $this->view;
        $this->viewObj = new $viewObjName($this->db, $this);

        return true;
    }

    /**
    * Determines the tabulators to display
    * @return array key: name of tabulator, value: link to access tabulator
    */
    function getTabulators()
    {
        $la = array();
        return $la;
    }

    /**
    * Returns an array of text (HTML) to be displayed as header.
    * The return value must be an array. The values are displayed side by side.
    * @return array text (HTML) to be displayed as header
    */
    function getHeaderText()
    {
    	switch ($this->view)
    	{
    		case 'Settings':
        		$headTxt = lang("Settings");
        		break;
        		
    		case 'Columns':
        		$headTxt = lang("Select Columns");
    			break;
    			
    		case 'Personal':
        		$headTxt = lang("Personal settings");
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
            case "Columns":
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Select all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"SetChecked(1,'');",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_1",
                        "NAME"=>"Submit_1",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"SetChecked(0,''); return false;",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_2",
                        "NAME"=>"Submit_2",
                        "VALUE"=>lang("Reset to predefined"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('settings','$this->view','PREDEFINED');",
                        "SubForm"=>"buttonbar"
                ));
                break;

            case 'Personal':
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Save entry"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('settings','Personal','UPDATE');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"MemberReset",
                        "NAME"=>"MemberReset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
                break;

            default:
                break;
        }
        return $cols;
    }

    /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        if ( $this->view == "Columns" && $action == 'PREDEFINED' )
        {
            $this->viewObj->setDefaultCols(getConfigEntry($this->db, "DefaultCols"));
            unset($_POST["DisplayCols"]);
        }

        if ( method_exists($this->viewObj, 'doAction') )
        {
            $this->viewObj->doAction($action);
        }

        return true;
    }
}