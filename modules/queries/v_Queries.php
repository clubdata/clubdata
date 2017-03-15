<?php
/**
 * Clubdata Query Modules
 *
 * @package Queries
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show Queries menu
 *
 * @package Queries
 */
class vQueries extends CdBase{

  function vQueries($db, &$smarty, &$formsgeneration)
  {
    $_SESSION['navigator_menu'] = 'QUERIES';
    return Cdbase::Cdbase();
  }

  function getSmartyTemplate()
  {
      return("queries/v_Queries.inc.tpl");
  }

}

?>