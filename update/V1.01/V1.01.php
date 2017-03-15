<?php
/**
 * Clubdata Update Modules
 *
 * Contains the class to Update Clubdata from V1.01 to V2.00
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
require('update/V1.00/V1.00.php');

//
// Nothing special to do for this version, so just call parents function
//
/**
 * Updates Clubdata from V1.01 to V2.00
 * @package Update
 */
class Update_V1_01 extends Update_V1_00
{
    function Update_V1_01($db)
    {
        Update_V1_00::Update_V1_00($db);

        exit;
    }
}
?>