<?php
/**
 * Clubdata Payments Modules
 *
 * Contains classes to administer payments in Clubdata.
 *
 * @package Payments
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show help for payment tasks
 *
 * @package Payments
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

    function getHeadTxt()
    {
      return lang("payment help");
    }

}
?>