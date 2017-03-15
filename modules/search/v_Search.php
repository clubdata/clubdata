<?php
/**
 * Clubdata Search Modules (View Email)
 *
 * Contains the class to search for members to which should be send an email.
 * By default the Attribute "Member wants infos per email" is predefined for this search
 *
 * @package Search
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */

/**
 * Class to show Queries menu
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @package Search
 */
class vSearch extends CdBase{

  function vSearch($db, &$smarty, &$formsgeneration)
  {
    $_SESSION['navigator_menu'] = 'COMMUNICATION';
    return Cdbase::Cdbase();
  }

  function getSmartyTemplate()
  {
      return("search/v_Search.inc.tpl");
  }

}

?>