<?php
/**
 * Clubdata Administration Modules (View Add)
 *
 * Contains classes to add data to the database.
 * This is used for administrative purposes, to add new entries to database tables.
 * This class is called by Class Admin
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
require_once("include/dbtable.class.php");

/**
 * Class to add new entries to a administrative database table
 *
 * @package Admin
 */
class vAdd {
    var $db;
    var $table;
    var $tableObj;
    var $key;
    var $smarty;
    var $formsgeneration;

    var $presetVals;
    var $presetEditVals;

    function vAdd($db, $table, $key, $smarty, $formsgeneration)
    {
        $this->db = $db;
        $this->table = $table;
        $this->key = $key;
        $this->smarty = $smarty;
        $this->formsgeneration = $formsgeneration;

        $this->tableObj = new DbTable($this->db, $this->formsgeneration, "`###_" . $this->table . "`", "1=1");

        $this->presetVals = array();
        $this->presetEditVals = array('Language_ref' => DEFAULT_LANGUAGE);

        $this->formsgeneration->AddInput(array(
                            "TYPE"=>"hidden",
                            "NAME"=>'Table',
                            "ID"=>'Table',
                            "VALUE"=>$this->table,
                            ));

        $this->tableObj->newRecord($this->presetVals, $this->presetEditVals);
    }

    function addLanguageColumns()
    {
      global $APerr;

      $tableArr = array('Addresstype', 'Attributes', 'Conferences', 'Configuration', 'Country', 'Help', 'InfoGiveOut',
                        'InfoWWW', 'Language', 'Mailingtypes', 'Membertype','Paymode', 'Paytype', 'Salutation');

      foreach ( $tableArr as $table )
      {
        $colNamesArr = $this->db->MetaColumns("###_" . $table . "");

        debug_r("DBTABLE", $colNamesArr, "[Admin, v_Add, addLanguageColumns] Table: $table, ColNamesArr:");

        foreach ( $colNamesArr as $colNameUpper => $column )
        {
          if ( !substr_compare(strtoupper($column->{'name'}), "_UK", -3) )
          {
            $colNameBase = substr($column->{'name'},0,-3);
            $colNameNew = $colNameBase . "_" . $this->key;
            $maxLength = ($column->{'length'} < 50 ? 100 : $column->{'length'});
            if ( $column->{'type'} == 'text' )
            {
              $sql = "ALTER TABLE `###_{$table}` ADD `{$colNameNew}` TEXT NOT NULL";
            }
            else
            {
              $sql = "ALTER TABLE `###_{$table}` ADD `{$colNameNew}` VARCHAR( {$maxLength} ) NOT NULL";
            }

            debug("DBTABLE", "[Admin, v_Add, addLanguageColumns] Table: $table, ColNameUpper: $colNameUpper, SQL: $sql");
            if ( $this->db->Execute($sql) === false )
            {
                $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql",sprintf(lang('Cannot alter table %1$s ! Please add column %2$s manually'), $table, $colNameNew));
            }
            else
            {
                logEntry('ALTER', $sql);
            }
          }
        }
      }
    }

    function displayView()
    {

    }

    function getSmartyTemplate()
    {
        return 'admin/v_Edit.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'table.inc.tpl'));
        debug_r("DBTABLE", $this->formsgeneration, "[Admin, v_Add, getSmartyTemplate])");
    }
    function doAction()
    {
        $this->tableObj->setWhere("1=0");
        $insertID = $this->tableObj->insertRecord();
        if ( $this->table == 'Language' )
        {
          $this->addLanguageColumns();
        }
        return $insertID;
    }
}
?>
