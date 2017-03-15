<?php
/**
 * Clubdata Update Modules
 *
 * Contains the class to Update Clubdata from V2.00 to V2.01
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
require('include/update.class.php');

/**
 * Updates Clubdata from V2.01b to V2.02
 * @package Update
 */
class Update_V2_01b extends Update
{

  var $sqlArr = array(
  // do nothing, just adjust version number to new V2.02
  );

  var $newVersion = "V2.02";

  function Update_V2_01b($db)
  {
      Update::Update($db);
  }
}
?>