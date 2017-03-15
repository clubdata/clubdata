<?php
/**
 * Clubdata Main Modules
 *
 * Contains the classes of the main menu
 *
 * @package Main
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/*
    modules/main/main.php: Main window with version and Copyright-Infos
    Copyright (C) 2003-2006 Franz Domes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/
/**
 *
 */
require_once("include/function.php");
require_once("include/cdbase.class.php");

/**
 * Module class of main menu
 *
 * @package Main
 */
class CdMain extends CdBase {
    var $view;
    var $viewObj;

    function CdMain()
    {
        CdBase::CdBase();

        $view = getGlobVar("view",
                            "Main|Copyright|Impressum|Logoff|Help",
                            "PG");

        if ( empty($view) )
        {
            $view = "Main";
        }
        $this->setAktView($view);

        return true;
    }

    function getModuleName()
    {
        return "main";
    }

    function getDefaultView()
    {
        return 'Main';
    }
/**/
    function getModulePerm($action)
    {
      $viewObjName = "v" . $this->view;
      if ( class_exists($viewObjName) )
      {
        $this->viewObj = new $viewObjName($this->db, $this->smarty, $this->formsgeneration);
      }
      return true;
    }
/**/
    function getNavigationElements()
    {
        $cols = array();
        return $cols;
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
    		case 'Impressum':
    				$headTxt = array(lang("Clubdata V2, the software for club member administration"));
    				break;
    		
    		case 'Copyright':
    				$headTxt = array(lang("Copyright"));
    				break;
    				
    		default:
    				$headTxt = parent::getHeaderText();
    				break;
    	}
    	
    	return $headTxt;
    }
}
?>