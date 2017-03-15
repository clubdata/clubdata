<?php
/**
 * Clubdata Fees Modules
 *
 * Contains classes to administer fees in Clubdata.
 *
 * @package Fees
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show help for fees tasks
 *
 * @package Fees
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
        showHelp($this->db, "NAVIGATOR", "HLP_fees");
    }


}
?>