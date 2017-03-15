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
 * Updates Clubdata from V2.01a to V2.01b
 * @package Update
 */
class Update_V2_01a extends Update
{

  var $sqlArr = array(
      "UPDATE `###_Addresstype` SET `LetterFields_ml` = 'Addresses_1.Salutation_ref AS \$lAddressL1, CONCAT_WS('' '', Addresses_1.Title, Addresses_1.Firstname, Addresses_1.Lastname) AS \$lAddressL2, Addresses_1.Address AS \$lAddressL3, CONCAT_WS('' '', Addresses_1.ZipCode , Addresses_1.Town) AS \$lAddressL4, Addresses_1.Country_ref AS \$lAddressL5' WHERE `###_Addresstype`.`id` =1",
      "UPDATE `###_Addresstype` SET `LetterFields_ml` = 'Addresses_2.FirmName_ml as \$lAddressL1, Addresses_2.FirmDepartment as \$lAddressL2, CONCAT_WS('' '', Addresses_1.Salutation_ref, Addresses_1.Title, Addresses_1.Firstname, Addresses_1.Lastname) AS \$lAddressL3, Addresses_2.Address AS \$lAddressL4, CONCAT_WS('' '', Addresses_2.ZipCode , Addresses_2.Town) AS \$lAddressL5, Addresses_2.Country_ref AS \$lAddressL6' WHERE `###_Addresstype`.`id` =2",
      "?ALTER TABLE `###_Users` ADD `PersonalSettings_ro` VARCHAR( 1024 ) NULL COMMENT 'Personal setting, changeable by user'",
  );

  var $newVersion = "V2.01b";

  function Update_V2_01a($db)
  {
      Update::Update($db);
  }
}
?>