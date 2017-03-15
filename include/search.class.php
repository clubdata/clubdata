<?php
/**
 * Search class
 *
 * @package Clubdata
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
require_once('include/dbtable.class.php');

/**
 *
 * @package Clubdata
 */
class Search extends DbTable {
    var $memberID;
    var $searchType;
    var $defVals;
    var $defCmps;
    var $query;
    var $columnNames;

    var $hideCols = array();
    var $adrObj;

    var $headArr = array();
    var $view;

    /*
     * defaults[tablename][columnname]['Compare'] = comparemode;
     * defaults[tablename][columnname]['Values'][0] = value0;
     * ...
     * defaults[tablename][columnname]['Values'][N] = valueN;
     */
    function Search ($db, &$formsgeneration, $searchType, $view, $defaults = array())
    {
        parent::DBTable($db,$formsgeneration,'','');

        $this->db = $db;
        $this->searchType = $searchType;
        $this->view = $view;

        debug('M_SEARCH', 'CLASS SEARCH: Default values');
        debug_r('M_SEARCH', $defaults, 'defaults');

        $defaults = array_change_key($defaults, create_function('$a', 'return clubdata_mysqli::replaceDBprefix($a, DB_TABLEPREFIX);'));

        debug_r('M_SEARCH', (array)getGlobVar('defVals'), 'defVals');
        $this->defVals = array_merge($defaults, (array)getGlobVar('defVals'));
        debug_r('M_SEARCH', $this->defVals, 'this->defVals');

//         $this->defCmps = array_merge($defCmps, getGlobVar('defCmps'));

        $this->displayCols = getGlobVar('DisplayCols','', 'S');
/*        $_SESSION['DisplayCols'] = $this->displayCols;
*/
//          print('<PRE>'); print_r($this->defVals); print('</PRE>');

        switch ( $searchType )
        {
            case 'Advanced':
                $this->adrObj = new Addresses($this->db, $this->formsgeneration);

                $this->query = $this->adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID',
                                                           '`###_Members`.MemberID');
                //          echo "<PRE>";print_r($addresstypes); echo "</PRE>";
                $addresstypes = $this->adrObj->getAddresstypes();
                for ( $i=0; $i < count($addresstypes) ; $i++ )
                {
                    $adrID = $addresstypes[$i]['id'];
                    $this->adrObj->setAddressType($adrID);
                    $adrColNames[] = $this->adrObj->getFieldList("Addresses_$adrID");
                }
                // FD20100925: No backticks ` for table names as they are added automatically by MetaColums in getFieldList
                foreach (array('###_Members', '###_Members_Attributes') as $table)
                {
                	$dbTableObj = new DBTable($this->db, $this->formsgeneration, $table, '1');
                    $adrColNames[] = $dbTableObj->getFieldList($table, array(/* '`###_Members`.MemberID' */'MemberID'));
                }
                $this->columnNames = '`###_Members`.`MemberID` as `###_Members%MemberID`, ' . join(',', $adrColNames);

//                 print("COL: " . $this->columnNames . "<BR>");

                $tabArr = $adrColNames;

                $this->hideCols = array('`###_Members`.MemberID' => 1,
                                        'Adr_MemberID' => 1,
                                        'id' => 1,);
                break;

            case 'Simple':
                $this->adrObj = new Addresses($this->db, $this->formsgeneration);

                // FD20100925: Added ### to Mailingtype alias, so that advances search works for mailingtypes
                $this->query = $this->adrObj->generateAdrTableList('`###_Members` LEFT JOIN `###_Members_Attributes` ON `###_Members`.MemberID = `###_Members_Attributes`.MemberID LEFT JOIN `###_Addresses` `Addresses` ON `###_Members`.MemberID = `Addresses`.`Adr_MemberID` LEFT JOIN `###_Addresses_Mailingtypes` `###_Addresses_Mailingtypes` ON `Addresses`.`id` = `###_Addresses_Mailingtypes`.`AddressID`',
                                                           '`###_Members`.MemberID');
                $easy = getConfigEntry($this->db, 'EasySearch');
                if ( !empty($easy) )
                {
                    $cols = array();
                    $cols[] = "`###_Members`.`MemberID` as `###_Members%MemberID`";

                    # $arr[row][0] = whole columnname (with ,)
                    # $arr[row][1] = tablename with .
                    # $arr[row][2] = tablename without  .
                    # $arr[row][3] = columnname
                    $res=preg_match_all("/(([^,.]*)\.)?([^,]*)[, ]*/",$easy,$arr,PREG_SET_ORDER);

//                     print "EASY: $easy<BR>";
                    foreach ( $arr as $rowArr )
                    {
                        if ( !empty($rowArr[0]) )
                        {
//                             print "COLS: $rowArr[0], $rowArr[2], $rowArr[3]<BR>";
                            $cols[] = formatColumn($rowArr[2], $rowArr[3]);
                        }
                    }

                    if ( is_array($this->defVals) )
                    {
                        foreach ( $this->defVals as $tableName => $valArr )
                        {
                            foreach ( $valArr as $columnName => $value )
                            {
//                                 echo "DEF: [$tableName][$columnName] = $value<BR>";
                                $key = formatColumn($tableName, $columnName);
//                                 echo "FORM: KEY: $key<BR>";

                                if ( in_array($key, $cols) === false )
                                {
                                    $cols[]=$key;
                                }
                            }
                        }
                    }

                    // add all preset columns to the default search
                    // Force important columns
                    if ( in_array('`###_Members`.Membertype_ref AS `###_Members%Membertype_ref`',$cols) === false )
                    {
                        $cols[] = "`###_Members`.Membertype_ref AS `###_Members%Membertype_ref`";
                    }
                    $this->columnNames = join(",", $cols);
//                       echo "COL: $this->columnNames<BR>";
                }
                else
                {
                    $this->columnNames = '*';
                }
                $tabArr = array_merge($this->db->MetaColumnNames('`###_Members`'),
                                        $this->db->MetaColumnNames('`###_Members_Attributes`'),
                                        $this->db->MetaColumnNames('`###_Addresses`'),
                                        $this->db->MetaColumnNames('`###_Addresses_Mailingtypes`'));
                break;
            case 'Payments':
                $this->query = '`###_Payments`';
                $this->columnNames = '*';
                $tabArr = $this->db->MetaColumnNames('`###_Payments`');
                break;

            case 'Fees':
                $this->query = '`###_Memberfees`';
                $this->columnNames = '*';
                $tabArr = $this->db->MetaColumnNames('`###_Memberfees`');
                break;
            case 'Conferences':
                $this->query = '`###_Conferences`';
                $this->columnNames = '*';
                $tabArr = $this->db->MetaColumnNames('`###_Conferences`');
                break;

        }

        // print("<PRE>");print_r($tabArr);print_r($this->defVals); print("</PRE>");
/** @todo Der Code funktioniert nicht !!! Einfach mal mit obigen print anschauen. Index ist nicht integer, sonder text, defVals hat als erstes eine Tabelle und keine Spalte !
*/
        for ($i=0 ; $i < count($tabArr); $i++ )
        {
            // DO NOT OVERWRITE ALREADY SET DEFVALS !!
            if ( isset($tabArr[$i]) && isset($this->defVals[$tabArr[$i]]) === false )
            {
                $param = getGlobVar($tabArr[$i],'','PG');
                if ( (!is_array($param) && !empty($param) ) ||
                        ( is_array($param) && count($param) > 1 ))
                {
                        $this->defVals[$tabArr[$i]] = $param;
                        $this->defCmps[$tabArr[$i]] = getGlobVar($tabArr[$i]. '_select','','PG');
                }
            }
//              echo "$tabArr[$i] = $param : " . $this->defVals[$tabArr[$i]] . '(' . isset($this->defVals[$tabArr[$i]])  . ")<BR>\n";
        }
        debug_r('M_SEARCH', $this->defVals, '(Endof Search) this->defVals');


    }

    function displaySearch()
    {
//         print('<PRE>'); print_r($this->defVals); print('</PRE>');
        $this->showRecordDetails($this->formsgeneration, NO_EDIT, '');
    }

    //($rs, $defVals = '',$defCmps = '', $table='')
    function showRecordDetails($formsgeneration, $edit, $titel)
    {
        global $APerr;
        $this->headArr = array();

        $sql = "select $this->columnNames from $this->query";
//		print("COL: $this->columnNames<BR>QUERY: $this->query");
		
        $tmpFetchMode = $this->db->SetFetchMode(ADODB_FETCH_NUM);
        $rs = $this->db->selectlimit($sql, 1);
        $this->db->SetFetchMode($tmpFetchMode);
        if ( $rs == false )
        {
            $APerr->setFatal(__FILE__,__LINE__,$this->db->errormsg(),"SQL: $sql");
            return;
        }

        if ( ! is_array($this->defVals) )
        {
            $this->defVals = array();
        }

        $aktTablename = '';
        $errTxt = '';
        for ( $i=0 ; $i < $rs->FieldCount(); $i++ )
        {
            $name = resolveFieldIndex($rs, $i);
//            print("NAME:<PRE>");print_r($name);print("</PRE>");
            if ( isset($this->hideCols[$name['raw']]) && $this->hideCols[$name['raw']] == 1 )
            {
                continue;
            }

            $ft = $rs->FetchField($i);
            $size = $ft->length;
            $mtype = $rs->MetaType($ft->type);

            // Take table prefix into account for mailing types
//            print("$name[table]: ". preg_match("/^(" . DB_TABLEPREFIX . ")?Addresses_Mailingtypes_/", $name['table']) . "<BR>");
            
            if ( $aktTablename != $name['table'] && 
            	 preg_match("/^(" . DB_TABLEPREFIX . ")?Addresses_Mailingtypes_/", $name['table']) == 0
            		// substr($name['table'],0,23) != 'Addresses_Mailingtypes_' 
            		)
            {
//            	print("FOUND: $name[table]<BR>");
            	//FD20110101: Make DB_TABLEPREFIX optional
                if (preg_match("/^(" . DB_TABLEPREFIX . ")?Addresses_(\d+)$/", $name['table'], $retArr) > 0)
                {
                    $adrType = $retArr[2];
                    $this->adrObj->setAddressType($adrType);
                    $headLine = lang('Address') . ' ' . getDescriptionTxt($this->adrObj->getAddressInfo());
                }
                else
                {
                	//FD20110101: Delete DB_TABLEPREFIX if present
                	$tmpName = preg_replace("/^" . DB_TABLEPREFIX . "/", '', $name['table']);
                    $headLine = lang($tmpName);
                }
                $this->headArr[$name['table']] = $headLine;
                $aktTablename = $name['table'];
            }

            $showsize = 1;

            /* Get predefined value (if any) */
            $preDefCmp = $preDefVal = '';
            if ( !empty($this->defVals[$name['table']][$name['column']]) )
            {
                $preDefVal = $this->defVals[$name['table']][$name['column']]['Value'];
                $preDefCmp = $this->defVals[$name['table']][$name['column']]['Compare'];
//                  debug('M_SEARCH', "Table: %s, Column: %s, DefVal: %s, DefCmp: %s", $name['table'], $name['column'], $preDefVal, $preDefCmp);
            }
            debug_r("M_SEARCH", $this->defVals, "TMPVAL (this->defVals[$name[table]][$name[column]][Value]): ($preDefCmp) $preDefVal");

            switch($name['type'])
            {
                case 'password':
                    // Can't search for password, aie...
                    break;

                case 'myref':
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . "_select",
                                        "ID"=>$name['raw'] . "_select",
                                        "SIZE"=>1,
//                                         "VALUE"=>(empty($preDefCmp)?'INSelection':$preDefCmp),
                                        "VALUE"=>empty($preDefCmp)?'INSelection':$preDefCmp,
                                        "OPTIONS"=>array(  'INSelection' => lang('Selection'),
                                                           'NOTINSelection' => lang('Not Selection')),
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>1,
                                        "NAME"=>$name['raw'],
                                        "ID"=>$name['raw'],
                                        "SIZE"=>1,
                                        "CLASS"=>"MULTIPLE",
                                        "SELECTED"=>empty($preDefVal)?getDefaultSelection($name['reftable'],false):(array)$preDefVal,
                                        "OPTIONS"=>getOptionArray($name['reftable'],NULL,true),
                                        ));
                    break;

                case 'yesno':
                    debug('M_SEARCH', "PREDEFCMP: $preDefCmp, PREDEFVAL: $preDefVal");
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . "_select",
                                        "ID"=>$name['raw'] . "_select",
                                        "SIZE"=>1,
                                        "VALUE"=>empty($preDefCmp)?'eq':$preDefCmp,
                                        "OPTIONS"=>array('eq' => '=',
                                                         'ne'=>'<>'),
                                        "ValidateAsSet"=>1,
                                        "ValidationErrorMessage"=>"It were not specified any interests.",
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'],
                                        "ID"=>$name['raw'],
                                        "SIZE"=>1,
                                        "VALUE"=>$preDefVal,
                                        "OPTIONS"=>array('' => '',
                                                         '1' => lang('Yes'),
                                                         '0' => lang('No')),
                                        ));
                    break;
                case 'int4':
                case 'int':
                case 'real':
                case 'year':
//                     $preDefVal = (isset($this->defVals[$name['raw']]) ? $this->defVals[$name['raw']]: '');
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . "_select",
                                        "ID"=>$name['raw'] . "_select",
                                        "SIZE"=>1,
                                        "VALUE"=>empty($preDefCmp)?'eq':$preDefCmp,
                                        "OPTIONS"=>array(  'eq' => '=',
                                                            'gt' => '>',
                                                            'lt' => '<',
                                                            'ge' => '>=',
                                                            'le'=>'<=',
                                                            'ne'=>'<>',
                                                            'INint' => lang('Selection'),
                                                            'BETint' => lang('Between'),
                                                            'IsEmpty' =>  lang('Is empty'),
                                                            'IsNotEmpty' =>  lang('Is not empty')),
                                        "ONCHANGE"=>'checkIfRange(this);'
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"text",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'],
                                        "ID"=>$name['raw'],
                                        "SIZE"=>$showsize,
                                        "VALUE"=>$preDefVal,
                                        "onKeyPress"=>"'checkEnter(event)'"
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"text",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . '_rangeEnd',
                                        "ID"=>$name['raw'] . '_rangeEnd',
                                        "SIZE"=>$showsize,
                                        "VALUE"=>$preDefVal,
                                        "onKeyPress"=>"'checkEnter(event)'"
                                        ));
                    break;

                case 'date':
//                     $preDefVal = (isset($this->defVals[$name['raw']]) ? $this->defVals[$name['raw']]: '');
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . "_select",
                                        "ID"=>$name['raw'] . "_select",
                                        "SIZE"=>1,
                                        "VALUE"=>empty($preDefCmp)?'eqd':$preDefCmp,
                                        "OPTIONS"=>array('eqd' => '=',
                                                        'gtd' => '>',
                                                        'ltd' => '<',
                                                        'ged' => '>=',
                                                        'led'=>'<=',
                                                        'ned'=>'<>',
                                                        'INdate' => lang('Selection'),
                                                        'BETdate' => lang('Between'),
                                                        'IsEmpty' =>  lang('Is empty'),
                                                        'IsNotEmpty' =>  lang('Is not empty')),
                                        "ONCHANGE"=>'checkIfRange(this);'
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"custom",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "CustomClass"=>"form_date_class",
                                        "Format"=>"{day}.{month}.{year}",
                                        "YearClass"=>"DATUM",
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'],
                                        "ID"=>$name['raw'],
                                        "SIZE"=>$showsize,
                                        "VALUE"=>$preDefVal,
                                        "onKeyPress"=>"'checkEnter(event)'",
                                        "Optional" => 1
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"custom",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "CustomClass"=>"form_date_class",
                                        "Format"=>"{day}.{month}.{year}",
                                        "YearClass"=>"DATUM",
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . "_rangeEnd",
                                        "ID"=>$name['raw'] . "_rangeEnd",
                                        "SIZE"=>$showsize,
                                        "VALUE"=>$preDefVal,
                                        "onKeyPress"=>"'checkEnter(event)'",
                                        "Optional" => 1
                                        ));
                    break;

                case 'varchar':
                case 'string':
                default:
//                     debug('M_SEARCH', "PREDEFCMP: $preDefCmp, PREDEFVAL: $preDefVal");
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"select",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'] . "_select",
                                        "ID"=>$name['raw'] . "_select",
                                        "SIZE"=>1,
                                        "VALUE"=>empty($preDefCmp)?'Contains':$preDefCmp,
                                        "OPTIONS"=>array('Contains'=>  lang('Contains'). ' (a=A)',
                                                        'Start'=>     lang('Begins with'). ' (a=A)',
                                                        'End'=>       lang('Ends with'). ' (a=A)',
                                                        'Exact'=>     lang('Exact'). ' (a=A)',
                                                        'INstring' => lang('Selection'),
                                                        'IsEmpty' =>  lang('Is empty'),
                                                        'IsNotEmpty' =>  lang('Is not empty')),
                                        ));
                    $errTxt .= $this->formsgeneration->AddInput(array(
                                        "TYPE"=>"text",
                                        "LABEL"=>helpAndText($name['table'],$name['raw'],$name['pretty']),
                                        "MULTIPLE"=>0,
                                        "NAME"=>$name['raw'],
                                        "ID"=>$name['raw'],
                                        "SIZE"=>$showsize,
                                        "VALUE"=>$preDefVal,
                                        "onKeyPress"=>"'checkEnter(event)'"
                                        ));
                   break;
            }
        }

        $errTxt .= $this->formsgeneration->AddInput(array(
                        "TYPE"=>"hidden",
                        "NAME"=>'Command',
                        "ID"=>'Command',
                        "VALUE"=>$this->view,
                        ));

#        debug_r("M_SEARCH", $this->formsgeneration, "[SEARCH, showRecordDetails] formsgeneration");

        debug("M_SEARCH", "[SEARCH, showRecordDetails] wasSubmitted " . $this->formsgeneration->wasSubmitted('Command'));
        $errTxt .= $this->formsgeneration->LoadInputValues($this->formsgeneration->wasSubmitted('Command'));

        if ( !empty($errTxt) )
        {
            $APerr->setFatal(__FILE__,__LINE__,$errTxt);
        }
    }

    Function generateSelectCMD()
    {
        global $APerr;

        $selectCMD = "";
        $error = false;
        $this->showRecordDetails($this->formsgeneration,'','');
        /*
        * Therefore we need to validate the submitted form values.
        */
        if(($error_message=$this->formsgeneration->Validate($verify))=="")
        {

            /*
            * It's valid, set the $doit flag variable to 1 to tell the form is ready to
            * processed.
            */
            $doit=1;

        }
        else
        {

            /*
            * It's invalid, set the $doit flag to 0 and encode the returned error message
            * to escape any HTML special characters.
            */
            $doit=0;
            $error_message=nl2br(HtmlSpecialChars($error_message));
            debug("M_SEARCH", "[SEARCH, generateSelectCMD] Formserror: $error_message");
            $APerr->setFatal(__FILE__,__LINE__,$error_message);
            return false;
        }

        /*
        * Search parameters:
        *  look for all parameter which ends by "_select", take the first part
        *  and get the value to search for.
        *  Also splits the variable name into table and column part
        *  $paramArr[table][Column][select]
        *                          {value]
        */
        unset($paramArr);
        foreach ( array_keys($_REQUEST) as $key)
        {
            if ( substr($key,-7) == "_select" )
            {
                $tmp = substr($key,0,-7);
                $tmpArr = unformatColumn($tmp);
                $value = $this->formsgeneration->GetInputValue($tmp);
                debug_r('MAIN', $value, "[Search, generateSelectCMD]: KEY: $tmp, value:");
                if ( !empty($value) )
                {
                    $paramArr[$tmpArr['table']][$tmpArr['column']]['select'] =
                            $this->formsgeneration->GetInputValue($key);
;
                    $paramArr[$tmpArr['table']][$tmpArr['column']]['value'] =
                            $value;

                    $endRange = $this->formsgeneration->GetInputValue($tmp . "_rangeEnd");
                    if ( !empty($endRange) )
                    {
                      $paramArr[$tmpArr['table']][$tmpArr['column']]['rangeEnd'] =
                              $endRange;
                      debug('MAIN', "[Search, generateSelectCMD]: PARAMARR RANGEEND: NAME=$key,SELECT=".$paramArr[$tmpArr['table']][$tmpArr['column']]['select'].",TMP={$tmp}_rangeEnd,VAL=".$paramArr[$tmpArr['table']][$tmpArr['column']]['rangeEnd']);
                    }
                    debug('MAIN', "[Search, generateSelectCMD]: PARAMARR: NAME=$key,SELECT=".$paramArr[$tmpArr['table']][$tmpArr['column']]['select'].",TMP=$tmp,VAL=".$paramArr[$tmpArr['table']][$tmpArr['column']]['value']);
                }
            }
        }

        debug_r('MAIN', $paramArr, "[Search, generateSelectCMD]: paramArr");

        foreach ( array_keys($paramArr) as $table )
        {
            foreach ( array_keys($paramArr[$table]) as $column )
            {

            $select = $paramArr[$table][$column]['select'];
            $val = $paramArr[$table][$column]['value'];
            $name = !empty($table) ? ($table . "." . $column) : $column;

            /*
            * The parameter might have several types:
            *
            * 1. a scalar is treated as is
            * 2. an array with one element, is converted to a scalar and treated like a scalar
            * 3. an array wirh more than one elements:
            * 3.a an array with two elements and the first element is the empty string:
            *       The empty string is deleted, the remaining array is treated like 2)
            * 3.b all other arrays:
            *       If the first element is the empty string, it is deleted,
            *       The remaining array is treated as is.
            *
            * N.B.: The empty string is selectable by the user and normally means "don't care".
            *       So the user is able to select "dont't care" and other entries in the selection list
            *       And as he can select it, he will select it. So we have to take care here.
            */
            if ( is_array($val))
            {
                // Case 2)
                if ( count($val) == 1 )
                {
                    $val = $val[0];
                }
                // Case 3a)
                elseif ( count($val) == 2 && empty($val[0]) )
                {
                    $val = $val[1];
                }
                // Case 3b)
                elseif ( count($val) > 2 && empty($val[0]) )
                {
                    array_shift($val);
                }
            }



            if ( is_array($val) )
            {
//                 echo "NAME: $name, VAL: "; print_r($val); echo "<BR>";
                switch($select)
                {
                    case "ContInd":
                    case "StartInd":
                    case "EndInd":
                    case "ExactInd":
                    case "RegExpInd":
                    case "RegExp":
                    case "Contains":
                    case "Start":
                    case "Begins":
                    case "End":
                    case "Ends":
                    case "Exact":
                    case "eqd":
                    case "gtd":
                    case "ltd":
                    case "ged":
                    case "led":
                    case "ned":
                    case "INstring":
                    case "INdate":
                        $selectCMD[] = "$name IN (" . join(",", array_map("addStrings",$val)) . ")";
                        break;

                    case "IsEmpty":
                        $selectCMD[] = "$name IS NULL";
                        break;

                    case "IsNotEmpty":
                        $selectCMD[] = "$name IS NOT NULL";
                        break;

                    case "INSelection":
                        //echo "1, MType: $mtype, VAL: !$val!<BR>\n";
                        $selectCMD[] = "$name IN (" . join(",", array_map("addStrings",$val)) . ")";
                        break;

                    case "NOTINSelection":
                        // get id column (MemberID or id )
                        $colArr = $this->db->MetaColumnNames($table);
                        if ( isset($colArr['MemberID']) || isset($colArr['MEMBERID']) )
                        {
                            $idCol = 'MemberID';
                        }
                        elseif ( isset($colArr['id'])  || isset($colArr['ID']) )
                        {
                            $idCol = 'id';
                        }
                        else
                        {
                            $APerr->setFatal(__FILE__,__LINE__,'NOTINSelection: No id column found');
                            return;
                        }

#                        $selectCMD[] = "NOT EXISTS (SELECT $idCol FROM $table WHERE `###_Members`.MemberID = $table.$idCol AND $name IN (" . join(",", array_map("addStrings",$val)) . "))";
                         $selectCMD[] = "$name NOT IN (" . join(",", array_map("addStrings",$val)) . ")";
                         break;

                    default:
                        $selectCMD[] = "$name IN (" . join(",", $val) . ")";
                        break;
                }
            }
            else
            {

//                  echo "Suche: $name: $val, ($select)<BR>";
                if ( ($val != "" && $select != "") || ($select == "IsEmpty" || $select == "IsNotEmpty")) //
                {
                    if (get_magic_quotes_gpc ())
                    {
                        $val=stripslashes($val);
                    }
                    debug('MAIN', "[generateSelectCMD]: NAME=$name,SELECT=$select");
                    switch($select)
                    {
                        case "ContInd":
                            $selectCMD[] = "$name ~* '" . sql_regcase($val) . "' ";
                            break;

                        case "StartInd":
                            $selectCMD[] = "$name ~* '^" . sql_regcase($val) . "' ";
                            break;

                        case "EndInd":
                            $selectCMD[] = "$name ~* '" . sql_regcase($val) . "$' ";
                            break;

                        case "ExactInd":
                            $selectCMD[] = "$name ~* '^" . sql_regcase($val) . "$'";
                            break;

                        case "RegExpInd":
                            $selectCMD[] = "$name ~* '$val' ";
                            break;

                        case "RegExp":
                            $selectCMD[] = "$name ~ '$val' ";
                            break;

                        case "Contains":
                            $selectCMD[] = "$name like '%$val%' ";
                            break;

                        case "Start":
                        case "Begins":
                            $selectCMD[] = "$name like '$val%' ";
                            break;

                        case "End":
                        case "Ends":
                            $selectCMD[] = "$name like '%$val' ";
                            break;

                        case "Exact":
                            $selectCMD[] = "$name like '$val' ";
                        break;

                        case "eq":
                            $selectCMD[] = "$name = $val";
                            break;

                        case "gt":
                            $selectCMD[] = "$name > $val ";
                            break;

                        case "lt":
                            $segeneriereSelectCMDlectCMD[] = "$name < $val ";
                            break;

                        case "ge":
                            $selectCMD[] = "$name >= $val ";
                            break;

                        case "le":
                            $selectCMD[] = "$name <= $val ";
                            break;

                        case "ne":
                            $selectCMD[] = "$name = $val";
                            break;

                        case "eqd":
                            $selectCMD[] = "$name = " . phpDateToMyDate($val) . " ";
                            break;

                        case "gtd":
                            $selectCMD[] = "$name > " . phpDateToMyDate($val) . " ";
                            break;

                        case "ltd":
                            $selectCMD[] = "$name < " . phpDateToMyDate($val) . " ";
                            break;

                        case "ged":
                            $selectCMD[] = "$name >= " . phpDateToMyDate($val) . " ";
                            break;

                        case "led":
                            $selectCMD[] = "$name <= " . phpDateToMyDate($val) . " ";
                            break;

                        case "ned":
                            $selectCMD[] = "$name <> " . phpDateToMyDate($val) . " ";
                            break;

                        case "INstring":
                        case "INdate":
                            $selectCMD[] = "$name IN (". join(",", array_map("addStrings",preg_split ("/[\s,]+/",$val,-1, PREG_SPLIT_NO_EMPTY))) .") ";
                            break;

                        case "INint":
                            $selectCMD[] = "$name IN ($val) ";
                            break;

                        case "BETdate":
/*                            $tmpVals = explode(";", $val);
                            if ( count($tmpVals) != 2 )
                            {
                                echo "INVALID PARAMETER for $name: $val<BR>";
                                $selectCMD[] = "$name > " . trim(phpDateToMyDate($tmpVals[0])) . " ";
                            }
                            else
                            {
                                $selectCMD[] = "($name >= " . trim(phpDateToMyDate($tmpVals[0])) . " AND $name <= " . trim(phpDateToMyDate($tmpVals[1])) . ") ";
                            }
                            */
                            $selectCMD[] = "($name >= " . trim(phpDateToMyDate($val)) . " AND $name <= " . trim(phpDateToMyDate($paramArr[$table][$column]['rangeEnd'])) . ") ";
                            break;

                        case "BETint":
//                             $tmpVals = explode(";", $val);
//                             if ( count($tmpVals) != 2 )
//                             {
//                                 echo "INVALID PARAMETER for $name: $val<BR>";
//                                 $selectCMD[] = "$name > " . trim($tmpVals[0]) . " ";
//                             }
//                             else
//                             {
//                                 $selectCMD[] = "($name >= " . trim($tmpVals[0]) . " AND $name <= " . trim($tmpVals[1]) . ") ";
//                             }

                            $selectCMD[] = "($name >= " . trim($val) . " AND $name <= " . trim($paramArr[$table][$column]['rangeEnd']) . ") ";
                            break;

                        case "INSelection":
                            //echo "1, MType: $mtype, VAL: !$val!<BR>\n";
                            $selectCMD[] = "$name IN (". join(",", array_map("addStrings",preg_split ("/[\s,]+/",$val,-1, PREG_SPLIT_NO_EMPTY))) .") ";
                            break;

                        case "NOTINSelection":
                            $colArr = $this->db->MetaColumnNames($table);
//                             echo "<PRE>";print_r($colArr);echo "</PRE>";
                            // keys of array returned by MetaColumnNames are all in upper letters
                            if ( isset($colArr['MEMBERID']) )
                            {
                                $idCol = 'MemberID';
                            }
                            // keys of array returned by MetaColumnNames are all in upper letters
                            elseif ( isset($colArr['ID']) )
                            {
                                $idCol = 'id';
                            }
                            else
                            {
                                $APerr->setFatal(__FILE__,__LINE__,'NOTINSelection: No id column found');
                                return;
                            }

                            $selectCMD[] = "NOT EXISTS (SELECT $idCol FROM $table WHERE `###_Members`.MemberID = $table.$idCol AND $name IN (" . join(",", array_map("addStrings",preg_split ("/[\s,]+/",$val,-1, PREG_SPLIT_NO_EMPTY))) .")) ";
                            break;

                    case "IsEmpty":
                        $selectCMD[] = "$name IS NULL";
                        break;

                    case "IsNotEmpty":
                        $selectCMD[] = "$name IS NOT NULL";
                        break;

                    default:
                            $APerr->setFatal(sprintf(lang("ERROR: Invalid QueryType=%s, name=%s, val=%s"), $select, $name, $val));
                            debug_backtr('MAIN');
                            $selectCMD[] = $name . " like  '%" . $val . "%'";
                            break;
                    }
                    }
                }
            }
        }
//         echo "selectCMD: " . join(" AND ", $selectCMD) . "<BR>";
        return (!empty($selectCMD) ? join(" AND ", $selectCMD) : '');
    }


}
?>