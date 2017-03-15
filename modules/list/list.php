<?php
/**
 * Clubdata List Modules
 *
 * Contains classes to generate and display serveral lists
 *
 * @package List
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
if (defined('LIST_CLASS')) {
    return 0;
} else {
    define('LIST_CLASS', TRUE);
}

require_once('include/function.php');
require_once('include/membertype_dep.php');
require_once('include/cdbase.class.php');
require_once('include/dblist.class.php');

/**
 * The Clubdata List Class
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @package List
 */
class CdList extends CdBase
{
    /** @var string name of command type to display*/
    var $command;

    /** @var object current list object*/
    var $mlist;

    /** @var integer set to 1 if new view has to be initialized*/
    var $initView;

    /**
    * Constructor of class List
    * @return integer Always OK
    */
    function CdList()
    {
        CdBase::CdBase();

        //TODO: Add view Emaillist for emails (instead of Memberlist) to show correct menu
        $this->view = getGlobVar('view','Memberlist|Invoiceletter|Payments|Infoletter|Help','PG');

        $this->command = getGlobVar('Command');

        $showbutton = getGlobVar('showbutton');
        $quicksearch = getGlobVar('quicksearch');

        $this->initView = getGlobVar('InitView', '0|1', 'PG');

        $this->setAktView($this->view);
    }

    function getDefaultView()
    {
        return 'Memberlist';
    }

    function getModuleName()
    {
        return 'list';
    }

    /**
    * Determines the permissions of the current user for this class
    * Might configure environment to ensure, the user only sees what he is allowed to see.
    * @return integer false: User doesn't have permission, true: User has permission
    */
    function getModulePerm()
    {
        if ( !isLoggedIn() || getClubUserInfo('MemberOnly') === true )
        {
            return false;
        }
        if ( ($this->view == 'Memberlist' || $this->view == 'Infoletter') && getUserType(VIEW, 'Member') ||
             ($this->view == 'Payments' || $this->view == 'Invoiceletter') && getUserType(VIEW, 'Payments'))
        {
            $viewObjName = 'v' . $this->view;
            $this->viewObj = new $viewObjName($this->db, $this->command, $this->initView, $this->smarty, $this->formsgeneration);

            return true;
        }
        return false;
    }


    /**
    * Returns an array of text (HTML) to be displayed as header.
    * The return value must be an array. The values are displayed side by side.
    * @return array text (HTML) to be displayed as header
    */
    function getHeaderText()
    {
        switch ($this->command)
        {
            case 'Email':
                $headTxt = lang('Selection for mass email');
                break;

            case 'Infoletter':
                $headTxt = lang('Selection for info letter');
                break;

            case 'Invoice':
                $headTxt = lang('Selection for invoice');
                break;

            case 'CanceledByEndOfYear':
                $headTxt = lang('Selection for Canceled memberships');
                break;

            default:
                $headTxt = lang('List for members');
                break;

        }
        return array($headTxt);
    }

    /**
    * Returns an array of elements to be displayed in the navigation bar
    * The return value must be an array. The values are displayed side by side.
    * e.g.
    *   nav[0]['type'] = 'button' // or imgage or input or text or hidden or submit or reset
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
        $idTag = (($this->view == 'Memberlist') ? 'MemberID[]' : 'id[]');
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Select all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('list','$this->view','SELECTALL');",
                        "SubForm"=>"buttonbar"
                ));

                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_1",
                        "NAME"=>"Submit_1",
                        "VALUE"=>lang("Deselect all"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('list','$this->view','DESELECTALL');",
                        "SubForm"=>"buttonbar"
                ));
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Excel",
                        "NAME"=>"Submit_Excel",
                        "VALUE"=>lang("Export to Excel"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('list','$this->view','EXCEL');",
                        "SubForm"=>"buttonbar"
                ));
        if ( $this->command != 'Payments' )
        {
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_PDF",
                        "NAME"=>"Submit_PDF",
                        "VALUE"=>lang("Export to PDF"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('list','$this->view','PDF');",
                        "SubForm"=>"buttonbar"
                ));
        }

        switch ( $this->command )
        {
            case 'Email':
                $mailArr = getOptionArray('Mailingtypes', 'EMailOK = 1', true);
                $mailArr[''] = lang("Send email of type...");
                $this->buttons->AddInput(array(
                        "TYPE"=>"select",
                        "MULTIPLE"=>0,
                        "NAME"=>'Mailingtype',
                        "ID"=>'Mailingtype',
                        "SIZE"=>1,
                        "VALUE"=>'',
                        "OPTIONS"=>$mailArr,
                        "ONCHANGE"=>"doSubmit('email','Create'); submit();",
                        "SubForm"=>"buttonbar"
                        ));

                break;
            case 'Infoletter':
                $mailArr = getOptionArray('Mailingtypes', '', true);
                $mailArr[''] = lang("Send infoletter of type...");
                $this->buttons->AddInput(array(
                        "TYPE"=>"select",
                        "MULTIPLE"=>0,
                        "NAME"=>'Mailingtype',
                        "ID"=>'Mailingtype',
                        "SIZE"=>1,
                        "VALUE"=>'',
                        "OPTIONS"=>$mailArr,
                        "ONCHANGE"=>"doSubmit('list','Infoletter'); submit();",
                        "SubForm"=>"buttonbar"
                        ));
                break;

            case 'Invoice':
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit_Invoice",
                        "NAME"=>"Submit_Invoice",
                        "VALUE"=>lang("Export invoice data"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('list','Invoiceletter','INVOICE');",
                        "SubForm"=>"buttonbar"
                ));
                break;


			case 'CanceledByEndOfYear':
                $this->buttons->AddInput(array(
                        "TYPE"=>"submit",
                        "ID"=>"Submit",
                        "NAME"=>"Submit",
                        "VALUE"=>lang("Do Job"),
                        "CLASS"=>"BUTTON",
                        "ONCLICK"=>"doAction('jobs','v_EndOfYear','DOJOB');",
                        "SubForm"=>"buttonbar"
                ));
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

    /**
    * Returns a text (HTML) to be displayed at the main part of the window.
    * This text is only shown, if no view file exists
    * @return string text (HTML) to be displayed
    */
    function getViewText()
    {
        return 'Invalid';
    }

    function display()
    {
      if ( $this->view == 'Infoletter' )
      {
        $this->viewObj->display();
      }
      parent::display();
    }
}