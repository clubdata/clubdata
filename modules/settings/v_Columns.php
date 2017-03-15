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
require_once('include/addresses.class.php');
require_once('include/dbtable.class.php');

/**
 * Class to set columns displayed in member lists
 *
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.2 $
 * @package Settings
 */
class vColumns extends Table {
    var $db;

    /**
     * @var object $module  Reference to calling module
     */
    var $module;

    var $table;
    var $columnNames;
    var $selectedFields;

    var $adrObj;

    var $idField  = '`###_Members`.`MemberID`';
    var $smarty;

    function vColumns($db, &$module)
    {
        $this->module = &$module;

        parent::Table($module->formsgeneration);

        $this->db = $db;
        $this->smarty = &$module->smarty;

        $this->adrObj = new Addresses($this->db, $this->formsgeneration);

        $this->query = '`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID';

//             echo "DISPLAYCOLS: <PRE>"; print_r(getGlobVar('DisplayCols','','PGS')); echo "</PRE><BR>";
        $this->setDefaultCols(getGlobVar('DisplayCols','','PGS'));

    }

    function setDefaultCols($defCols = '')
    {

        if (empty($defCols) )
        {
//                   phpinfo(INFO_VARIABLES);

            if ( empty($this->displayCols) )
            {
                // Force ID field to be set
                $this->displayCols[$this->idField] = 'on';

                $arr = preg_split('/\s*,\s*/', getConfigEntry($this->db, 'DefaultCols'));
                foreach($arr as $key )
                {
                    $this->displayCols[$key] = 'on';
                }
            }

        }
        else
        {
            // Force ID field to be set
            $this->displayCols[$this->idField] = 'on';

            if ( is_array($defCols) )
            {
                $this->displayCols = array_merge($this->displayCols, $defCols);
            }
            else
            {
                $this->displayCols = array();
                $arr = preg_split('/\s*,\s*/', $defCols);
                foreach($arr as $key )
                {
                    $this->displayCols[$key] = 'on';
                }
            }
        }

        unset($_SESSION['DisplayCols']);
        $_SESSION['DisplayCols'] = $this->displayCols;
    }

    function selectFields($checkedFields = '')
    {
      global $APerr;

        $rc = 0;
        $colArr = array();
        foreach (array('`###_Members`', '`###_Members_Attributes`') as $table)
        {
            $dbTableObj = new DBTable($this->db, $this->formsgeneration,  $table , '1');
            $colArr = array_merge($colArr,
                                    $dbTableObj->getFieldListArray($table,
                                        ($table == "`###_Members_Attributes`" ? array("MemberID") : array()),
                                        array(),true));

//              echo "VAL: $val, TABLE: $table, NAME:<PRE>";print_r($colArr);print"</PRE>";
        }

//         echo "ID: $this->idField<BR>";
        foreach ( $colArr as $key => $val )
        {
            $name = resolveField($val);
//             echo "VAL: >".$val."<, NAME:<PRE>";print_r($name);print"</PRE>";


            $checked = $checkedFields == '*' || is_array($checkedFields) && !empty($checkedFields[$name['raw']]) ? 'CHECKED ' : '';
            $disabled = ( $name['raw'] == $this->idField ) ? 0 : 1;

// echo "IDFIELD: >"  . $this->idField . "<, RAW: >$name[raw]< = ". (( !strcmp($name['raw'],$this->idField) ) ? 0 : 1) ."<BR>";

            $this->formsgeneration->AddInput(array(
                            "TYPE"=>"checkbox",
                            "CLASS"=>"CHECKBOX",
                            "ONCLICK"=>"submit();",
                            "Accessible"=>$disabled,
                            "CHECKED"=>$checked,
                            "LABEL"=>helpAndText('Members', $name['column'], $name['pretty']),
                            "NAME"=>"DisplayCols[$val]",
                            "ID"=>"DisplayCols[$val]",
                            "VALUE"=>"on",
                            "SubForm"=>"Columns",
                            "ReadOnlyMark"=>'[X]',
                            "ReadOnlyMarkUnchecked"=>'[ ]',
                            ));
        }

        $this->headArr["Columns"] = lang('Memberinfo');

        unset($rs);

        // Generate Subtables for each Addresstype
        $sql = "select * from `###_Addresses` LEFT JOIN `###_Addresses_Mailingtypes` ON `###_Addresses`.`id` = `###_Addresses_Mailingtypes`.`AddressID`";
        $rs = $this->db->SelectLimit($sql, 1);
        if ( $rs === false )
        {
            $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
        }
        else
        {
          $adrTypes = $this->adrObj->getAddressTypes();
          foreach ( $adrTypes as $adrType )
          {
              foreach ( explode(',', $adrType['FieldList']) as $field)
              {
                  $field = trim($field);
                  $name = resolveFieldName($rs, $field);
                  $checked = $checkedFields == '*' || is_array($checkedFields) && !empty($checkedFields["Addresses_{$adrType['id']}.{$name['raw']}"]) ? 'CHECKED ' : '';
                  $disabled = ( $name['raw'] == $this->idField ) ? 0 : 1;

                  $this->formsgeneration->AddInput(array(
                                  "TYPE"=>"checkbox",
                                  "ONCLICK"=>"submit();",
                                  "CLASS"=>"CHECKBOX",
                                  "Accessible"=>$disabled,
                                  "CHECKED"=>$checked,
                                  "LABEL"=>helpAndText('Addresses', $name['raw'], $name['pretty']),
                                  "NAME"=>"DisplayCols[Addresses_{$adrType['id']}.{$name['raw']}]",
                                  "ID"=>"DisplayCols[Addresses_{$adrType['id']}.{$name['raw']}]",
                                  "VALUE"=>"on",
                                  "SubForm"=>"Addresses_{$adrType['id']}",
                                  "ReadOnlyMark"=>'[X]',
                                  "ReadOnlyMarkUnchecked"=>'[ ]',
                                  ));
              }

              $field = 'Mailingtypes_ref';

              $name = resolveFieldName($rs, $field);
              $checked = $checkedFields == '*' || is_array($checkedFields) && !empty($checkedFields["Addresses_{$adrType['id']}.{$name['raw']}"]) ? 'CHECKED ' : '';
              $disabled = ( $name['raw'] == $this->idField ) ? 0 : 1;

              $this->formsgeneration->AddInput(array(
                              "TYPE"=>"checkbox",
                              "CLASS"=>"CHECKBOX",
                              "Accessible"=>$disabled,
                              "CHECKED"=>$checked,
                              "LABEL"=>helpAndText('Addresses_Mailingtypes', $name['raw'], $name['pretty']),
                              "NAME"=>"DisplayCols[Addresses_Mailingtypes_{$adrType['id']}.{$name['raw']}",
                              "ID"=>"DisplayCols[Addresses_Mailingtypes_{$adrType['id']}.{$name['raw']}",
                              "VALUE"=>"on",
                              "SubForm"=>"Addresses_{$adrType['id']}",
                              "ReadOnlyMark"=>'[X]',
                              "ReadOnlyMarkUnchecked"=>'[ ]',
                              ));

              $this->headArr["Addresses_{$adrType['id']}"] = getDescriptionTxt($adrType);
          }
        }
        debug_r("TABLE", $this->formsgeneration, "showRecordDetails");
    }

    function getSmartyTemplate()
    {
        return 'settings/v_Columns.inc.tpl';
    }

    function setSmartyValues()
    {
        $this->selectFields($this->displayCols);

        $this->smarty->assign_by_ref("heads", $this->headArr);
        $this->smarty->assign("mainform", $this->formsgeneration->processFormsGeneration($this->smarty,'columns.inc.tpl'));
        $this->smarty->assign_by_ref("formDefinition", $this->formsgeneration->getFormDefinition());
    }

}