<?php
/**
 * Clubdata Help Modules
 *
 * Contains the classes of the main menu
 *
 * @package Help
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */
/*
    modules/Help/Help.php: Help window with version and Copyright-Infos
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
 * Module class of help menu
 *
 * @package Help
 */
class CdHelp extends CdBase {
    var $view;
    var $viewObj;

    function CdHelp()
    {
        CdBase::CdBase();

/*            $view = getGlobVar("view",
                                "Copyright|Logoff|Help",
                                "PG");
*/
        if ( empty($view) )
        {
            $view = "Help";
        }
        $this->setAktView($view);

        return true;
    }

    function getModuleName()
    {
        return "help";
    }

    function getDefaultView()
    {
        return 'Help';
    }
/**/
    function getModulePerm($action)
    {
        $viewObjName = "v" . $this->view;
        if ( class_exists($viewObjName) )
        {
            $this->viewObj = new $viewObjName();
        }
        debug('M_HELP', "PERM: View: $this->view");
        return true;
    }
/**/
    function getNavigationElements()
    {
        $cols = array();
        return $cols;
    }

    /**
    *
    * displays the page.
    */
    function display()
    {
        $this->viewObj->display();
    }
}
?>