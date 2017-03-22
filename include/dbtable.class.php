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
require_once('include/table.class.php');
require_once('include/function.php');

/**
 * @package Clubdata
 */
class DbTable extends Table {

    protected $db;     // DB connection
    protected $table;  // SQL command
    protected $where;  // ID
    protected $fields; // fields of sql query

    protected $checkArr = array(
        'Paydate' => '/\d+\-\d+\-\d+/',
        'Period' => '/\d{1,4}/',
        'Amount' => '/\d+[.,]\d+/',
    );

    public function __construct($db, &$formsgeneration, $table, $where, $fields = '*') {
        parent::__construct($formsgeneration);

        $this->db = $db;
        $this->table = $table;
        $this->where = $where;
        $this->fields = $fields;
    }

    function setWhere($where) {
        $this->where = $where;
    }

    function getCol($name = 'id') {
        $sql = "SELECT {$name} FROM {$this->table} WHERE {$this->where}";
        $col = $this->db->getCol($sql);

        return $col;
    }

    function recordExists() {
        global $APerr;

        $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$this->where}";
        $rs = $this->db->Execute($sql);

        if (empty($rs)) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return false;
        }

        $result = ($rs->recordCount() > 0) ? true : false;
        return $result;
    }

    function getRecord() {
        global $APerr;

        $result = array();

        $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$this->where}";
        debug('DBTABLE', "[DBTABLE: getRecord]: SQL {$sql}");
        $rs = $this->db->Execute($sql);

        if (empty($rs)) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return false;
        }

        // If more than one row, get 2-dimensional associative Array,
        // else return single row as simple associative array
        if ($rs->RecordCount() > 1) {
            $result = $rs->getAssoc();
        } else {
            $result = $rs->fetchRow();
        }

        debug_r('DBTABLE', $result, '[DBTABLE: getRecord]: Result');
        return $result;
    }

    function __excludeArr($var) {
        return !in_array($var, $this->tmpExclude);
    }

    function getFieldListArray($tablename = '', $exclude = array(), $include = array(), $noAlias = false) {
        $colArr = $this->db->MetaColumnNames($this->table, true);

        if (!empty($colArr)) {
            $resultArr = array();
            $shouldFilter = (!empty($exclude) || !empty($include));
            $shouldFormat = !empty($tablename);

            foreach ($colArr as $column) {
                if ($shouldFilter && (!in_array($column, $include) && in_array($column, $exclude))) {
                    continue;
                }

                $resultArr[] = ($shouldFormat) ? formatColumn($tablename, $column, $noAlias) : $column;
            }

            return $resultArr;
        }

        return array();
    }

    function getFieldList($tablename = '', $exclude = array(), $include = array()) {
        $fieldList = join(',', $this->getFieldListArray($tablename, $exclude, $include));
        return $fieldList;
    }

    function getFieldListExcept($tablename = '', $exclude = array()) {
        return $this->getFieldList($tablename, $exclude);
    }

    function getFieldListInclude($tablename = '', $include = array()) {
        return $this->getFieldList($tablename, array(), $include);
    }

    function showRecordDetails($edit = false, $title = '') {
        global $APerr;

        $this->formsgeneration->ReadOnly = ($edit === false ? 1 : 0);

        $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$this->where}";
        $rs = $this->db->Execute($sql);
        debug('DBTABLE', "[DBTABLE: showRecordDetails] SQL: {$sql}");

        if (empty($rs)) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            debug_backtr('DBTABLE');
            return;
        }

        $rowArrAssoc = $rs->FetchRow();
        $errTxt[] = array();

        for ($i = 0; $i < $rs->FieldCount(); $i++) {
            $name = resolveFieldIndex($rs, $i);
            $value = $rowArrAssoc[$name['raw']];

            if (strtolower($name['raw']) == 'id' || strtolower($name['raw']) == 'memberid') {
                $errTxt[] = $this->formsgeneration->AddInput(array(
                    'TYPE'       => 'text',
                    'NAME'       => $name['raw'],
                    'ID'         => $name['raw'],
                    'VALUE'      => $value,
                    'LABEL'      => helpAndText($this->table, $name['raw'], $name['pretty']),
                    'Accessible' => 0
                ));
                debug_r('DBTABLE', $this->formsgeneration->inputs, '[DBTABLE, showRecordDetails], nach id-Insert');
            } else {
                $ft = $rs->FetchField($i);
                //FD20150607 changed max_length to length
                $size = $ft->length;

                if ($size == -1) {
                    $size = 30;
                } elseif ($name['type'] == 'date') {
                    $size = 10;
                }

                if (isset($this->subTableArr[$name['raw']])) {
                    $this->subTableArr[$name['raw']]->getRecordAsSubtable($this->formsgeneration);
                } else {
                    debug('DBTABLE', "[DBTABLE: showRecordDetails]
                        NAME: {$name['raw']}
                        VALUE: {$value},
                        TYPE: {$name['type']}");

                    switch ($name['type']) {
                        case 'myref':
                            $options = getOptionArray($name['reftable'], null, true);

                            if (!array_key_exists($value, $options)) {
                                $value = key($options);
                            }

                            debug('DBTABLE', "[DBTABLE: showRecordDetails] MYREF VALUE: {$value}");

                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'     => 'select',
                                'LABEL'    => helpAndText($this->table, $name['raw'], $name['pretty']),
                                'MULTIPLE' => 0,
                                'NAME'     => $name['raw'],
                                'ID'       => $name['raw'],
                                'SIZE'     => 1,
                                'VALUE'    => (string) $value,
                                'OPTIONS'  => $options,
                            ));
                            break;
                        case 'date':
                            if ($value == '0000-00-00') {
                                $value = '';
                            }

                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'        => 'custom',
                                'LABEL'       => helpAndText($this->table, $name['raw'], $name['pretty']),
                                'ID'          => $name['raw'],
                                'NAME'        => $name['raw'],
                                'CustomClass' => 'form_date_class',
                                'VALUE'       => $value,
                                'Format'      => '{day}.{month}.{year}',
                                'YearClass'   => 'DATUM',
                                'Months'      => array(
                                    '01' => lang('January'),
                                    '02' => lang('February'),
                                    '03' => lang('March'),
                                    '04' => lang('April'),
                                    '05' => lang('May'),
                                    '06' => lang('June'),
                                    '07' => lang('July'),
                                    '08' => lang('August'),
                                    '09' => lang('September'),
                                    '10' => lang('October'),
                                    '11' => lang('November'),
                                    '12' => lang('December')
                                ),
                                'Optional'    => 1,
                                'ONKEYDOWN'   => 'changeColorOnKey(this)',
                                'ONBLUR'      => "changeColorIfChanged(this, '{$value}')"
                            ));
                            break;
                        case 'yesno':
                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'      => 'select',
                                'LABEL'     => helpAndText($this->table, $name['raw'], $name['pretty']),
                                'MULTIPLE'  => 0,
                                'NAME'      => $name['raw'],
                                'ID'        => $name['raw'],
                                'SIZE'      => 1,
                                'VALUE'     => $value,
                                'OPTIONS'   => array(
                                    '1' => lang('Yes'),
                                    '0' => lang('No')
                                ),
                                'ONKEYDOWN' => 'changeColorOnKey(this)',
                                'ONBLUR'    => "changeColorIfChanged(this, '{$value}')"
                            ));
                            break;
                        case 'multiline':
                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'      => 'textarea',
                                'CLASS'     => 'TEXTAREA',
                                'LABEL'     => helpAndText($this->table, $name['raw'], $name['pretty']),
                                'NAME'      => $name['raw'],
                                'ID'        => $name['raw'],
                                'ROWS'      => 2,
                                'COLS'      => 50,
                                'VALUE'     => $value,
                                'ONKEYDOWN' => 'changeColorOnKey(this)',
                                'ONBLUR'    => "changeColorIfChanged(this, '{$value}')"
                            ));
                            break;
                        case 'mylink':
                            $value = strtr($value, array('"' => '&quot;'));
                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'   => 'file',
                                'NAME'   => $name['raw'],
                                'ID'     => $name['raw'],
                                'ACCEPT' => 'image/gif',
                                'LABEL'  => helpAndText($this->table, $name['raw'], $name['pretty'])
                            ));
                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'            => 'checkbox',
                                'CLASS'           => 'CHECKBOX',
                                'CHECKED'         => 0,
                                'NAME'            => $name['raw'] .'_DELETE',
                                'ID'              => $name['raw'] .'_DELETE',
                                'LABEL'           => helpAndText($this->table, $name['raw'], $name['pretty'].' Delete'),
                                'VALUE'           => 'on',
                                'ApplicationData' => $value
                            ));
                            break;
                        case 'password':
                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'            => 'password',
                                'NAME'            => $name['raw'],
                                'ID'              => $name['raw'],
                                'VALUE'           => '*****',
                                'LABEL'           => helpAndText($this->table, $name['raw'], $name['pretty']),
                                'ReadOnlyMark'    => '********',
                                'ExtraAttributes' => array('autocomplete' => 'off')
                            ));
                            $errTxt[] = $this->formsgeneration->AddInput(array(
                                'TYPE'                   => 'password',
                                'LABEL'                  => helpAndText($this->table, $name['raw'], $name['pretty']),
                                'NAME'                   => "{$name['raw']}_1",
                                'ID'                     => "{$name['raw']}_1",
                                'VALUE'                  => '*****',
                                'ValidateAsEqualTo'      => $name['raw'],
                                'ValidationErrorMessage' => lang('The password is not equal to the confirmation'),
                                'ReadOnlyMark'           => '********'
                            ));
                            break;
                        default:
                            $value = strtr($value, array('"' => '&quot;'));

                            if ($size > 1024) {
                                $errTxt[] = $this->formsgeneration->AddInput(array(
                                    'TYPE'      => 'textarea',
                                    'CLASS'     => 'TEXTAREA',
                                    'LABEL'     => helpAndText($this->table, $name['raw'], $name['pretty']),
                                    'NAME'      => $name['raw'],
                                    'ID'        => $name['raw'],
                                    'ROWS'      => 2,
                                    'COLS'      => 50,
                                    'VALUE'     => $value,
                                    'ONKEYDOWN' => 'changeColorOnKey(this)',
                                    'ONBLUR'    => "changeColorIfChanged(this, '{$value}')"
                                ));
                            } else {
                                $errTxt[] = $this->formsgeneration->AddInput(array(
                                    'TYPE'      => 'text',
                                    'NAME'      => $name['raw'],
                                    'ID'        => $name['raw'],
                                    'MAXLENGTH' => $size,
                                    'VALUE'     => $value,
                                    'LABEL'     => helpAndText($this->table, $name['raw'], $name['pretty']),
                                    'ONKEYDOWN' => 'changeColorOnKey(this)',
                                    'ONBLUR'    => "changeColorIfChanged(this, '{$value}')"
                                ));
                            }
                            break;
                    }
                }
            }
        }

        $pageNr = getGlobVar('PageNr', '::number::', 'PG');

        if (!empty($pageNr)) {
            $this->formsgeneration->AddInput(array(
                'TYPE'  => 'hidden',
                'NAME'  => 'PageNr',
                'ID'    => 'PageNr',
                'VALUE' => $pageNr,
            ));
        }

        // Load Input values only when Submitted via UPDATE-Button
        // debug_r('DBTABLE', $GLOBALS, 'showRecordDetails (GLOBALS))');
        debug('DBTABLE', 'WasSubmitted: ' . $this->formsgeneration->WasSubmitted('doit'));
        $errTxt[] = $this->formsgeneration->LoadInputValues($this->formsgeneration->WasSubmitted('doit'));

//         if ($this->formsgeneration->WasSubmitted('doit'))
//             $this->formsgeneration->LoadInputValues(true);

        debug_r('DBTABLE', $errTxt, '[DBTABLE, showRecordDetails] errTxt');

        if (count($errTxt = array_filter($errTxt))) {
            debug_backtr('DBTABLE');
            $str = join('<BR>', $errTxt);
            $APerr->setFatal(__FILE__, __LINE__, $str);
        }

        debug_r('DBTABLE', $this->formsgeneration, "showRecordDetails ({$value}))");
        return $this->formsgeneration;
    }

    function checkValue($fieldName, $fieldValue, $checkArr) {
        global $APerr;

        if (isset($checkArr[$fieldName['raw']])) {
            if (!preg_match($checkArr[$fieldName['raw']], $fieldValue)) {
                $APerr->setError(lang('Invalid format for field ') .
                    $fieldName['pretty'] . ": '{$fieldValue}' <=> " . $checkArr[$fieldName['raw']]);
                return false;
            }
        }

        return true;
    }

    function newRecord($presetVals = array(), $presetEditVals = array(), $title = '') {
        $this->title = $title;

        $sql = "SELECT {$this->fields} FROM {$this->table}";
        $rs = $this->db->SelectLimit($sql, 1)
              or die(__LINE__ .': '. $this->db->ErrorMsg() .'<BR>'. $sql);

        for ($i = 0; $i < $rs->FieldCount(); $i++) {
            $name = resolveFieldIndex($rs, $i);

            if ($name['raw'] == 'id' && $name['type'] == 'int') {
                continue;
            }

            $ft = $rs->FetchField($i);
            $size = $ft->length;

            if ($size == -1) {
                $size = 30;
            } elseif ($name['type'] == 'date') {
                $size = 10;
            }

            $editVal = '';

            if (empty($presetVals[$name['raw']])) {
                if (!empty($presetEditVals[$name['raw']])) {
                    $editVal = $presetEditVals[$name['raw']];
                }
            } else {
                $editVal = $presetVals[$name['raw']];
            }

            switch ($name['type']) {
                case 'myref':
                    $options = getOptionArray($name['reftable'], null, false);
                    $this->formsgeneration->AddInput(array(
                        'TYPE'       => 'select',
                        'LABEL'      => helpAndText($this->table, $name['raw'], $name['pretty']),
                        'MULTIPLE'   => 0,
                        'NAME'       => $name['raw'],
                        'ID'         => $name['raw'],
                        'SIZE'       => 1,
                        'VALUE'      => (string) (empty($editVal) ? key($options) : $editVal),
//                    					'VALUE'=>key($options),
                        'OPTIONS'    => $options,
                        'Accessible' => !isset($presetVals[$name['raw']])
                    ));
                    break;
                case 'yesno':
                    $this->formsgeneration->AddInput(array(
                        'TYPE'       => 'select',
                        'LABEL'      => helpAndText($this->table, $name['raw'], $name['pretty']),
                        'MULTIPLE'   => 0,
                        'NAME'       => $name['raw'],
                        'ID'         => $name['raw'],
                        'VALUE'      => 0,
                        'SIZE'       => 1,
                        'OPTIONS'    => array(
                            '1' => lang('Yes'),
                            '0' => lang('No')
                        ),
                        'Accessible' => !isset($presetVals[$name['raw']])
                    ));
                    break;
                case 'mylink':
                    $value = strtr($value, array('"' => '&quot;'));
                    $this->formsgeneration->AddInput(array(
                        'TYPE'       => 'file',
                        'NAME'       => $name['raw'],
                        'ID'         => $name['raw'],
                        'ACCEPT'     => 'image/jpeg,image/pjpeg,image/png,image/gif',
                        'LABEL'      => helpAndText($this->table, $name['raw'], $name['pretty']),
                        'Accessible' => !isset($presetVals[$name['raw']])
                    ));
                    $this->formsgeneration->AddInput(array(
                        'TYPE'       => 'checkbox',
                        'CLASS'      => 'CHECKBOX',
                        'CHECKED'    => 0,
                        'NAME'       => $name['raw'] .'_DELETE',
                        'ID'         => $name['raw'] .'_DELETE',
                        'LABEL'      => helpAndText($this->table, $name['raw'], $name['pretty'] .' Delete'),
/*                            'VALUE'=>'',*/
                        'Accessible' => !isset($presetVals[$name['raw']])
                    ));
                    break;
                case 'password':
                    $this->formsgeneration->AddInput(array(
                        'TYPE'         => 'password',
                        'NAME'         => $name['raw'],
                        'ID'           => $name['raw'],
                        'VALUE'        => $editVal,
                        'LABEL'        => helpAndText($this->table, $name['raw'], $name['pretty']),
                        'ReadOnlyMark' => '********',
                        'Accessible'   => !isset($presetVals[$name['raw']])
                    ));
                    $this->formsgeneration->AddInput(array(
                        'TYPE'                   => 'password',
                        'LABEL'                  => helpAndText($this->table, $name['raw'], "{$name[pretty]} Wiederh."),
                        'VALUE'                  => $editVal,
                        'NAME'                   => "{$name['raw']}_1",
                        'ID'                     => "{$name['raw']}_1",
                        'ValidateAsEqualTo'      => $name['raw'],
                        'ValidationErrorMessage' => lang('The password is not equal to the confirmation'),
                        'ReadOnlyMark'           => '********',
                        'Accessible'             => !isset($presetVals[$name['raw']])
                    ));
                    break;
                case 'date':
                    $this->formsgeneration->AddInput(array(
                        'TYPE'        => 'custom',
                        'LABEL'       => helpAndText($this->table, $name['raw'], $name['pretty']),
                        'ID'          => $name['raw'],
                        'VALUE'       => $editVal,
                        'NAME'        => $name['raw'],
                        'CustomClass' => 'form_date_class',
                        'Format'      => '{day}.{month}.{year}',
                        'YearClass'   => 'DATUM',
                        'Optional'    => 1,
                        'Accessible'  => !isset($presetVals[$name['raw']])
                    ));
                    break;
                default:
                    $this->formsgeneration->AddInput(array(
                        'TYPE'       => 'text',
                        'NAME'       => $name['raw'],
                        'VALUE'      => $editVal,
                        'ID'         => $name['raw'],
                        'MAXLENGTH'  => $size,
                        'LABEL'      => helpAndText($this->table, $name['raw'], $name['pretty']),
                        'Accessible' => !isset($presetVals[$name['raw']])
                    ));
                    break;
            }
        }

        // Load Input values only when Submitted via UPDATE-Button
        $this->formsgeneration->LoadInputValues($this->formsgeneration->WasSubmitted('doit'));
    }

    function updateRecord($uploadID = '') {
        global $APerr;

        $error = false;

        // Therefore we need to validate the submitted form values.
        if (($error_message = $this->formsgeneration->Validate($verify)) == '') {
            // It's valid, set the $doit flag variable to 1 to tell the form is ready to processed.
            $doit = 1;
        } else {
            /*
             * It's invalid, set the $doit flag to 0 and encode the returned error message
             * to escape any HTML special characters.
             */
            $doit = 0;
            $error_message = nl2br(HtmlSpecialChars($error_message));
            debug('DBTABLE', "[DBTABLE, updateRecord] Formserror: {$error_message}");
            $APerr->setFatal(__FILE__, __LINE__, $error_message);
            return false;
        }

        $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$this->where}";
        debug('DBTABLE', "[DBTABLE, updateRecord]: SQL = {$sql}");
        $rs = $this->db->Execute($sql);

        if ($rs === false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return false;
        }

        if ($rs->RecordCount() == 1) {
            $fields = array();

            for ($i = 0; $i < $rs->FieldCount(); $i++) {
                $field_name = resolveFieldIndex($rs, $i);

                if (strtolower($field_name['raw']) == 'id' || strtolower($field_name['raw']) == 'memberid') {
                    continue;
                }

                if (isset($this->subTableArr[$field_name['raw']])) {
                    $this->updateSubTable($field_name['raw']);
                    continue;
                }

                $value = $this->formsgeneration->GetInputValue($field_name['raw']);
                debug('DBTABLE', "[DBTABLE, updateRecord]: FORMSGENERATION-WERT: {$field_name['raw']} = {$value}");

                if (!$this->checkValue($field_name, $value, $this->checkArr)) {
                    $error = true;
                } else {
                    debug_r('DBTABLE', $field_name, "[DBTABLE, updateRecord]: {$field_name['raw']} = {$value}");

                    switch ($field_name['type']) {
                        case 'multiline':
                        case 'varchar':
                        case 'string':
//                         case 'blob':
                        case 'time':
                            $fields[] = $field_name['raw'] .'='. $this->db->Quote($value);
                            break;
                        case 'password':
                            // Get password only via POST !!
                            $pw = trim($value);
                            $pw1 = $this->formsgeneration->GetInputValue("{$field_name['raw']}_1");

                            // If password is empty leave it as it is
                            if ($pw != '' && $pw != '*****') {
                                if ($pw != $pw1) {
                                    $APerr->setError(lang('Invalid Password!'));
                                    return;
                                }

                                $fields[] = $field_name['raw'] ."='". cryptPassword($pw) . "'";
                            }

                            break;
                        case 'real':
                            $tmp = strtr($value, ',', '.');
                            $fields[] = $field_name['raw'] .'='. (empty($tmp) ? 'NULL': $tmp);
                            break;
                        case 'date':
                            $tmp = phpDateToMyDate($value);
                            $fields[] = $field_name['raw'] .'='. (empty($tmp) ? 'NULL': $tmp);
                            break;
                        case 'mylink':
                            debug_r(
                                'DBTABLE',
                                $_GET,
                                "[DBTABLE, updateRecord, mylink]: {$field_name['raw']} = {$value}, 'GET="
                            );

                            if ($this->formsgeneration->GetCheckedState($field_name['raw'] .'_DELETE')) {
                                $fields[] = $field_name['raw'] .'=NULL';
                                // reset ApplicationData and Checkbox
                                $this->formsgeneration->SetInputProperty(
                                    $field_name['raw'] .'_DELETE',
                                    'ApplicationData',
                                    ''
                                );
                                $this->formsgeneration->SetCheckedState($field_name['raw'] .'_DELETE', false);
                            } elseif ($value != '') {
                                $destFileName = copyUploadFile($field_name['raw'], $uploadID);

                                if ($destFileName != '') {
                                    $fields[] = $field_name['raw'] ."='{$_SERVER['DEST_HTTP_DIR']}${destFileName}'";
                                    // Set ApplicationData field to name of image, so it will be displayed
                                    $this->formsgeneration->SetInputProperty(
                                        $field_name['raw'] . '_DELETE',
                                        'ApplicationData',
                                        $destFileName
                                    );
                                }
                            }
                            break;
                        case 'myref':
                            $tmp = $value;

                            if (!is_int($tmp)) {
                                $tmp = "'{$tmp}'";
                            }

                            $fields[] = $field_name['raw'] .'='. (($tmp == '') ? 'NULL': $tmp);
                            break;
                        default:
                            $fields[] = $field_name['raw'] .'='. (($value == '') ? 'NULL': $this->db->Quote($value));
                            break;
                    }
                }
            }

            $fieldStr = join(',', $fields);

            $sql = "UPDATE {$this->table} SET {$fieldStr} WHERE {$this->where}";
            debug('DBTABLE', "[DBTABLE, updateRecord]: SQLCMD: {$sql}");

            if ($error === false) {
                if ($this->db->Execute($sql) === false) {
                    $APerr->setFatal(
                        __FILE__,
                        __LINE__,
                        $this->db->errormsg(),
                        "SQL: {$sql}",
                        lang('Please correct errors')
                    );
                } else {
                    logEntry('UPDATE', $sql);

                    // Update registered subtables also
                    foreach ($this->getSubTableNames() as $subTableName) {
                        $this->updateSubTable($subTableName);
                    }
                }
            } else {
                $APerr->setFatal(__FILE__, __LINE__, lang('Please correct errors'));
            }

            return !$error;
        } else {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: $sql", lang('More than one record !'));
            return false;
        }
    }

    function insertRecord($presetVals = array()) {
        global $APerr;

        // Therefore we need to validate the submitted form values.
        if (($error_message = $this->formsgeneration->Validate($verify)) == '') {
            // It's valid, set the $doit flag variable to 1 to tell the form is ready to processed.
            $doit = 1;
        } else {
            /*
             * It's invalid, set the $doit flag to 0 and encode the returned error message
             * to escape any HTML special characters.
             */
            $doit = 0;
            $error_message = nl2br(HtmlSpecialChars($error_message));
            debug('DBTABLE', "[DBTABLE, updateRecord] Formserror: {$error_message}");
            $APerr->setFatal(__FILE__, __LINE__, $error_message);
            return false;
        }

        $sql = "SELECT {$this->fields} FROM {$this->table} WHERE {$this->where}";
        $rs = $this->db->SelectLimit($sql, 1);

        if ($rs == false) {
            $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
            return null;
        }

        $fields = array();
        $field_val = array();
        $error = false;

        if ($rs->RecordCount() > 0) {
            $APerr->setError(sprintf(lang('Record with %s already exists!'), $this->where));
            return null;
        }

        // Set predefined values anyway
        foreach ($presetVals as $key => $val) {
            $fields[] = $key;
            $field_val[] = (string) $val;
        }

        for ($i = 0; $i < $rs->FieldCount(); $i++) {
            $field_name = resolveFieldIndex($rs, $i);

            // Ignore ID column and
            // Insert subtables later as we need the id of this row !!
            if (($field_name['raw'] == 'id' && $field_name['type'] == 'int')
                || isset($this->subTableArr[$field_name['raw']])) {
                continue;
            }

            if (empty($presetVals[$field_name['raw']])) {
                $tmpVal = strtr($this->formsgeneration->GetInputValue($field_name['raw']), array('"' => '&quot;'));
                debug('DBTABLE', "[DBTABLE: insertRecord] NAME: {$field_name['raw']}, VAL: {$tmpVal}");
            }

            if (!$this->checkValue($field_name, $tmpVal, $this->checkArr)) {
                $error = true;
            } else {
                switch ($field_name['type']) {
                    case 'varchar':
                    case 'string':
                        $fields[] = $field_name['raw'];
                        $field_val[] = $this->db->qstr($tmpVal);
                        break;
                    case 'password':
                        $pw = getGlobVar($field_name['raw'], '', 'P');
                        $pw1 = getGlobVar($field_name['raw']. '_1', '', 'P');

                        if ($pw != $pw1) {
                            echo '<H1>Ungueltiges Passwort !!<H1>';
                            exit;
                        }

                        $fields[] = $field_name['raw'];
                        $field_val[] = "'". cryptPassword(getGlobVar($field_name['raw'], '', 'P')) ."'";
                        break;
                    case 'date':
                        $fields[] = $field_name['raw'];
                        $field_val[] = empty($tmpVal) ? 'NULL' : phpDateToMyDate($tmpVal);
                        break;
                    case 'real':
                        $tmpVal = strtr($tmpVal, ',', '.');
                        $fields[] = $field_name['raw'];
                        $field_val[] = ($tmpVal == '') ? 'NULL' : $tmpVal;
                        break;
                    case 'mylink':
                        if (getGlobVar($field_name['raw']) != '') {
                            $id = getNextSequenceNumber($this->table, 'id');
                            $destFileName = copyUploadFile($field_name['raw'], $id);

                            if ($destFileName != '') {
                                $fields[] = $field_name['raw'];
                                $field_val[] = "'{$_SERVER['DEST_HTTP_DIR']}${destFileName}'";
                            }
                        }
                        break;
                    default:
                        $fields[] = $field_name['raw'];
                        $field_val[] = ($tmpVal == '') ? 'NULL' : $this->db->qstr($tmpVal);
                        break;
                }
            }
        }

        $fieldStr = join(',', $fields);
        $fieldValStr = join(',', $field_val);

        debug('DBTABLE', "[DBTABLE: insertRecord] FIELDS: {$fieldStr}\nFIELD_VAL: {$fieldValStr}");

        $sql = "INSERT INTO {$this->table} ({$fieldStr}) VALUES ({$fieldValStr})";
        debug('DBTABLE', "[DBTABLE: insertRecord] SQLCMD: {$sql}");

        if ($error === false) {
            if ($this->db->Execute($sql)) {
                $insertID = $this->db->Insert_ID();
                logEntry('INSERT', "ID: {$insertID}, SQL: {$sql}");

                foreach (array_keys($this->subTableArr) as $subTable) {
                    $this->subTableArr[$subTable]->setMasterTable($insertID);
                    $this->updateSubtable($subTable);
                }
            } else {
                $APerr->setFatal(__FILE__, __LINE__, $this->db->errormsg(), "SQL: {$sql}");
                $insertID = null;
            }
        } else {
            $insertID = null;
        }

        return $insertID;
    }
}
