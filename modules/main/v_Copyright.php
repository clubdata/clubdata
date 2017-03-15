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

/**
 * class to show copyright of clubdata
 * @package Main
 */
class vCopyright extends CdBase{

    var $smarty;
    var $formsgeneration;

    function vCopyright($db, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;
    }
    
    function getSmartyTemplate()
    {
        return("main/v_Copyright.inc.tpl");
    }

    function setSmartyValues()
    {
        $this->smarty->assign_by_ref('CopyrightTxt', file_get_contents("COPYING"));
    }

}

?>