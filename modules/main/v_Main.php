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
 * class for the main view of Clubdata
 * @package Main
 */
class vMain extends CdBase{

    function vMain()
    {
        $_SESSION['navigator_menu'] = 'MAIN';
        return Cdbase::Cdbase();
    }

    function getSmartyTemplate()
    {
        return("main/v_Main.inc.tpl");
    }

}

?>