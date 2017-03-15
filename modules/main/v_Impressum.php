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
 * class to show impressum of clubdata
 * @package Main
 */
class vImpressum extends CdBase{

    var $smarty;
    var $formsgeneration;

    function vImpressum($db, &$smarty, &$formsgeneration)
    {
        $this->db = $db;
        $this->smarty = &$smarty;
        $this->formsgeneration = &$formsgeneration;
    }
    
    function getSmartyTemplate()
    {
        return("main/v_Impressum.inc.tpl");
    }

    function setSmartyValues()
    {
        global $version;
        
        $this->smarty->assign('version', $version);
    }

}

?>