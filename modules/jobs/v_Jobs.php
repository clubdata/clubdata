<?php
/**
 * Clubdata Jobs Modules
 *
 * @package Jobs
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 * Class to show Jobs menu
 *
 * @package Jobs
 */
class vJobs extends CdBase{

    function vJobs($db, &$smarty, &$formsgeneration)
    {
      $_SESSION['navigator_menu'] = 'ACCOUNTING';
      return Cdbase::Cdbase();
    }

    function getSmartyTemplate()
    {
        return("jobs/v_Jobs.inc.tpl");
    }

}

?>