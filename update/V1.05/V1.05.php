<?php
/**
 * Clubdata Update Modules
 *
 * Contains the class to Update Clubdata from V1.05 to V2.00
 *
 * @package Update
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require('update/V1.01/V1.01.php');

//
// Nothing special to do for this version, so just call parents function
//
/**
 * Updates Clubdata from V1.05 to V2.00
 * @package Update
 */
class Update_V1_05 extends Update_V1_01
{
    function Update_V1_05($db)
    {
        Update_V1_01::Update_V1_01($db);

        exit;
    }
}
?>