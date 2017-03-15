<?php
/**
 * Clubdata Search Modules
 *
 * Contains classes to search data in Clubdata.
 *
 * TODO: Conferences support
 *
 * @package Search
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
if (defined('SEARCH_CLASS')) {
    return 0;
} else {
    define('SEARCH_CLASS', TRUE);
}

require_once('include/function.php');
require_once('include/membertype_dep.php');
require_once('include/cdbase.class.php');
require_once('modules/settings/v_Columns.php');

/**
 * The Clubdata Search module class
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @package Search
 */
class CdSearch extends CdBase
{

    /**
      * @var stores object of column view
      */
    var $columnsViewObj;

    /**
      * @var type of search, currently one of email, infoletter, invoice, canceledByEndOfYear
      */
    var $searchtype;

    /**
      * @var SearchMode: Simple or Advanced
      */
    var $searchmode;


    /**
    * Constructor of class Search
    *
    * @return nothing
    */
    function CdSearch()
    {
        CdBase::CdBase();

        // unset MemberID, as it would be preset to the search form
        // we don't need it here
        unset($GLOBALS['MemberID']);

        $this->view = getGlobVar("view","Search|Conferences|Member|Email|Infoletter|Invoice|Payments|Fees|Columns|Help","PG");
        $this->searchtype = getGlobVar("searchtype","email|infoletter|invoice|canceledByEndOfYear","PGS");
        $this->setSearchMode();

        $this->columnsViewObj = new vColumns($this->db, $this->smarty, $this->formsgeneration);

        $this->setAktView($this->view);
    }

    function setSearchMode($searchmode = "")
    {
        // If not set via parameter get HTTP variable
        if ( empty($searchmode) )
        {
            $searchmode = getGlobVar("searchmode","Simple|Advanced","PGS");
        }
        // If still not set, reset to default
        if ( empty($searchmode) )
        {
            $searchmode = (($this->view == "Payments") ? "Payments" : "Simple");
        }
        $this->searchmode = $searchmode;
        $_SESSION["searchmode"] = $this->searchmode;
    }

    function getDefaultView()
    {
        return 'Search';
    }

    /**
    * get name of Module
    * @return TEXT : Name of module
    */
    function getModuleName()
    {
        return "search";
    }

    /**
    * Determines the permissions of the current user for this class
    * Might configure environment to ensure, the user only sees what he is allowed to see.
    *
    * @return integer false: User doesn't have permission, true: User has permission
    */
    function getModulePerm()
    {
      $perm = false;

        if ( !isLoggedIn() || getClubUserInfo("MemberOnly") === true )
        {
            return false;
        }

        debug('M_SEARCH', "[Search, getModulePerm] VIEW: $this->view");
        switch ( $this->view )
        {
          case 'Search':
            $perm = true;
            break;

          case 'Infoletter':
          case 'Email':
            $perm = getUserType("Create",$this->view);
            break;

          case 'Invoice':
            $perm = getUserType(VIEW, "Payments");
            break;

          default:
            $perm = getUserType(VIEW, $this->view);
            break;
        }
        if ( $perm )
        {
          // Column view is already loaded at startup
          if ( $this->view != "Columns" )
          {
              $viewObjName = "v" . $this->view;
              $this->viewObj = new $viewObjName($this->db, $this->searchmode, $this->smarty, $this->formsgeneration);
          }
          else
          {
              $this->viewObj = $this->columnsViewObj;
          }
        }
        return $perm;
    }

    /**
    * Determines the tabulators to display
    * @return array key: name of tabulator, value: link to access tabulator
    */
    function getTabulators()
    {
//        $la["Member"] = lang("Member");
//        $la["Email"] = lang("Email");
//        $la["Infoletter"] = lang("Infoletter");
//        $la["Invoice"] = lang("Invoice Mailing");
//        $la["Columns"] = lang("Columns");
//        $la["1"] = "";
//        $la["Payments"] = lang("Payments");
//        $la["Fees"] = lang("Fees");
//        $la["2"] = "";
//        $la["Help"] = lang("Help");

        return $la;
    }

    /**
    * Returns an array of text (HTML) to be displayed as header.
    * The return value must be an array. The values are displayed side by side.
    * @return array text (HTML) to be displayed as header
    */
    function getHeaderText()
    {
        if ( method_exists($this->viewObj, 'getHeaderText') )
        {
          $headTxt = $this->viewObj->getHeaderText();
        }
        else
        {
          switch ($this->view)
          {
              case "CanceledByEndOfYear":
                  $headTxt = lang("Selection for Canceled memberships");
                  break;

              default:
                  $headTxt = lang("Searching...");
                  break;
          }
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
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"SetChecked(0,''); return false;",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_2",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Reset to predefined"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('search','$this->view','PREDEFINED');",
                        "SubForm"=>"buttonbar"
                ));
                break;

            case "Payments":
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"PaymentsSubmit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Start search for payments"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doSubmit('payments','List');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"PaymentsReset",
                        "NAME"=>"PaymentsReset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
                break;

            case "Conferences":
                	$this->buttons->AddInput(array(
				                	"TYPE"=>"submit",
				                	"ID"=>"ConferencesSubmit",
				                	"NAME"=>"Submit",
				                	"VALUE"=>lang("Start search for Conferences"),
				                	"CLASS"=>"BUTTON",
				                	"ONCLICK"=>"doSubmit('conferences','List');",
				                	"SubForm"=>"buttonbar"
                			));
             			$this->buttons->AddInput(array(
                					"TYPE"=>"button",
                					"ID"=>"ConferencesReset",
                					"NAME"=>"ConferencesReset",
                					"CLASS"=>"BUTTON",
                					"VALUE"=>lang("Reset"),
                					"ONCLICK"=>"reset();",
                					"SubForm"=>"buttonbar"
                			));
           			break;
                
                
            case "Fees":
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"FeesSubmit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Start search for membership fees"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doSubmit('fees','List');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"FeesReset",
                        "NAME"=>"Reset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
                break;

            case 'Invoice':
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"MemberSubmit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Start search for Member"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"return askInvoiceData();", /* doSubmit('list','Invoice');", */
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"MemberReset",
                        "NAME"=>"Reset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
                $l_searchmode = $this->searchmode == "Advanced" ? lang("Simple search") : lang("Advanced search");
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"searchmode",
                        "NAME"=>"Submit_2",
                        "VALUE"=>$l_searchmode,
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('search','$this->view','" . ($this->searchmode == 'Advanced' ? 'SIMPLE' : 'ADVANCED') . "');",
                        "SubForm"=>"buttonbar"
                ));
                break;

            case 'Member':
            case 'Email':
            case 'Infoletter':
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"MemberSubmit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Start search for Member"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doSubmit('list','Memberlist');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"button",
                        "ID"=>"MemberReset",
                        "NAME"=>"Reset",
                        "CLASS"=>"BUTTON",
                        "VALUE"=>lang("Reset"),
                        "ONCLICK"=>"reset();",
                        "SubForm"=>"buttonbar"
                ));
                $l_searchmode = $this->searchmode == "Advanced" ? lang("Simple search") : lang("Advanced search");
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"searchmode",
                        "NAME"=>"Submit_2",
                        "VALUE"=>$l_searchmode,
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('search','$this->view','" . ($this->searchmode == 'Advanced' ? 'SIMPLE' : 'ADVANCED') . "');",
                        "SubForm"=>"buttonbar"
                ));
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
        if ( $action == 'ADVANCED' )
        {
            $this->setSearchMode("Advanced");
            $this->viewObj->setSearchMode($this->searchmode);
        }
        elseif ( $action == 'SIMPLE' )
        {
            $this->setSearchMode("Simple");
            $this->viewObj->setSearchMode($this->searchmode);
        }
        elseif ( $this->view == "Columns" && $action == 'PREDEFINED' )
        {
            $this->viewObj->setDefaultCols(getConfigEntry($this->db, "DefaultCols"));
            unset($_POST["DisplayCols"]);
        }
        return true;
    }
}