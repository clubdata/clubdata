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
if (defined('QUERIES_CLASS')) {
    return 0;
} else {
    define('QUERIES_CLASS', TRUE);
}

require_once("include/function.php");
require_once("include/membertype_dep.php");
require_once("include/cdbase.class.php");

/**
 * The Clubdata Queries Class
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @package Queries
 */
class CdQueries extends CdBase
{
    /** @var name of command type to display*/
    var $command;

    /** @var current queries object*/
    var $mqueries;

    /**
    * Constructor of class Queries
    * @return integer Always OK
    */
    function CdQueries()
    {
        CdBase::CdBase();

        $this->view = getGlobVar("view","Queries|MemberSummary|Statistics|Payments|AddressLists|Help","PG");

        $this->command = getGlobVar("Command");

        $showbutton = getGlobVar("showbutton");
        $quicksearch = getGlobVar("quicksearch");

        $this->setAktView($this->view);
    }

    function getDefaultView()
    {
        return 'MemberSummary';
    }

    function getModuleName()
    {
        return "queries";
    }

    function getSmartyTemplate()
    {
        if ( method_exists($this->viewObj, 'getSmartyTemplate') )
            return $this->viewObj->getSmartyTemplate();

        return CdBase::getSmartyTemplate();
    }

    function setSmartyValues()
    {
        if ( method_exists($this->viewObj, 'setSmartyValues') )
            return $this->viewObj->setSmartyValues();

        return CdBase::setSmartyValues();
    }

    /**
    * Determines the permissions of the current user for this class
    * Might configure environment to ensure, the user only sees what he is allowed to see.
    * @return integer false: User doesn't have permission, true: User has permission
    */
    function getModulePerm()
    {
        if ( !isLoggedIn() )
        {
            return false;
        }
        if ( $this->view == "Queries" || $this->view == "AddressLists" ||
             ($this->view == "MemberSummary" ||
              $this->view == "Statistics"
              ) && getUserType(VIEW, "Member") && getClubUserInfo("MemberOnly") === false )
        {
            $viewObjName = "v" . $this->view;
            $this->viewObj = new $viewObjName($this->db, $this->command, $this->smarty, $this->formsgeneration);

            return true;
        }
        return false;
    }

    function getTabulators()
    {
        $la = array();

//        if ( getClubUserInfo("MemberOnly") === false )
//        {
//	        $la["MemberSummary"] = lang("Member summary");
//	        $la["Statistics"] = lang("Statistics");
//        }
//        $la["AddressLists"] = lang("Addresslists");
        return $la;

    }
    /**
    * Returns an array of text (HTML) to be displayed as header.
    * The return value must be an array. The values are displayed side by side.
    * @return array text (HTML) to be displayed as header
    */
    function getHeaderText()
    {
        $headTxt = array();
        switch ($this->command)
        {
            default:
                $headTxt = lang("Queries");
                break;

        }
        return array($headTxt);
    }

    /**
    * Returns an array of elements to be displayed in the navigation bar
    * The return value must be an array. The values are displayed side by side.
    * e.g.
    *   nav[0]['type'] = "button" // or imgage or input or text or hidden or submit or reset
    *   nav[0]['file'] = 'filename' // if type = image
    *   nav[0]['name'] = 'elementname'
    *   nav[0]['link'] = link_to_result
    *   nav[0]['default'] = Standardwert
    *   nav[0]['javascript'] = javascript code

    * The navigation elements must be displayed in this order
    * @return array of assiciative array to display in navigator bar
    */
    function getNavigationElements()
    {
        $cols = array();
        switch($this->view)
        {
            case 'AddressLists':
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_PDF",
                        "NAME"=>"Submit_PDF",
                        "VALUE"=>lang("Export to PDF"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('queries','$this->view','PDF');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Excel",
                        "NAME"=>"Submit_Excel",
                        "VALUE"=>lang("Export to Excel"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('queries','$this->view','EXCEL');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Display",
                        "NAME"=>"Submit_Display",
                        "VALUE"=>lang("Display list"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('queries','$this->view','DISPLAY');",
                        "SubForm"=>"buttonbar"
                ));
/*                array_push($cols,array ( 'type' => 'submit',
                                    'name' => 'Action',
                                    'label' => lang("Public Addresslist"),
                                    'value' => 'PublicAddress',
                        ));
*/
                break;

            case 'Queries':
                break;
                
            default:
/*                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Select all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('queries','$this->view','SELECTALL');",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_1",
                        "NAME"=>"Submit_1",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('queries','$this->view','DESELECTALL');",
                        "SubForm"=>"buttonbar"
                ));
*/
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Excel",
                        "NAME"=>"Submit_Excel",
                        "VALUE"=>lang("Export to Excel"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('queries','$this->view','EXCEL');",
                        "SubForm"=>"buttonbar"
                ));
                break;
//
        }
        return $cols;
    }

    /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        $this->viewObj->doAction($action);
    }

    /**
    * Returns a text (HTML) to be displayed at the main part of the window.
    * This text is only shown, if no view file exists
    * @return string text (HTML) to be displayed
    */
    function getViewText()
    {
        return "Invalid";
    }

}