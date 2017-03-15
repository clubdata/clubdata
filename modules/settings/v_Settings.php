<?php
/**
 * Clubdata Settings Modules
 *
 * Contains classes to set parameters in Clubdata.
 *
 * @package Settings
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
/**
 * Class to show Settings menu
 *
 * @package Settings
 */
class vSettings extends CdBase{

  function vSettings($db, &$smarty)
  {
    $_SESSION['navigator_menu'] = 'SETTINGS';
    return Cdbase::Cdbase();
  }

  function getSmartyTemplate()
  {
      return("settings/v_Settings.inc.tpl");
  }

}

?>