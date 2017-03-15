<?php
/**
 * Clubdata Jobs Modules
 *
 * Contains classes to perform jobs on datas in Clubdata.
 * (e.g. End of Year tasks)
 *
 * @package Jobs
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */

if (defined('JOBS_CLASS')) {
    return 0;
} else {
    define('JOBS_CLASS', TRUE);
}

require_once("include/function.php");
require_once("include/membertype_dep.php");
require_once("include/cdbase.class.php");

/**
* The Clubdata Queries Class
*
* @author Franz Domes <franz.domes@gmx.de>
* @version $Revision: 1.3 $
* @package Jobs
*/
class CdJobs extends CdBase
{
    /** @var name of State type to display*/
    var $state;

    /** @var current queries object*/
    var $mqueries;

    /**
    * Constructor of class Queries
    * @return integer Always OK
    */
    function CdJobs()
    {
        CdBase::CdBase();

        $this->view = getGlobVar("view","Jobs|EndOfYear|Help","PG");

        $this->setAktView($this->view);
    }

    function getDefaultView()
    {
        return 'EndOfYear';
    }

    function getModuleName()
    {
        return "jobs";
    }

    /**
    * Determines the permissions of the current user for this class
    * Might configure environment to ensure, the user only sees what he is allowed to see.
    * @return integer false: User doesn't have permission, true: User has permission
    */
    function getModulePerm()
    {
        if ( !isLoggedIn() || getClubUserInfo("MemberOnly") === true )
        {
            return false;
        }
        if (  getUserType(UPDATE, "Payments") || getUserType(UPDATE, "Fees") )
        {
            $viewObjName = "v" . $this->view;
            $this->viewObj = new $viewObjName($this->db, $this->smarty, $this->formsgeneration);

            return true;
        }
        return false;
    }

    function getTabulators()
    {
        $la = array();

//        if (  getUserType(UPDATE, "Payments") || getUserType(UPDATE, "Fees") )
//        {
//          $la["EndOfYear"] = lang("End of Year Updates");
//          $la["Help"] = lang("Help");
//        }
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

        switch($this->view)
        {
            case 'Help':
                $headTxt = lang("Jobs help");
                break;
                                
			case 'EndOfYear':
				$headTxt = $this->viewObj->getActHeaderText();
                break;

            case 'Jobs':
            default:
            	$headTxt = lang("Jobs");
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
        if (  !(getUserType(UPDATE, "Payments") || getUserType(UPDATE, "Fees")) )
        {
          return false;
        }
        
        switch($this->view)
        {
            case 'Help':
                break;
                
                
			case 'EndOfYear':
                    $this->buttons->AddInput(array(
                            "TYPE"=>"submit",
                            "ID"=>"Submit",
                            "NAME"=>"Submit",
                            "VALUE"=>lang($this->viewObj->getActButtonText()),
                            "CLASS"=>"BUTTON",
                            "ONCLICK"=>"doAction('jobs','$this->view','StartJob');",
                            "SubForm"=>"buttonbar"
                    ));
                break;
            case 'Jobs':
                break;
        }
    }

    /**
    * saves values passed via POST
    * @return boolean true : save ok, false: error
    */
    function doAction($action)
    {
        $this->viewObj->doAction($action);
    }
}