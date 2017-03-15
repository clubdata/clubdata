<?php
/**
 * Clubdata Administration Modules (View Admin)
 *
 * Contains the class to view the admin selection
 * This is used for administrative purposes, view all possible administration tasks
 * see template: admin/v_Admin.inc.tpl
 * This class is called by Class Admin.
 *
 * @package Admin
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show administration menu
 *
 * @package Admin
 */
class vAdmin extends CdBase{

    function vAdmin($db, &$smarty, &$formsgeneration)
    {
         $_SESSION['navigator_menu'] = 'ADMIN';
         return Cdbase::Cdbase();
    }

    function getSmartyTemplate()
    {
        return("admin/v_Admin.inc.tpl");
    }

}

?>