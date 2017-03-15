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
 * Updates Clubdata from V2.02 to V2.03
 * @package Update
 */
class Update_V2_02 extends Update
{

  var $sqlArr = array(
  "ALTER TABLE `###_Help` DROP PRIMARY KEY",
  "ALTER TABLE `###_Help` ADD UNIQUE (`Category` ,`Subcategory`) ",
  "ALTER TABLE `###_Help` ADD `id` TINYINT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST",
  "CREATE TABLE `###_Help_Backup` AS SELECT * FROM `###_Help`",
  );

  var $newVersion = "V2.03";

  function Update_V2_02($db)
  {
    Update::Update($db);

    print("<div class='hint'>Updating help data: <BR>");
    if( false === ($structSQL = file_get_contents(SCRIPTROOT .'/Installation/Clubdata2-help.mysql.sql')))
    {
    	print("<b>Cannot read " . SCRIPTROOT .'/Installation/Clubdata2-help.mysql.sql' . "</b>");
    }
    else
    {
    	// Split only on ; at end of line, as there are also semicolons in the text fields
    	$structSQLArr = preg_split("/;\s*\n/", $structSQL, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    	foreach ( $structSQLArr as $sql)
    	{
    		$sql = trim($sql);
    		if ( !empty($sql) && $sql != '' )
    		{
    			if ( ($ok = $db->Execute($sql)) === false )
    			{
    				print("<b>Error: Cannot Execute SQL from Clubdata2-help.mysql.sql<BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</b>");
    				break;
    			}
    		}
    	}
    	if ( $ok )
    	{
    		print("OK");
    	}
    }
    return $ok;
  }
}
?>