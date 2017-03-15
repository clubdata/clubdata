<?php
/**
 * Membertype_dep
 *
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
/*
    function.php: Central include-file with a lot of function definitions
    Copyright (C) 2003 Franz Domes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/
        
if (defined('MEMBERTYPE_DEP')) {
	return 0;
} else {
	define('MEMBERTYPE_DEP', TRUE);
}
    
define('MEMBERTYPE_DEP_INDEPENDANT', -1);
define('MEMBERTYPE_DEP_FULLMEMBER', 0);
define('MEMBERTYPE_DEP_ASSOCMEMBER', 1);

function getMemberTypeDependency($memberType)
{
    global $db, $APerr;
    
    $sql = "SELECT TypeDependencies FROM `###_Membertype` WHERE id = $memberType";
    $dep = $db->GetOne($sql);
    if ( $dep === false && $db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
        return array();
    }
    return $dep;
}    

function getAssociateMembers($memberType, $MemberID)
{
  global $db, $APerr;
    
  if ( getMemberTypeDependency($memberType) == 0 )
  {
    $sql = "SELECT `###_Members`.MemberID, `###_Addresses`.Firstname, `###_Addresses`.Lastname
              FROM `###_Addresses`
         LEFT JOIN `###_Members` ON `###_Addresses`.Adr_MemberID = `###_Members`.MemberID
             WHERE `###_Addresses`.Addresstype_ref = 1
               AND `###_Members`.MainMemberID = $MemberID";
    $assocMemberArr = $db->GetAssoc($sql);
    if ( $assocMemberArr === false && $db->ErrorNo() != 0 )
    {
        $APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
        return array();
    }

    return $assocMemberArr;
  }
  else
  {
    return array();
  }
}

/*
 * Parameter $memberType_dep:
 *    MEMBERTYPE_DEP_INDEPENDANT, MEMBERTYPE_DEP_FULLMEMBER, MEMBERTYPE_DEP_ASSOCMEMBER
 */
function getListOfMemberTypeDependencies($memberType_dep)
{
  global $db;
  
  $sql  = "SELECT id from `###_Membertype` WHERE TypeDependencies ";
  
  switch ($memberType_dep)
  {
    case MEMBERTYPE_DEP_INDEPENDANT:
      $sql .= " = '-1' OR TypeDependencies IS NULL";
      break;
      
    case MEMBERTYPE_DEP_FULLMEMBER:
      $sql .= " = '0'";
      break;
      
    case MEMBERTYPE_DEP_ASSOCMEMBER:
      $sql .= " > '0'";
      break;
  }
  
  (($arr = $db->GetCol($sql)) !== false) or die (__LINE__ . ": " . $db->ErrorMsg());
  
  return (join(", ", $arr));
}
?>