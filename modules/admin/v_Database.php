<?php
/**
 * Clubdata Administration Modules (View Database)
 *
 * Contains the class to make database backups
 * This is used for administrative purposes, to view all possible database manipulation tasks
 * see template: admin/v_Database.inc.tpl
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
 * Class to show menu for database table administration
 *
 * @package Admin
 */
class vDatabase extends CdBase{

    function vDatabase($db, &$smarty, &$formsgeneration)
    {
        return Cdbase::Cdbase();
    }

    function getSmartyTemplate()
    {
        return("admin/v_Database.inc.tpl");
    }

}

?>