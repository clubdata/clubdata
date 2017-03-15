<?php
/**
 * Clubdata Administration Modules (View Backup)
 *
 * Contains the class to make database backups
 * This is used for administrative purposes, to backup the Clubdata database
 * This class is called by Class Admin.
 *
 * @package Admin
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/backup.class.php');
require_once('include/table.class.php');
require_once('include/listing.class.php');
//     include("javascript/calendar.js.php");

/**
 * Class to back up database tables
 *
 * @package Admin
 */
class vBackup {
    var $memberID;
    var $db;
    var $addresstype;

    var $tblObj;
    var $listObj;

    var $smarty;
    var $formsgeneration;

    function vBackup($db, $table, $key, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->table = $table;
        $this->key = $key;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->tblObj = new Table($this->formsgeneration);

    }

    function getSmartyTemplate()
    {
        return 'admin/v_Backup.inc.tpl';
    }

    function setSmartyValues()
    {
        global $APerr;

//        print("<PRE>");print_r($this->listObj);print("</PRE>");
        if ( is_object($this->listObj) )
        {
        	$this->listObj->prepareRecordList('');
       		$this->smarty->assign_by_ref('listObj', $this->listObj);
        }

        $errTxt = array();
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                            "TYPE"=>"select",
                            "LABEL"=>lang('Type of Backup'),
                            "MULTIPLE"=>0,
                            "NAME"=>'BACKTYPE',
                            "ID"=>'BACKTYPE',
                            "SIZE"=>1,
                            "VALUE"=>'SEND',
                            "OPTIONS"=>array('SEND' => lang('Send Backup'),
                                             'FILE' => lang('Write Server File'),
                                             'DISPLAY' => lang('Display in Browser')
                                             ),
                            ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                            "TYPE"=>"text",
                            "NAME"=>'NAMEPART',
                            "ID"=>'NAMEPART',
                            "VALUE"=>'Backup',
                            "LABEL"=>lang('Namepart of Backup'),
        ));
        $errTxt[] .= $this->formsgeneration->AddInput(array(
                            "TYPE"=>"select",
                            "LABEL"=>lang('Compression'),
                            "MULTIPLE"=>0,
                            "NAME"=>'COMPRESSION',
                            "ID"=>'COMPRESSION',
                            "SIZE"=>1,
                            "VALUE"=>'GZIP',
                            "OPTIONS"=>array('GZIP' => lang('GZIP'),
                                             'BZIP' => lang('BZIP'),
                                             'NOZIP' => lang('NOZIP')
                                             ),
                            ));

        if ( count($errTxt = array_filter($errTxt)) )
        {
            $str = join("<BR>",$errTxt);
            $APerr->setFatal(__FILE__,__LINE__,$str);
        }

        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
    }

    function getNavigationElements(&$buttons)
    {

            $buttons->AddInput(array(
                    "TYPE"=>"submit",
                    "ID"=>"Submit_Insert",
                    "NAME"=>"Submit",
                    "VALUE"=>lang("Start Backup"),
                    "CLASS"=>"BUTTON",
                    "ONCLICK"=>"doAction('admin','Backup','BACKUP');",
                    "SubForm"=>"buttonbar"
            ));
/*            $cols = array();
            array_push($cols,array ( 'type' => 'submit',
                                    'name' => 'Action',
                                    'value' => 'BACKUP',
                                    'javascript' => "onClick='doSubmit(\"admin\",\"Backup\");'",
                                    'label' => lang("Start Backup"),
                    ));
            return $cols;
*/
    }

    function doAction($action)
    {
        switch ( $action )
        {
            case 'BACKUP':
                $backtype = getGlobVar('BACKTYPE','SEND|FILE|DISPLAY');
                $compression = getGlobVar('COMPRESSION','GZIP|BZIP|NOZIP');
                $filename = getGlobVar('NAMEPART');
                
                $backObj = new Backup($this->db, BACKUPDIR);
                switch ( $backtype )
                {
                    case 'SEND':
                        $backObj->doBackupSend($filename, $compression);
                        break;

                    case 'FILE':
                        $backObj->doBackupFile($filename, $compression);
                        break;

                    case 'DISPLAY':
                        $sqlback = $backObj->doBackupShow($filename);
                        $this->listObj = new Listing('backup_list',
												array('columnNames' => array('Backup')));
                        foreach (explode("\n", $sqlback) as $backLine )
                        {
                            $this->listObj->addRow($backLine);
                        }                        
                        break;
                }
        }
        return true;
    }
}
?>