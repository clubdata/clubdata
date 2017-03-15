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
 * class to log off from clubdata
 * @package Main
 */
class vLogoff extends CdBase{

    function vMain()
    {
        debug('MAIN', 'v_Logoff: displayView');
        return Cdbase::Cdbase();
    }
    
    function getSmartyTemplate()
    {
//         session_destroy();
        global $auth;

        $auth->logout();
        return("main/v_Logoff.inc.tpl");
    }
}

?>
