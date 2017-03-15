<?php
/**
 * Clubdata Administration Modules (View Help)
 *
 * Contains the class to view help on administration tasks
 * This is used for administrative purposes, to view help
 * This class is called by Class Admin.
 * TODO: This class does not have any functionality yet
 *
 * @package Admin
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show help for administrative tasks
 *
 * @package Admin
 */
    class vHelp {
        var $memberID;
        var $db;

        function vHelp($db, $memberID)
        {
            $this->db = $db;
            $this->memberID = $memberID;
        }

        function displayView()
        {
            showHelp($this->db, "NAVIGATOR", "HLP_Admin");
        }


}
?>