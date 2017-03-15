<?php
/**
 * Clubdata Member Modules
 *
 * Contains the class to list and manipulate conferences participation for a member
 * The views which are called by this class correspond to the tabs shown on the member page
 *
 * @package Members
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * @package Members
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
            showHelp($this->db, "NAVIGATOR", "HLP_member");
        }
        

}
?>