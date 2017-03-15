<?php
/**
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/clubdataFG.class.php');
require_once('include/personalSettings.class.php');

/**
 * The Clubdata Base class
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.7 $
 * @package Clubdata
 */
class CdBase
{
	/** @var handle database handle*/
	var $db;

	/**
	 * @var stores name of current view
	 */
	var $view;

	/**
	 * @var stores current view object
	 */
	var $viewObj;

	/**
	 * @var stores Smarty object
	 */
	var $smarty;

	/** stores formsgeneration object
	 * @var object
	 */
	var $formsgeneration;

	/** stores buttons object
	 * @var object
	 */
	var $buttons;

	/**
	 * Constructor of class CdBase
	 * @return integer Always OK
	 */
	function CdBase()
	{
		global $language, $db, $auth;

		$this->db = $db;
		$this->smarty = new ClubdataSmarty($this->db, $language);

		$this->formsgeneration = new clubdataFG();
		/*
		 * Give a name to hidden input field so you can tell whether the form is to
		 * be outputted for the first or otherwise it was submitted by the user.
		 */
		$this->formsgeneration->AddInput(array(
                                "TYPE"=>"hidden",
                                "NAME"=>"doit",
                                "VALUE"=>1,
		));

		$this->buttons = new clubdataFG();
        $this->personalSettings = new PersonalSettings($this->db, $this->formsgeneration, $auth);

		debug_r('MAIN', array("GET" => $_GET, "POST" => $_POST), "CDBASE::CDBASE" );

		return true;
	}

	/**
	 * get name of Module
	 * @return TEXT : Name of module
	 */
	function getModuleName()
	{
		return "";
	}

	/**
	 * Determines the permissions of the current user for this class
	 * Might configure environment to ensure, the user only sees what he is allowed to see.
	 * @param $action name of action to perform, or empty for default action (=view)
	 * @return integer false: User doesn't have permission, true: User has permission
	 */


	function getModulePerm($action = '')
	{
		return false;
	}

	/**
	 * Determines the tabulators to display
	 * @return array key: name of tabulator, value: link to access tabulator
	 */
	function getTabulators()
	{
		return array();
	}

	/**
	 * get name of default view
	 *
	 * @return string name of default view
	 */
	function getDefaultView()
	{
		return '';
	}


	/**
	 * Set the current view.
	 * Either the parameter view is set,
	 *   or the Session variable {modulename}view is set
	 *   or a default value is set
	 *
	 * @return nothing
	 */
	function setAktView($view)
	{
		global $APerr;

		$moduleName = $this->getModuleName();
		$sessionVarName = $moduleName . 'view';

		//          echo "SessionName: $sessionVarName<BR>";
		if ( empty($view) )
		{
			$view = empty($_SESSION[$sessionVarName]) ? $this->getDefaultView()
			: $_SESSION[$sessionVarName];
		}

		$_SESSION[$sessionVarName] = $view;

		/* Special treatment vor addresses views:
		 * The number indicates the type of the address
		 */
		if ( strncmp($view, 'Addresses_', 10) == 0 )
		{
			$this->addresstype = substr($view, 10);
			$view = 'Addresses';
		}
		$this->view = $view;


		if (file_exists(SCRIPTROOT . "/modules/$moduleName/v_$view.php") )
		{
			include_once(SCRIPTROOT . "/modules/$moduleName/v_$view.php");
		}
		else
		{
			unset($_SESSION[$sessionVarName]);
			$APerr->setFatal(__FILE__,__LINE__,lang('Cannnot find view file'),"Module: $moduleName, View: {$this->view}, File: " . SCRIPTROOT . "/modules/$moduleName/v_$view.php");

		}
		debug('MAIN', "VIEW: $view" );
	}

	/**
	 * Returns the current tabulator displayed
	 * @return string name of current tabulator
	 */
	function getCurrentView()
	{
		return $this->view;
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

	/**
	 * Returns the name of the smarty template to display
	 * Must be overloaded by derived classes
	 * @return name of smarty template file
	 */

	function getSmartyTemplate()
	{
		$template = NULL;
		if ( method_exists($this->viewObj, 'getSmartyTemplate') )
		$template = $this->viewObj->getSmartyTemplate();

		return empty($template) ? "general.inc.tpl" : $template;
	}


	/**
	 * Sets the Smarty parameter needed to show the page
	 * Must be overloaded by derived classes
	 * @return name of smarty template file
	 */
	function setSmartyValues()
	{
		if ( method_exists($this->viewObj, 'setSmartyValues') )
		return $this->viewObj->setSmartyValues();

		return;
	}

	/**
	 * Returns an array of text (HTML) to be displayed as header.
	 * The return value must be an array. The values are displayed side by side.
	 * @return array text (HTML) to be displayed as header
	 */

	function getHeaderText()
	{
		return array(lang('Welcome'));
	}

	/**
	 * NEW: SET BUTTONS VIA FORMSDEFINITION !!!
	 *
	 * The navigation elements must be displayed in this order
	 * @return array of assiciative array to display in navigator bar
	 */
	function getNavigationElements()
	{
		//DEFAULT:  display nothing
		return array();
	}

	/**
	 * executes action on module, e.g. saves values passed via POST, or start search.
	 * action may vary depending on parameter passed via POST
	 * @return boolean true : action ok, false: error
	 */
	function doAction($action)
	{
		return true;
	}

	/**
	 *
	 * displays the main section. May be forwarded to tabulator views.
	 */
	function displayMainSection()
	{
		return "Hallo";
	}

	/**
	 *
	 * displays the page.
	 *
	 * FD20100620: Add parameters $display and $template
	 *
	 * @param boolean $display true=call smarty::display, false= call smarty::fetch and return fetched page
	 * @param string $template name of template file to display or fetch
	 */
	function display($display = true, $template = 'main.tpl')
	{
		global $langFile;
		global $APerr;



		$this->smarty->assign('HEAD_Encoding', CHARACTER_ENCODING);
		$this->smarty->assign('HEAD_Title',
		lang("Club Member Administration") .
                                "(" . $this->getModuleName() . "/" .
		$this->getCurrentView() . ")");

		$this->smarty->assign('demoMode', DEMOMODE);
		
		/* get navigator view */
		$this->smarty->assign('navigatorMenu', $_SESSION['navigator_menu']);

		$this->smarty->assign('mainSectionInclude', $this->getSmartyTemplate());

		/* check if a module-specific button template exists.
		 (The inc is replaced by button)
		 If yes, fetch the output
		 else use the standard button bar
		 */
		$buttonInclude=str_replace(".inc.",".button.",$this->getSmartyTemplate());

		if ( $this->smarty->smartyTemplateExists($buttonInclude) )
		{
			$this->smarty->assign('buttonbar',
			$this->smarty->fetch($buttonInclude)
			);
		}
		else
		{
			$this->smarty->assign('buttonArr', $this->getNavigationElements());
			$this->smarty->assign('buttonbar',
			$this->buttons->processFormsGeneration($this->smarty, 'buttonbar.inc.tpl')
			);
		}

		/* check if a module-specific tabulator template exists.
		 (The inc is replaced by tabulator)
		 If yes, fetch the output
		 else use the standard tabulator bar
		 */
		$tabulatorInclude=str_replace(".inc.",".tabulator.",$this->getSmartyTemplate());

		if ( $this->smarty->smartyTemplateExists($tabulatorInclude) )
		{
			$this->smarty->assign('tabulatorInclude', $tabulatorInclude);
		}
		else
		{
			$this->smarty->assign('tabulatorInclude', 'tabulator.inc.tpl');
		}

		/* check if a module-specific header template exists.
		 (The inc is replaced by header)
		 If yes, fetch the output
		 else use the standard header bar
		 */
		$headerInclude=str_replace(".inc.",".header.",$this->getSmartyTemplate());

		if ( $this->smarty->smartyTemplateExists($headerInclude) )
		{
			$this->smarty->assign('headerInclude', $headerInclude);
		}
		else
		{
			$this->smarty->assign('headerInclude', 'headline.inc.tpl');
		}

		$this->setSmartyValues();

		debug_r('SMARTY', $this->smarty, "[CDBASE|display] smarty");
		$this->formsgeneration->AddInput(array(
                                "TYPE"=>"hidden",
                                "NAME"=>"Action",
                                "VALUE"=>'',
		));

		$this->smarty->assign('hiddenform', $this->processFormsGeneration('hidden.inc.tpl'));

		$this->smarty->assign('currentView', $this->getCurrentView());
		$this->smarty->assign('moduleName', $this->getModuleName());
		if ( !empty($this->memberID) )
		{
			$this->smarty->assign('MemberID', $this->memberID);
		}

		$this->smarty->assign("headArr", $this->getHeaderText());
		$this->smarty->register_object("modClass", $this);
		$this->smarty->assign_by_ref("APerr", $APerr);

		$this->smarty->assign('personalSettings', $this->personalSettings->getPersonalSettings());


		//         debug_r('MAIN', $this->formsgeneration, "[MAIN, display] formsgeneration");
		//         debug('MAIN', "[MAIN, display]: buttonbar: ". $this->smarty->get_template_vars('buttonbar'));
		//         debug('MAIN', "[MAIN, display]: forms: ". $this->smarty->get_template_vars('forms'));

		$this->smarty->assign('javascript', $this->formsgeneration->getJavascriptDefinition());
		$this->smarty->assign("formDefinition", $this->formsgeneration->getFormDefinition());

		//         debug('MAIN', "[MAIN, display]: formDefinition: ". $this->formsgeneration->getFormDefinition());

		return ($display ? $this->smarty->display($template) : $this->smarty->fetch($template));
	}

	function getFormsObject()
	{
		return $this->formsgeneration;
	}

	function processFormsGeneration($template = 'table.inc.tpl')
	{
		return $this->formsgeneration->processFormsGeneration($this->smarty, $template);
	}

}