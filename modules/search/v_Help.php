<?php
/**
 * Clubdata Search Modules (View Email)
 *
 * Contains the class to search for members to which should be send an email.
 * By default the Attribute "Member wants infos per email" is predefined for this search
 *
 * @package Search
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
/**
 * Class to show help for search tasks
 *
 * @package Search
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
            showHelp($this->db, "NAVIGATOR", "HLP_search");
        }
        

}
?>