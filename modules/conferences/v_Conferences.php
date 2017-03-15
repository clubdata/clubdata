<?php
/**
 * Clubdata Conferences Modules
 *
 * Contains classes to administer conferences in Clubdata.
 *
 * @package Conferences
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show selection menu
 *
 * @package Conferences
 */
class vConferences extends CdBase{

    function vConferences($db, $memberID, $conferenceObj, $initView, $smarty, $formsgeneration)
    {
      $_SESSION['navigator_menu'] = 'CONFERENCES';
      return Cdbase::Cdbase();
    }

    function getSmartyTemplate()
    {
        return("conferences/v_Conferences.inc.tpl");
    }

}

?>