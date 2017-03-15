<?php
/**
 * Clubdata Help Modules
 *
 * Contains the classes of the main menu
 *
 * @package Help
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 *
 */

/**
 *
 */

/**
 * class to show help text for data field
 *
 * @package Help
 */
class vHelp extends CdBase{

    var $head;
    var $cat;
    var $subcat;

    /**
     *
     * mode defines if help is shown in tooltip or not
     * @var string 'tooltip' or empty
     */
    var $mode;

    function vHelp()
    {
        Cdbase::Cdbase();

        $this->head = getGlobVar("head", "::textws::");
        $this->cat  = getGlobVar("cat", "::text::");
        $this->subcat = getGlobVar("subcat", "::text::");
        $this->mode = getGlobVar("mode", "tooltip");

        debug('M_HELP', "[Help, vHelp]: Category: $this->cat, Subcategory: ". $this->subcat);
    }

    function getSmartyTemplate()
    {
        return("help/v_Help.inc.tpl");
    }

    function setSmartyValues()
    {

    }

    /**
    *
    * displays the page.
    */
    function display()
    {
        global $langFile;
        global $APerr;

        $hlpTxt = '';
        $sql ="SELECT * FROM `###_Help` WHERE Category IN ('*','$this->cat') AND Subcategory = '$this->subcat' ORDER BY Category DESC";
        //echo $sql;

        debug('M_HELP', "[Help, display]: Category: $this->cat, Subcategory: ". $this->subcat . " SQL: $sql");

        $res = $this->db->Execute($sql);
        if ( $res === false )
        {
        	debug('M_HELP', "[Help, display]: ERROR (" . $this->db->ErrorMsg . " SQL: $sql");
        }
        else
        {
	        if ( $res->RecordCount() > 0)
	        {
	            $mgArr = $res->FetchRow();

	            debug_r('M_HELP', $mgArr, "[Help, display] mgArr");

	            $hlpTxt = getDescriptionTxt($mgArr);
	        }
        }

        if ( empty($hlpTxt) )
        {
            $hlpTxt = lang("No Help yet available");
            $hlpTxt .= "<P><table><tr><td>Category:</td><td>$this->cat</td></tr><tr><td>Subcategoy:</td><td>$this->subcat</td></tr></table>";
        }

        $this->smarty->assign('hlpTxt', $hlpTxt);



        $this->smarty->assign('HEAD_Encoding', CHARACTER_ENCODING);
        $this->smarty->assign('HEAD_Title',
                                lang("Club Member Administration") .
                                "(" . $this->getModuleName() . "/" .
                                $this->getCurrentView() . ")");

        $this->smarty->assign('head', lang($this->head));
        $this->smarty->register_object("modClass", $this);
        $this->smarty->assign_by_ref("APerr", $APerr);
        $this->smarty->assign("mode", $this->mode);


//         debug_r('Help', $this->formsgeneration, "[Help, display] formsgeneration");
//         debug('Help', "[Help, display]: buttonbar: ". $this->smarty->get_template_vars('buttonbar'));
//         debug('Help', "[Help, display]: forms: ". $this->smarty->get_template_vars('forms'));

        $this->smarty->assign('javascript', $this->formsgeneration->getJavascriptDefinition());
        $this->smarty->assign("formDefinition", $this->formsgeneration->getFormDefinition());
        debug('M_HELP', "[Help, display]: formDefinition: ". $this->formsgeneration->getFormDefinition());

        $this->smarty->display('help/v_Help.inc.tpl');
    }

}

?>