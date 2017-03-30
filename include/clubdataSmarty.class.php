<?php
/**
 * @package Clubdata
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
if (defined('CLUBDATASMARTY')) {
    return 0;
} else {
    define('CLUBDATASMARTY', true);
}

/**
 * Class to extend Smarty for Clubdata
 *
 * @package Clubdata
 */
class ClubdataSmarty extends Smarty {

    public $db;
    public $style;

    public function __construct($db, $langFile) {
        $this->db = $db;

        $this->style = getConfigEntry($this->db, "Style");

        $this->template_dir = SCRIPTROOT . "/style/$this->style/templates/";
        $this->compile_dir = SCRIPTROOT . "/style/$this->style/templates_c";
        $this->config_dir = SCRIPTROOT . "/style/$this->style/configs/";
        $this->cache_dir = SCRIPTROOT . "/style/$this->style/cache/";
        $this->compile_id = $langFile;

        if (SERVER_SYSTEM_TYPE == 'WINDOWS') {
            foreach (array ('template_dir', 'compile_dir', 'config_dir', 'cache_dir') as $dir) {
//            $this->$dir = str_replace('/', '\\', $this->$dir);
//            print ("DIR: $dir=" . $this->$dir . "<BR>");
            }
        }

        $this->compile_check = true;
        $this->debugging = SMARTY_DEBUGGING;

        if (!is__writable($this->compile_dir) || ! is__writable($this->cache_dir)) {
            printf(
                lang("The directories %s and %s must be writeable by the webserver ! Please change the access rights !"),
                $this->compile_dir,
                $this->cache_dir
            );
            exit;
        }
//        $this->config_load($langFile . ".smarty");
        $this->assign('STYLE_DIR', "style/$this->style/");
        $this->assign('INDEX_PHP', INDEX_PHP);

        $this->assign('YesNoSelection', array(
                                'YES' => lang('Yes'),
                                'NO' => lang('No')
                                ));

        require_once('include/smarty.plugins.php');
        $this->register_modifier("translate", "smarty_modifier_translate");
        $this->register_function("getDescription", "smarty_function_getDescription");
        $this->register_function("displayTable", "smarty_function_displayTable");
        $this->register_compiler_function("lang", "smarty_compiler_lang");
        $this->register_postfilter('smarty_postfilter_lang');

        $this->register_function("image_path", "imagePath");
    }

    public function smartyTemplateExists($template) {
/*        print("BUTTONTEMPLATE: " . SCRIPTROOT . "/style/$this->style/templates/" . $template . "\n" .
              " EXISTS: " . file_exists(SCRIPTROOT . "/style/$this->style/templates/" . $template));*/
        return (file_exists(SCRIPTROOT . "/style/$this->style/templates/" . $template));
    }
}
