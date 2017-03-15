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
 * Updates Clubdata from V2.00 to V2.01
 * @package Update
 */
class Update_V2_00 extends Update
{
 
  var $sqlArr = array(
      "ALTER TABLE `###_Members_Conferences` DROP PRIMARY KEY , ADD UNIQUE `###_MemberConferencesUIDX` ( `MemberID` , `Conferences_ref` )",
      "ALTER TABLE `###_Members_Conferences` ADD `SubscriptionID` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST",
  );

  var $newVersion = "V2.01";

  function Update_V2_00($db)
  {
      Update::Update($db);
  }
}
?>