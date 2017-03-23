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
 * Startup declarations and general utility functions
 *
 *
 * This file does the startup configuration of Clubdata like defining constants, opening the database or reading the language files.
 * It contains also several utility functions.
 *
 */
/*
    function.php: Central include-file with a lot of function definitions
    Copyright (C) 2003 Franz Domes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/

if (defined('INCLUDE_FUNCTION')) {
	return 0;
} else {
	define('INCLUDE_FUNCTION', TRUE);
}

header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");                                   // HTTP/1.0

$version = "V2.03";

if (!defined("SCRIPTROOT") )
{
    define("SCRIPTROOT", _realpath($_SERVER["DOCUMENT_ROOT"] . LINKROOT));
//    print("DOCUMENT_ROOT: " . $_SERVER["DOCUMENT_ROOT"] . "<BR>SCRIPTROOT: " . SCRIPTROOT);
}

$sep = (SERVER_SYSTEM_TYPE == "UNIX") ? ":" : ";";

ini_set('include_path', ini_get('include_path') . "$sep" .
                        FORMSGENERATION_DIR . "$sep" .
                        PHP2EXCEL_DIR . "$sep" .
                        SCRIPTROOT . "$sep" .
                        SCRIPTROOT . "/include/phpmailer");

// FD20101118: Will be defined later by database !
//$suppLangs = array("UK", "DE", "FR", "NL");

// absolute path where to store uploaded pictures
define('DESTDIR', SCRIPTROOT . "/" . DEST_HTTP_DIR);

/**
 * XREFTABLE: Associative array, which links _xref column names to table names.
 *
 * eg ('FirmCountry_xref' => 'Country'):
 *    The values of the column FirmCountry_xref are the id column of table Country
 *
 */
/* FD20101121 Not used anymore
$xreftable = array( 'FirmCountry_xref' => 'Country',
                    'PrivatCountry_xref' => 'Country'
                  );
*/

$db = ADONewConnection(DB_TYPE);	# create a connection
if ( $db->PConnect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME) !== true )
{
	print "Cannot connect to database " . DB_NAME . "!<BR>" . $db->ErrorMsg() . "<BR>";
	exit;
}
if ( $db->Execute("SET NAMES '" . CHARACTER_ENCODING . "'") === false )
{
  print "Character set " . CHARACTER_ENCODING . " not supported: " . $db->ErrorMsg();
}
if ( $db->Execute("set character set " . CHARACTER_ENCODING) === false )
{
  print "Character set " . CHARACTER_ENCODING . " not supported: " . $db->ErrorMsg();
}

// Check if Database has correct version
if (  $auth->isIdentified === true )
{
  checkDBVersion($db);
}

$suppLangs = getSupportedLanguages();

$userLang = getClubUserInfo("Language_ref");
if ( is_string($userLang) && in_array($userLang, $suppLangs) )
{
    $language = $userLang;
}
// define some icons
$navigatorGif = getStyleFileLink("images/navigator.gif","");
$DelCross = getStyleFileLink("images/mini-cross.gif","");
$EditPencil = getStyleFileLink("images/mini-pencil.gif","");


// extract first part of script_name. It is used for Language import and hyperlinks.
// e.g.: MemberFees_Main.php => MemberFees
//       navigator.php => navigator
//eregi("^.*/([^_]*)_?[^_]*\.php$","$_SERVER[SCRIPT_NAME]",$regs);
preg_match("~^.*/([^_]*)_?[^_]*\.php$~",$_SERVER['SCRIPT_NAME'], $regs);
$skript_prefix = $regs[1];


// Read language file, if it exists
$langFile = SCRIPTROOT . "/Language/" . CHARACTER_ENCODING . "/$language.php";
if (is_readable($langFile) )
{
    require_once($langFile);
}
else
{
	#FD20150617
  $langFile = SCRIPTROOT . "/Language/UTF8/$language.php";
  if (is_readable($langFile) )
  {
      require_once($langFile);
  }
}
debug('MAIN', "CHARACTER_ENCODING: " . CHARACTER_ENCODING . ", LANG: $langFile<BR>");

define("HELP_CONTEXT_FILE", INDEX_PHP . "?mod=help&view=Context");

/**
 * function openDB
 *
 *  Description:
 *    Returns the database identifier
 *
 * Return:
 * @return object Returns the value of the global variable $db.
*/
function openDB()
{
    global $db;
    return $db;
}

/**
 * WINDOWS COMPATIBILITY FUNCTIONS
 *
 * The following function are needed to support Installation routines with windows
 */

/**
 *
 * checks if a directory is writeable. works also with windows (Attention there are two underscores!)
 * @param string $path path to check if the file or directory is writable
 * @return true if writable, false if not
 */
function is__writable($path) {
//will work in despite of Windows ACLs bug
//NOTE: use a trailing slash for folders!!!
//see http://bugs.php.net/bug.php?id=27609
//see http://bugs.php.net/bug.php?id=30931
//    print("PATH: $path<BR>");
    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
    {
        unlink("$path");

    }
    return true;
}

// fixes windows paths...
// (windows accepts forward slashes and backwards slashes, so why does PHP use backwards?
function fix_path($path) {
    return str_replace('\\','/',$path);
}

function _realpath($path)
{
  return fix_path(realpath($path));
}
/**************** END OF WINDOWS COMPATIBILITY FUNCTIONS ***************************/

//Check to see if it exists in case PHP has this function later
if (!function_exists("mb_substr_replace")){
   //Same parameters as substr_replace with the extra encoding parameter.
    function mb_substr_replace($string,$replacement,$start,$length=null,$encoding = null){
        if ($encoding == null){
            if ($length == null){
                return mb_substr($string,0,$start).$replacement;
            }
            else{
                return mb_substr($string,0,$start).$replacement.mb_substr($string,$start + $length);
            }
        }
        else{
            if ($length == null){
                return mb_substr($string,0,$start,$encoding).$replacement;
            }
            else{
                return mb_substr($string,0,$start,$encoding). $replacement. mb_substr($string,$start + $length,mb_strlen($string,$encoding),$encoding);
            }
        }
    }
}

/**
 * function getSupportedLanguages
 *
 * returns an array of supported languanges, defined by the language table
 *
 * @return array Array of supported languages like 'DE,'UK','FR'
 *
 */
function getSupportedLanguages()
{
    global $db;

    $sql = "SELECT id FROM `###_Language`";

    $suppLang = $db->getCol($sql);
    return $suppLang;
}

/**
 * function formatColumn
 *
 * Formats a table column name to the format table.column as `table.column`. It first trims all whitespaces + ` from the tablename.
 * Tablename may be empty, in which case no tablename is given in the resultstring
 *
 * @param string $table  tablename
 * @param string $column columnname
 * @param boolean $noAlias true: define an alias of the form 'tablename%columnname'; false do not define an alias
 *
 * @return string a sting with the formated columnname of the form [`tablename`.]`columnname` [AS `tablename%columnname`]
 *
 */
function formatColumn($table, $column, $noAlias = false)
{
    $table = trim($table,"` \t\r\n\0\x0b");
    return ( empty($table) ? "`$column`" : "`$table`.`$column`" . ($noAlias ? '' : " AS `$table%$column`"));
}

/**
 * function unformatColumn
 *
 * Separates a columnname of the form <table>%<columnname> and returns an array [column] and [table].  if no % is found, only the columnname is set
 * @see formatColumn
 *
 * @param string $column formated Columnname (tablename%columnname)
 *
 * @return array associative array whith keys table and column
 */
function unformatColumn($column)
{
    $res=preg_match("/^(([^%]*)[%])?(.*?)$/", $column,$fieldArr);

    $retArr['table'] = $fieldArr[2];
    $retArr['column'] = $fieldArr[3];
    return $retArr;
}

/*
 * function getMyRefTable
 *
 *  Description:
 *    Gets the row of the referenced table which belongs to the selected value
 *    If the selected value is empty, an empty array is returned
 *    This function supports numerical and alphanumerical select values
 *
 *  Parameter:
 *    $db          Link to database
 *    $selected    Value to be selected
 *    $table       name of table
 *
 * Return:
 *    Selected row of the referenced table as an array. The exact format depends
 *    on the table
 *
 * Side Effects:
 *    nothing
 */
function getMyRefTable($db, $selected, $table)
{
    if ( !isset($selected) )
    {
        $anArr  = array();
    }
    else
    {
        if ( strval(intval($selected)) != $selected &&
           ( substr($selected,0,1) != "'" || substr($selected,-1) != "'" ) )
        {
          $selected = "'$selected'";
        }

        if ( strpos($table,"###_") === false )
        {
          $table = "###_" . $table;
        }

        $sqlCMD = "SELECT * from $table where id=$selected";
        (($anArr = $db->GetRow($sqlCMD)) === false) and $db->ErrorNo() != 0 and print (__FILE__ . "(" . __LINE__ . "/$sqlCMD): ". $db->ErrorMsg());
    }

//     debug_r('MAIN', $anArr, "[FUNCTION, getMyRefTable] anArr");
    return $anArr;
}
/*
 * function getDescriptionTxt
 *
 *  Description:
 *    Gets an array (=row) of a query and shows the correspondant description
 *    The returned text is calculated in the following order until a non empty value is found
 *    1. Try column $bezName_$language (eg. Description_DE)
 *    2. Try $bezName_UK for the english version
 *    3. Try all supported columns (see variable $suppLangs) in the given order
 *    4. Try $bezName (without language extension)
 *    5. Use the first column returned
 *
 *  Parameter:
 *    $anArr       Array which holds Descriptions (between others)
 *    $bezName     Basename of column to return (default: "Description")
 *
 * Return:
 *    Description found
 *
 * Side Effects:
 *    nothing
 */
function getDescriptionTxt($anArr, $bezName = "Description")
{
    global $language, $suppLangs;

    $txt = "";
    if ( empty($anArr[$bezName . "_" . $language]) )
    {
        if ( empty($anArr[$bezName . "_UK"]) )
        {
            /* If no english description available, look for any other languageto display */
            foreach ($suppLangs as $lang)
            {
                if ( ! empty($anArr[$bezName . "_" . $lang] ) )
                {
                    $txt = $anArr[$bezName . "_" . $lang];
                    break;
                }
            }
            /* If still nothing found, try Description without lang
                and use as last resort the first value of the array
            */
            if ( $txt == "")
            {
                if ( empty($anArr[$bezName]))
                {
                    reset($anArr);
                    $txt = current($anArr);
                }
                else
                {
                    $txt = $anArr[$bezName];
                }
            }
        }
        else
        {
            $txt = $anArr[$bezName . "_UK"];
        }
    }
    else
    {
        $txt = $anArr[$bezName . "_" . $language];
    }

//     echo "DESCR: $txt<BR><PRE>";print_r($anArr);echo "</PRE>";
    return $txt;
}

/*
 * function getMyRefDescription
 *
 *  Description:
 *    Gets the values of a referenced table and shows the correspondant description
 *    The returned text is calculated in the following order until a non empty value is found
 *    1. Try column $bezName_$language (eg. Description_DE)
 *    2. Try $bezName_UK for the english version
 *    3. Try all supported columns (see variable $suppLangs) in the given order
 *    4. Try $bezName (without language extension)
 *    5. Use the first column returned
 *
 *  Parameter:
 *    $db          Link to database
 *    $selected    Value to be selected
 *    $colName     column name structure (See functions resolve_field_...)
 *    $bezName     Basename of column to return (default: "Description")
 *
 * Return:
 *    Description found
 *
 * Side Effects:
 *    nothing
 */
function getMyRefDescription($db, $selected, $colName, $bezName = "Description")
{
    global $language, $suppLangs;

    $anArr = getMyRefTable($db, $selected, $colName['reftable']);
    return getDescriptionTxt($anArr, $bezName);
}


/*
 * function getConfigEntry
 *
 *  Description:
 *    Gets the values of the Configuration table, identified by the name passed as parameter
 *    This function supports also the old-style configuration table, where the name of the
 *    table was Konfiguration instead of Configuration.
 *
 *  Parameter:
 *    $db          Link to database
 *    $selected    Name of configuration entry to return
 *
 * Return:
 *    Value of configuration entry referenced by $selected
 *
 * Side Effects:
 *    Nothing
 */
function getConfigEntry($db, $selected)
{
    global $APerr;

    $tmpFetchMode = $db->setFetchMode(ADODB_FETCH_ASSOC);
    $anArr = $db->GetRow("SELECT * from `###_Configuration` where name='$selected'");
    $db->setFetchMode($tmpFetchMode);

    if ( !isset($anArr["value"]) )
    {
      // Try Configuration without prefix
      $tmpFetchMode = $db->setFetchMode(ADODB_FETCH_ASSOC);
      $anArr = $db->GetRow("SELECT * from /* NO PREFIX */ `Configuration` where name='$selected'");
      $db->setFetchMode($tmpFetchMode);
    }
    if ( !isset($anArr["value"]) )
    {
        $APerr->setFatal("getConfigEntry: Undefined index value for key $selected");
        return NULL;
    }
    else
    {
        return $anArr["value"];
    }
}

/*
 * function lang
 *
 *  Description:
 *    Gets the translation of the passed text to the local language or returns the original text
 *    if now translation is available
 *
 *  Parameter:
 *    $text    Original text to translate
 *
 * Return:
 *    Translated text or original text, if now translation is available
 *
 * Side Effects:
 *    Nothing
 */
function lang($text)
{
    global $lang;

//     debug('ADDRESSES', "LANG: $text = {$lang[$text]}<BR>");
    return isset($lang[$text]) ? $lang[$text] : $text;
}

function icT($text)
{
  $retText= ( CHARACTER_ENCODING != 'ISO-8859-1' ? iconv(CHARACTER_ENCODING, 'ISO-8859-1', $text) : $text);
  debug('ADDRESSES', "[createPDF, icT]: ENCODING: " . CHARACTER_ENCODING. "TEXT: $text, Returns: $retText");
  return $retText;
}

/**
 *
 * This function generates a link to help for parameter $text.
 * The help is defined by section and subsection (also called category and subcategory)
 * This function only generates the openeing part of the <a>-tag. No link text nor the closing </a>-tag is created
 *
 * @see helpAndText()
 * @see http://plugins.learningjquery.com/cluetip/
 * @param string $section
 * @param string $subsection
 * @param string $text
 */
function createHelpLink($section, $subsection, $text)
{
    $link = INDEX_PHP . "?mod=help&head=$text&cat=$section&subcat=$subsection";
    $txt = "<a class=help title='" . lang($text) . "' rel='{$link}&mode=tooltip' href=\"#\" onclick=\"openHelp('$link')\">";

    return $txt;
}

function helpAndText($section, $subsection, $text)
{
    global $language;

//    $t1p = urlencode($text);
	$t1p = $text;

    /* Translate `###_Tablename` to Tablename */
    $section = preg_replace('/(`?###_)?([^`]+)`?/', "$2", $section);

    $txt = createHelpLink($section, $subsection, $t1p);
    //FD20080224
    $txt .= lang($t1p) . "</a>";
#    $txt .= lang($subsection) . "</a>";
    return $txt;

}

function getFirstRecord($db)
{
    return $db->GetOne("SELECT MIN(MemberID) FROM `###_Members`");
}

function getLastRecord($db)
{
    return $db->GetOne("SELECT MAX(MemberID) FROM `###_Members`");
}

function getNextRecord($db,$aktMemberID)
{
  global $APerr;

    if ($aktMemberID == "" )
    {
        return getLastRecord($db);
    }

    $sql = "SELECT MIN(MemberID) FROM `###_Members` WHERE MemberID > $aktMemberID";
    $next = $db->GetOne($sql);
    if ( $next === false )
    {
      $APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
    }
    elseif( $next != "" )
    {
        return $next;
    }
    return (getLastRecord($db));
}

function getPrevRecord($db,$aktMemberID)
{
  global $APerr;

    if ($aktMemberID == "" )
    {
        return getFirstRecord($db);
    }

    $sql  = "SELECT MAX(MemberID) FROM `###_Members` WHERE MemberID < $aktMemberID";
    $prev = $db->GetOne($sql);
    if ( $prev === false )
    {
      $APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
    }
    elseif( $prev != "" )
    {
        return $prev;
    }
    return (getFirstRecord($db));
}

// Convert date format YYYY-MM-DD to DD.MM.YYYY
function myDateToPhPDate($date)
{
    if ( $date != "" && $date != "0000-00-00" )
    {
        $datArr = explode("-", $date);
        return $datArr[2] . "." . $datArr[1] . "." . $datArr[0];
    }
    else
    {
        return "";
    }
}

// Convert date format DD.MM.YYYY to YYYY-MM-DD
function phpDateToMyDate($date)
{
    global $db;

    if ( $date != "" && $date != "00.00.0000" )
    {
        $datArr = explode(".", $date);
        if ( count($datArr) > 1)
        {
            return $db->DBDate(adodb_mktime(0,0,0,$datArr[1],$datArr[0],$datArr[2]));
        }
        else
        {
            return $db->DBDate($date);
        }

    }
    else
    {
        return "";
    }
}

/**
 resolve field at index idx.

 returns an associative array with
    ["raw"]  = raw field name

    ["type"] = type of field
                If the raw name of the field ends with _xxx, this ending will be used to determine the type of the field
                else, the index is empty
                _ref : (myref) Reference to another table
                _xref : (myref) Reference to another table (via array $xreftable)
                _jn
                _yn  : (yesno) Yes/No field
                _pw  : (password) Password field
                _link: (mylink) Reference to an external file
                _ml  : (multiline) Field my contain more than on line
                _ro  : (readonly) Field should not be edited

    ["pretty"] = human readable field name
                If language entry exists, this will be used,
                else first letter upper case and _ replace by whitespace and the type section removed

    ["reftable"] = Name of referenced table, if type = myref
                    Only the string between the last _ and the end of the string is referenced
                    e.g. Privat_Country_ref => reftable = Country
                         Salutation_ref     => reftable = Salutation
*/
function resolveField($name)
{
    global $tablang, $xreftable;

    // Split column name: [(Tablename).](Columnname)[_(Extension)][ AS alias]
    //      $0 = hole fn (=$ft->name)
    //      $1 = not used
    //      $2 = Tablename
    //      $3 = Columnname with quotes, if any
    //      $4 = Columnname without extension and quotes
    //      $5 = _Extension
    //      $6 = Extension
    //      $7 = alias part (with 'as')
    //      $8 = alias (without 'as')
    //
    //      Tablenames may be separated by . or % from Columnname
    //
    //      After the Extension (or columnname, if no extension) there may be a [] which is ignored
    //      (This is usefull to pass arrays in lists, which is also used for a column name
    //
    $res=preg_match("/^(([^.%]*)[%.])?(`?(.*?)(_(ref|yn|jn|pw|link|ml|ro))?`?)\[?\]?(\s+[Aa][Ss]\s+(.*))?$/", $name,$fieldArr);

    $fieldname["raw"] = $fieldArr[0];
    $fieldname['table'] = trim($fieldArr[2],'` \t\n\r\0\x0B');
//    $fieldname['column'] = trim(empty($fieldArr[5]) ? $fieldArr[3] : $fieldArr[3] . $fieldArr[5],"` \t\n\r\0\x0B");
    $fieldname['column'] = trim($fieldArr[3],"` \t\n\r\0\x0B");
//    echo "<PRE>";print_r($fieldArr); print_r($fieldname); echo "</PRE>";
//     echo "FN: $fn";
    if ( empty($fieldArr[6]) )
    {
        $fieldArr[6] = "";
    }

    if ( $fieldArr[6] == "ref")
    {
        $fieldname["type"] = "myref";
        if ( ($rpos = strrpos($fieldArr[4],'_') ) !== false )
        {
            $fieldname["reftable"] = substr($fieldArr[4], $rpos+1);
        }
        else
        {
            $fieldname["reftable"] = $fieldArr[4];
        }
    }
    elseif ( $fieldArr[6] == "xref")
    {
        $fieldname["type"] = "myref";
        $fieldname["reftable"] = $xreftable[$fieldArr[3]];
    }
    elseif ( $fieldArr[6] == "jn" || $fieldArr[6] == "yn")
    {
        $fieldname["type"] = "yesno";
    }
    elseif ( $fieldArr[6] == "pw")
    {
        $fieldname["type"] = "password";
    }
    elseif ( $fieldArr[6] == "link")
    {
        $fieldname["type"] = "mylink";
    }
    elseif ( $fieldArr[6] == "ml")
    {
        $fieldname["type"] = "multiline";
    }
    elseif ( $fieldArr[6] == "ro")
    {
        $fieldname["type"] = "readonly";
    }
    else
    {
        unset($fieldname["type"]);
    }

    if ( isset($tablang[$fieldname['column']]) )
    {
        $fieldname["pretty"] = $tablang[$fieldname['column']];
    }
    else
    {
        $fieldname["pretty"] = trim(ucwords(strtr($fieldArr[4], "_", " ")),"` \t\n\r\0\x0B");
    }
//         echo "RESOLVE: <PRE>"; print_r($fieldArr); print_r($fieldname); print("</PRE><BR>");
    return $fieldname;
}
/**
 resolve field at index idx.

 returns an associative array with
    ["raw"]  = raw field name

    ["type"] = type of field
                If the raw name of the field ends with _xxx, this ending will be used to determine the type of the field
                else, the type returned by the database will be used
                _ref : (myref) Reference to another table
                _xref : (myref) Reference to another table (via array $xreftable)
                _jn
                _yn  : (yesno) Yes/No field
                _pw  : (password) Password field
                _link: (mylink) Reference to an external file
                _ml  : (multiline) Field my contain more than on line
                _ro  : (readonly) Field should not be edited

    ["pretty"] = human readable field name
                If language entry exists, this will be used,
                else first letter upper case and _ replace by whitespace and the type section removed

    ["reftable"] = Name of referenced table, if type = myref
                    Only the string between the last _ and the end of the string is referenced
                    e.g. Privat_Country_ref => reftable = Country
                         Salutation_ref     => reftable = Salutation
*/
function resolveFieldIndex($rs, $idx)
{
    global $tablang, $xreftable;

    //var_dump($tablang);

    $ft = $rs->FetchField($idx);

    $fieldname = resolveField($ft->name);
    if ( empty($fieldname["type"] ) )
    {
        $fieldname["type"] = $ft->type;
    }
    return $fieldname;
}


/**
 resolve field with name $fn.

 returns an associative array with
    ["raw"]  = raw field name
    ["index"] = index of field in query

    ["type"] = type of field
                If the raw name of the field ends with _xxx, this ending will be used to determine the type of the field
                else, the type returned by the database will be used
                _ref : (myref) Reference to another table
                _xref : (myref) Reference to another table (via array $xreftable)
                _jn
                _yn  : (yesno) Yes/No field
                _pw  : (password) Password field
                _link: (mylink) Reference to an external file
                _ml  : (multiline) Field my contain more than on line

    ["pretty"] = human readable field name
                If language entry exists, this will be used,
                else first letter upper case and _ replace by whitespace and the type section removed

    ["reftable"] = Name of referenced table, if type = myref
                    Only the string between the last _ and the end of the string is referenced
                    e.g. Privat_Country_ref => reftable = Country
                         Salutation_ref     => reftable = Salutation
*/
function resolveFieldName($rs, $fn)
{
    $fieldname = resolveField($fn);
    if ( empty($fieldname["type"]) )
    {
        $numFields = $rs->FieldCount();
        for ($i=0 ; $i < $numFields; $i++ )
        {
            $ft = $rs->FetchField($i);
    //          print("FN: [$fn]<PRE>");print_r($ft);print("</PRE>");
            if ( $ft->name == $fn )
            {
                break;
            }
        }

        if ($i == $numFields)
        {
            echo lang('Invalid field name') . " $fn<BR>";
            return NULL;
        }

        $fieldname["type"] = $ft->type;
    }
//    echo "RESOLVE: <PRE>"; print_r($fieldname); print("</PRE><BR>");
    return $fieldname;
}

/**
 * function getOptionArray
 *
 *  Description:
 *    Genarate an option list from all values of table $table,
 *    The table must have a field called id.
 *	  The array has the form:
 *		array[id] = 'Description';
 *
 *    The description is calculated in that order until a non empty value is found
 *    1. Try column $bezName_$language (eg. Description_DE)
 *    2. Try $bezName_UK for the english version
 *    3. Try all supported columns (see variable $suppLangs) in the given order
 *    4. Try $bezName (without language extension)
 *    5. Use the first column returned
 *    If a column Show_yn is found in the table, only those rows who have Show_yn = 1
 *    are added to the array. If this column doesn't exist, all rows are added
 *    If $withEmpty is true, an empty entry is added to the list
 *
 *  Parameter:
 *    $table       Table to generate array list from
 *    $colName     column name structure (See functions resolve_field_...)
 *    $where	   additional WHERE clause for the SELECT statement
 *    $withEmpty   Value to be selected
 *    $bezName     Name of column to use in SELECT statement (default: "Description")
    Genarate an option list from all values of table $table,
    The table must have a field called id.
    preselect entry which id equals to $actType,
        $actType may be a scalar or an array.
        In this case all elements matching an entry in the array are selected.
    prepend empty entry if $withEmpty = true
    If a column Show_yn exists, only rows with Show_yn == 1 are shown,
    if no column Show_yn exists, ALL rows are shown
 *
 * Return:
 *    Array of descriptions (Index is id of row)
 *
 * Side Effects:
 *    none
 */
function getOptionArray($table, $where = NULL, $withEmpty = false, $bezName = "Description")
{

  global $db,$APerr,$ADODB_FETCH_MODE;

  $db->SetFetchMode(ADODB_FETCH_ASSOC);

// 	echo "getOptionArray($table, $where, $withEmpty, $bezName)<BR>";

  $resArr = array();

  if ( strpos($table,"###_") === false )
  {
    $table = "###_" . $table;
  }

	$sql = "select * from $table " .
						(empty($where)? '' : "WHERE $where ") . "order by id";
//     debug('MAIN', "[getOptionArray] SQL: $sql");
	$res = $db->Execute($sql);

	if ( $res === false )
	{
    debug('MAIN', "FATAL: SQL $sql");
    debug_backtr('MAIN');
		$APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
		return;
	}

    if ( $withEmpty == true )
    {
      $resArr[''] = '';
    }
    while ( $mgArtArr = $res->FetchRow() )
    {
        //echo "SHOW: $mgArtArr[Show_yn]: " . !isset($mgArtArr["Show_yn"]) . "<BR>\n";
//         debug_r('MAIN', $mgArtArr, "[getOptionArray] mgArtArr");
        if ( !isset($mgArtArr["Show_yn"]) || $mgArtArr["Show_yn"] == 1 )
        {
			$resArr[$mgArtArr["id"]] = getDescriptionTxt($mgArtArr, $bezName);
        }
    }

//     debug_r('MAIN', $resArr, "[getOptionArray] resArr");
	return $resArr;
}

/*
 * function getDefaultSelection
 *
 *  Description:
 *    Genarate an array of entries of table $table which should be selected by
 *    default.
 *    The table must have a field called id.
 *    The array may have a field SelectByDefault_yn. If this column exists,
 *    only those rows, which have set it to 1 are returned.
 *
 *    If a column Show_yn is found in the table, only those rows who have Show_yn = 1
 *    are checked. If this column doesn't exist, all rows are checked
 *
 *  Parameter:
 *    $table         Table to generate array list from
 *    $defSelectAll  True: select all entries if no column SelectByDefault_yn is given
 *                   False: do not select any entry
 *
 * Return:
 *    Array of selected values
 *
 * Side Effects:
 *    none
 */
function getDefaultSelection($table, $defSelectAll = true)
{

    global $db,$APerr;

    $resArr = array();

    if ( strpos($table,"###_") === false )
    {
      $table = "###_" . $table;
    }

    $sql = "select * from $table order by id";
    debug('MAIN', "[getDefaultSelection] SQL: $sql");

    $tmpFetchMode = $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $res = $db->Execute($sql);
    $db->SetFetchMode($tmpFetchMode);

    if ( $res === false )
    {
        debug('MAIN', "FATAL: SQL $sql");
        debug_backtr('MAIN');
        $APerr->setFatal(__FILE__,__LINE__,$db->errormsg(),"SQL: $sql");
        return;
    }

    while ( $mgArtArr = $res->FetchRow() )
    {
//         debug_r('MAIN', $mgArtArr, "[getOptionArray] mgArtArr");
        if ( (!isset($mgArtArr["Show_yn"]) || $mgArtArr["Show_yn"] == 1) &&
             ((!isset($mgArtArr["SelectByDefault_yn"]) && $defSelectAll) ||
               (isset($mgArtArr["SelectByDefault_yn"]) && $mgArtArr["SelectByDefault_yn"] == 1)) )
        {
            $resArr[] = $mgArtArr['id'];
        }
    }

    debug_r('MAIN', $resArr, "[getDefaultSelection] resArr");
    return $resArr;
}

/*****************************************************************
** generiereSelectCMD
******************************************************************/
    function addStrings($x)
    {
        //echo "ADD: $x<BR>";
        return "'$x'";
    }

Function generateSelectCMD($rs)
{
    $selectCMD = "";

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
            if ( isset($_REQUEST[$tmp]) )
            {
                $paramArr[$tmpArr['table']][$tmpArr['column']]['select'] = getGlobVar($key);
                $paramArr[$tmpArr['table']][$tmpArr['column']]['value'] = getGlobVar($tmp);
//                 debug('MAIN', "[generateSelectCMD]: PARAMARR: NAME=$key,SELECT=".$paramArr[$tmpArr['table']][$tmpArr['column']]['select'].",TMP=$tmp,VAL=".$paramArr[$tmpArr['table']][$tmpArr['column']]['value']);
            }
                // Check for date parameters of class formgeneration
                // Form: p_{Variablename}_[day|month|year]
            elseif ( !empty($_REQUEST['p_' . $tmp . '_day']) )
            {
                $paramArr[$tmpArr['table']][$tmpArr['column']]['select'] = getGlobVar($key);
                $paramArr[$tmpArr['table']][$tmpArr['column']]['value'] = getGlobVar('p_' . $tmp . '_day') .
                                                                          '.' .
                                                                          getGlobVar('p_' . $tmp . '_month') .
                                                                          '.' .
                                                                          getGlobVar('p_' . $tmp . '_year');
                debug('MAIN', "[generateSelectCMD]: FG-DATE: NAME=$key,SELECT=".$paramArr[$tmpArr['table']][$tmpArr['column']]['select'].",TMP=$tmp,VAL=".$paramArr[$tmpArr['table']][$tmpArr['column']]['value']);
            }
        }
    }

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
            //echo "NAME: $name, VAL: "; print_r($val); echo "<BR>";
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
                    $selectCMD[] = "$name NOT IN (" . join(",", array_map("addStrings",$val)) . ")";
                    break;

                default:
                    $selectCMD[] = "$name IN (" . join(",", $val) . ")";
                    break;
            }
        }
        else
        {

            // echo "Suche: $name: $val, ($select)<BR>";
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
                        $tmpVals = explode(";", $val);
                        if ( count($tmpVals) != 2 )
                          {
                            echo "INVALID PARAMETER for $name: $val<BR>";
                            $selectCMD[] = "$name > " . trim(phpDateToMyDate($tmpVals[0])) . " ";
                          }
                        else
                          {
                            $selectCMD[] = "($name >= " . trim(phpDateToMyDate($tmpVals[0])) . " AND $name <= " . trim(phpDateToMyDate($tmpVals[1])) . ") ";
                          }
		                break;

                    case "BETint":
                        $tmpVals = explode(";", $val);
                        if ( count($tmpVals) != 2 )
                          {
                            echo "INVALID PARAMETER for $name: $val<BR>";
                            $selectCMD[] = "$name > " . trim($tmpVals[0]) . " ";
                          }
                        else
                          {
                            $selectCMD[] = "($name >= " . trim($tmpVals[0]) . " AND $name <= " . trim($tmpVals[1]) . ") ";
                          }
		                break;

                    case "INSelection":
                        //echo "1, MType: $mtype, VAL: !$val!<BR>\n";
						$selectCMD[] = "$name IN (". join(",", array_map("addStrings",preg_split ("/[\s,]+/",$val,-1, PREG_SPLIT_NO_EMPTY))) .") ";
                        break;

                    case "NOTINSelection":
						$selectCMD[] = "$name NOT IN (". join(",", array_map("addStrings",preg_split ("/[\s,]+/",$val,-1, PREG_SPLIT_NO_EMPTY))) .") ";
                        break;

                case "IsEmpty":
                    $selectCMD[] = "$name IS NULL";
                    break;

                case "IsNotEmpty":
                    $selectCMD[] = "$name IS NOT NULL";
                    break;

                  default:
                        printf("<BR><B>" . lang("ERROR: Invalid QueryType=%s, name=%s, val=%s") . "</B><BR>", $select, $name, $val);

            		    $selectCMD[] = $name . " like  '%" . $val . "%'";
    		            break;
                }
                }
            }
        }
    }
    //  echo "selectCMD: $selectCMD<BR>";
    return (!empty($selectCMD) ? join(" AND ", $selectCMD) : '');
}


function createThumbnail($createfn, $thumbnailfile)
{
    {
//        echo "THUMB: $createfn, $thumbnailfile<BR>";
        if (eregi("\.(jpg|jpeg)$",$createfn))
            $im = imagecreatefromjpeg($createfn);
        else if (eregi("\.png$",$createfn))
            $im = imagecreatefrompng($createfn);
        else if (eregi("\.gif$",$createfn))
            $im = imagecreatefromgif($createfn);

        if ($im != "")
        {
            $newh=MAXTHUMBNAILHEIGHT;
            $neww=$newh/imagesy($im) * imagesx($im);
            if ($neww > imagesx($im))
            {
                $neww=imagesx($im);
                $newh=imagesy($im);
            }
            if ($neww > MAXTHUMBNAILWIDTH)
            {
                $neww=MAXTHUMBNAILWIDTH;
                $newh=$neww/imagesx($im) * imagesy($im);
            }

            $im2=ImageCreateTrueColor($neww-1,$newh-1);
            ImageCopyResized($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));

            if (eregi("\.(jpg|jpeg)$",$createfn))
                imagejpeg($im2,$thumbnailfile,50);
            else if (eregi("\.png$",$createfn))
                imagepng($im2,$thumbnailfile);
            else if (eregi("\.gif$",$createfn))
                imagegif($im2,$thumbnailfile);
            ImageDestroy($im);
            ImageDestroy($im2);
        }
    }
}

function copyUploadFile($fieldName, $destNamePrefix)
{
	global $APerr;

//     global $HTTP_POST_FILES;

    $size_limit = MAX_SIZE_LIMIT; // set size limit in bytes
    $allowed_types = explode(",", ALLOWED_UPLOAD_TYPES);
      //array("image/jpeg","image/pjpeg","image/png","image/gif"); //etc.

    // phpinfo();
//     echo "MYLINK: $fieldName<BR>";
    $file = $_FILES[$fieldName]['name'];
    $type = $_FILES[$fieldName]['type'];
    $size = $_FILES[$fieldName]['size'];
    $temp = $_FILES[$fieldName]['tmp_name'];

    if ($file)
    {
        if ($size < $size_limit)
        {
            if (in_array($type,$allowed_types))
            {
                $destFileName = "${destNamePrefix}_${file}";
                $destFilePath = DESTDIR . $destFileName;
                $destThumbPath = DESTDIR . "/small/" . $destFileName;

//                echo "Copying $temp to $destFilePath<BR>";
                if ( !copy($temp, $destFilePath) )
                {
				    $errors= error_get_last();
                	$APerr->setFatal(__FILE__,__LINE__,sprintf(lang("Cannot move %s to %s"),$temp, $destFilePath) . "<BR>" . $errors['message']);
                }
//                echo "Creating thumbnail preview<BR>";
                list ($w, $h, $r) = getimagesize($destFilePath);
                if ( $w === false )
                {
				    $errors= error_get_last();
                	$APerr->setFatal(__FILE__,__LINE__,sprintf(lang("Cannot get imagesize of %s"), $destFilePath) . "<BR>" . $errors['message']);
                }
                //                echo "WIDTH: $w, HEIGHT: $h<BR>";
                createThumbnail($destFilePath, $destThumbPath);
                return $destFileName;
            }
            else
            {
                printf(lang("Sorry, files of type <tt>%s</tt> are not permitted"), $type);
                return "";
            }
        }
        else
        {
            printf(lang("Sorry, your file exceeds the size limit of %d bytes"), $size_limit);
            return "";
        }
    }
    else
    {
        echo lang("Sorry, no filename given!") . "<BR>";
        return "";
    }
}

function getNextSequenceNumber($table,$field)
{
    global $db;

// 	echo "getNextSequenceNumber: TABLE: $table, FIELD: $field<BR>";
    $rs1 = $db->Execute("SHOW TABLE STATUS LIKE '$table'");

    if ( $rs1->RecordCount() != 1 )
    {
        return 0;
    }

    $nextval   = $rs1->fields['Auto_increment'];

    return $nextval;
}

/*
function generateFieldList($feldArr)
{
    if ( !is_array($feldArr) )
    {
        return $feldArr;
    }

    $fieldList = "";
    reset($feldArr);
    while (list ($key, $val) = each ($feldArr))
    {
        //echo "$key => $val<br>";
        if ( $val == "on")
        {
            $fieldList .= ($fieldList == "") ? $key : ",$key";
        }
    }
    //    echo "Feldliste: $fieldList<BR>";
    return $fieldList;
}
*/


function passParameterAsSession($params, $excludeKeys = array())
{
    reset ($params);

    $intExclKeys = array('mod', 'view','doit','Action','State');
    while (list ($key, $val) = each ($params))
    {
        if ( !in_array($key,$intExclKeys) &&
             (!is_array($excludeKeys) || !in_array($key, $excludeKeys)) )
        {
          $_SESSION[$key] = $val;
        }
    }
}

/**
* Registers global variables
*
* This function takes global namespace $HTTP_*_VARS variables from input and if they exist,
* register them as a global variable so that scripts can use them. The first argument
* signifies where to pull the variable names from, and should be one of GET, POST, COOKIE, ENV, or SERVER.
*
*/
    function checkNumber(&$var, $index, $varName)
    {
        global $APerr;

        if ( $var == NULL || is_numeric($var) )
        {
            return true;
        }
        else
        {
            $APerr->setFatal("checkNumber: Invalid INTEGER for Variable %s (%s)", $varName, $var);
            $var = NULL;
            return false;
        }
    }


    function checkText(&$var, $index, $varName)
    {
        global $APerr;

        // &\w; accepts special chars like &uuml; or &agrave;
        if ( $var == NULL || preg_match("/^(\w|&\w*;)*$/", $var) )
        {
            return true;
        }
        else
        {
            $APerr->setFatal("checkText: Invalid TEXT for Variable %s (%s)", $varName, $var);
            $var = NULL;
            return false;
        }
    }

    function checkTextWS(&$var, $index, $varName)
    {
        global $APerr;

        // &\w; accepts special chars like &uuml; or &agrave;
        if ( $var == NULL || preg_match("/^([\w\s]|&\w*;)*$/", $var) )
        {
            return true;
        }
        else
        {
            $APerr->setFatal("checkTextWS: Invalid TEXT for Variable %s (%s)", $varName, $var);
            $var = NULL;
            return false;
        }
    }

    function checkRegExp(&$var, $index, $parmArr)
    {
        global $APerr;

        $regExp = $parmArr["RegExp"];
        // &\w; accepts special chars like &uuml; or &agrave;
        if ( $var == NULL || preg_match("/$regExp/", $var) )
        {
            return true;
        }
        else
        {
            $APerr->setFatal("checkRegExp: Invalid REGEXP ($regExp) for Variable %s (%s)", $parmArr['VarName'], $var);
            $var = NULL;
            return false;
        }
    }

function getGlobVar($varName, $check = "", $methods = "GPCSEs", $overwrite = false)
{
    global $APerr;

    $var = NULL;

    if ( $overwrite === false && isset(${$varName}) || isset($GLOBALS[$varName]) )
    {
        $var = isset(${$varName}) ? ${$varName} : $GLOBALS[$varName];
    }

    $found = false;
    for ( $methodIdx = 0 ; !$found && $methodIdx < strlen($methods); $methodIdx++ )
    {
//         debug("MAIN","Method[$methodIdx]: " . substr($methods,$methodIdx,1));
        switch(substr($methods,$methodIdx,1))
        {
            case 'C':
                //echo "Method: C<BR>";
                if ( isset($_COOKIE[$varName]) )
                {
                    $var = $_COOKIE[$varName];
                    $found = true;
                }
                break;

            case 'E':
                //echo "Method: E<BR>";
                if ( isset($_ENV[$varName]) )
                {
                    $var = $_ENV[$varName];
                    $found = true;
                }
                break;

            case 'G':
                //echo "Method: G<BR>";
                if ( isset($_GET[$varName]) )
                {
                    $var = $_GET[$varName];
                    $found = true;
                }
                break;

            case 'P':
                //echo "Method: P<BR>";
                if ( isset($_POST[$varName]) )
                {
                    $var = $_POST[$varName];
                    $found = true;
                    //echo "FOUND: $varName<BR><PRE>"; var_dump($var); echo "</PRE>";
                }
                break;

            case 'S':
                //echo "Method: S<BR>";
                if ( isset($_SESSION[$varName]) )
                {
                    $var = $_SESSION[$varName];
                    $found = true;
                }
                break;

            case 's':
                //echo "Method: s<BR>";
                if ( isset($_SERVER[$varName]) )
                {
                    $var = $_SERVER[$varName];
                    $found = true;
                }
                break;

        }
    }
    //echo "varName: $varName, CHECK: $check, VAR: $var<BR>";
    if ( !empty($check) )
    {
        switch($check)
        {
            case '::number::':
                if ( is_array($var) )
                {
                    array_walk( $var, "checkNumber", $varName);
                }
                else
                {
                    checkNumber($var, 0, $varName);
                }
                break;

            case '::numberlist::':
                if ( is_array($var) )
                {
                    array_walk( $var, "checkRegExp",
                                array("VarName" => $varName, "RegExp" => "[0-9,]"));
                }
                else
                {
                  checkRegExp($var, 0 , array("VarName" => $varName, "RegExp" => "[0-9,]"));
/*                  if ( $var != NULL && !ereg("[0-9,]", $var) )
                  {
                      $APerr->setFatal("getGlobVar: Invalid Variable %s (Value: %s, Check: [0-9,]"), $varName, $var);
                      $var = NULL;
                  }
*/
                }
                break;

            case '::text::':
                if ( is_array($var) )
                {
                    array_walk( $var, "checkText", $varName);
                }
                else
                {
                    checkText($var, 0, $varName);
                }
                break;

            case '::textws::':
                if ( is_array($var) )
                {
                    array_walk( $var, "checkTextWS", $varName);
                }
                else
                {
                    checkTextWS($var, 0, $varName);
                }
                break;

            case '::date::':
                if ( $var != NULL )
                {
                    if ( ereg ("([0-9]{1,2}).([0-9]{1,2}).([0-9]{2,4})", $var, $regs))
                    {
                        if ( ! checkdate($regs[2], $regs[1], $regs[3]) )
                        {
                            $APerr->setFatal("getGlobVar: DATE out of range for Variable %s (Value: %s, Check: [%s,%s,%s])", $varName, $var,$regs[1], $regs[2], $regs[3]);
                            $var = NULL;
                        }
                    }
                    else
                    {
                        $APerr->setFatal("getGlobVar: DATE out of range for Variable %s (Value: %s)", $varName, $var);
                        $var = NULL;
                    }
                }
                break;

            default:
                if ( is_array($var) )
                {
                    array_walk( $var, "checkRegExp",
                                array("VarName" => $varName, "RegExp" => "$check"));
                }
                elseif ( $var != NULL && !preg_match("~$check~i", $var) /*!ereg($check, $var)*/ )
                {
                    $APerr->setFatal(sprintf("getGlobVar: Invalid Variable %s (Value: %s, Check: %s)", $varName, $var, $check));
                    $var = NULL;
                }
                break;
        }
    }
    if ( get_magic_quotes_gpc() && $var != NULL)
    {
    //    $var = stripslashes($var);
    }
    return $var;
}

function checkGlobNameExists($name)
{
    foreach($_GET as $key => $value)
    {
        //echo "Search $name in $key<BR>";
        if ( preg_match("/$name/", $key ) )
        {
            //echo "FOUND $name in $key<BR>";
            return 1;
        }
    }
    foreach($_POST as $key => $value)
    {
        if ( preg_match("/$name/", $key ) )
        {
            //echo "FOUND $name in $key<BR>";
            return 1;
        }
    }
    return 0;
}

function checkDBVersion($db)
{
   global $version;

   $updateNeeded = false;
   while ( ($dbVers = getConfigEntry($db, "Clubdata_Version_DB")) != $version )
   {
        print "UserType: " . getUserType(ADMINISTRATOR);
        if ( ! getUserType(ADMINISTRATOR) )
        {
            print "<H1>" . lang("The database has not the correct version, please call your administrator!!"). "</H1>";
            exit;
        }
        $updateNeeded = true;

        printf (lang("Database has incorrect version %s (%s required)") . "<BR>\n", $dbVers, $version);
        $updateDB = SCRIPTROOT . "/update/$dbVers/$dbVers.php";
        if ( file_exists($updateDB)  )
        {
            printf (lang("Updating Database using %s") . "<BR>\n", $updateDB);
            include_once($updateDB);
            $moduleName = "Update_" . strtr($dbVers,".","_");
            $updateObj = new $moduleName($db);
        }
        else
        {
            printf(lang("Don't know how to update database, cannot find %s") . "<BR>\n",$updateDB);
            exit;
        }
        print "<P></P>";
    }
    if ( $updateNeeded === true )
        {
            printf(lang("Update finished... Please reload using the reload button of your browser"));
            exit;
        }
}

function getStyleFileLink($filename, $default)
{
   global $db;

   $styleDir = SCRIPTROOT . "/style";
   $linkDir = LINKROOT . "/style";
   $style = getConfigEntry($db, "Style");

   //echo "$styleDir/$style/$filename : " . file_exists("$styleDir/$style/$filename") . "<BR>";
   if ( file_exists(SCRIPTROOT . "/style/$style/$filename") )
   {
      return "$linkDir/$style/$filename";
   }
   else
   {
      return "$linkDir/$default";
   }
}

function array_change_key($input, $callback){
    if(!is_array($input))return FALSE;
    $product = array();
    foreach($input as $key => $value){
        $key2 = call_user_func($callback, $key);
        $product[$key2] = $value;
    }
    return $product;
}/* endfunction array_change_key_case */

function logEntry($task, $parameter)
{
    global $db;

    $user = getClubUserInfo("Login");
    $date = $db->DBTimeStamp(time());
    $host = $_SERVER["REMOTE_ADDR"];
    $parameter = $db->qstr($parameter);
    $sqlCMD = "INSERT INTO `###_Log` (user, date, host, task, parameter) VALUES ('$user', $date, '$host', '$task', $parameter)";
    $db->Execute($sqlCMD) or print (__FILE__ . "(" . __LINE__ . "/$sqlCMD): ". $db->ErrorMsg());
}

if (!function_exists("stripos")) {
  function stripos($str,$needle,$offset=0)
  {
     return strpos(strtolower($str),strtolower($needle),$offset);
  }
}

function debug_r($class, $var, $title='')
{
    if ( DEBUG != 0 && strpos(DEBUGCLASSES, $class) !== false )
    {
        ob_start();
        print_r($var);
        $txt = ob_get_contents();
        ob_end_clean();

        if ( (DEBUG & 1) == 1)
        {
            echo "\n<BR>VARIABLE: $title<BR>\n<PRE>";
            echo $txt;
            echo "</PRE>\n";
        }

        if ( (DEBUG & 2) == 2)
        {
            $handle = fopen(DEBUGFILE, "a");
            fwrite($handle,"\nVARIABLE: $title\n");
            fwrite($handle, $txt . "\n");
            fclose($handle);
        }
    }
}

function debug()
{
  if ( DEBUG != 0 )
  {
    $args = func_get_args();
    if ( count($args) > 1 )
    {
        $class = array_shift($args);
//         echo "DEBUGCLASSES " . DEBUGCLASSES . " CLASS: $class<BR>";
      if ( strpos(DEBUGCLASSES, $class) !== false )
      {

        if ( count($args) > 1 )
        {
            $txt = "DEB: " . call_user_func_array('sprintf', $args) . "<BR>";
        }
        else
        {
            $txt = "DEB0: $args[0]<BR>";
        }

        if ( (DEBUG & 1) == 1)
        {
            print $txt;
        }

        if ( (DEBUG & 2) == 2)
        {
          $handle = fopen(DEBUGFILE, "a");
          if ( ! $handle )
          {
            echo "Cannot open " . DEBUGFILE;
            exit;
          }
          fwrite($handle, $txt . "\n");
          fclose($handle);
        }
      }
    }
  }
}

function debug_backtr($class, $levels=9999,$skippy=0)
{
	if (DEBUG == 0 || strpos(DEBUGCLASSES, $class) === false
        || !function_exists('debug_backtrace')) return '';

	$fmt =  "%% line %4d, file: %s (%s)";

	$MAXSTRLEN = 128;

	$traceArr = debug_backtrace();
	array_shift($traceArr);
	$tabs = sizeof($traceArr)-2;

    $s = '';
	foreach ($traceArr as $arr) {
		if ($skippy) {$skippy -= 1; continue;}
		$levels -= 1;
		if ($levels < 0) break;

		$args = array();
		for ($i=0; $i < $tabs; $i++) $s .= "\t";
		$tabs -= 1;
		if (isset($arr['class'])) $s .= $arr['class'].'.';
		if (isset($arr['args']))
		 foreach($arr['args'] as $v) {
			if (is_null($v)) $args[] = 'null';
			else if (is_array($v)) $args[] = 'Array['.sizeof($v).']';
			else if (is_object($v)) $args[] = 'Object:'.get_class($v);
			else if (is_bool($v)) $args[] = $v ? 'true' : 'false';
			else {
				$v = (string) @$v;
				$str = htmlspecialchars(substr($v,0,$MAXSTRLEN));
				if (strlen($v) > $MAXSTRLEN) $str .= '...';
				$args[] = $str;
			}
		}
		$s .= $arr['function'].'('.implode(', ',$args).')';
		$s .= @sprintf($fmt, $arr['line'],$arr['file'],basename($arr['file']));
		$s .= "\n";
	}

    if ( (DEBUG & 1) == 1)
    {
        echo "\n<BR>BACKTRACE<BR>\n<PRE>";
        echo $s;
        echo "</PRE>\n";
    }

    if ( (DEBUG & 2) == 2)
    {
        $handle = fopen(DEBUGFILE, "a");
        fwrite($handle,"\nBACKTRACE\n");
        fwrite($handle, $s . "\n");
        fclose($handle);
    }
	return $s;
}

function formsdebug($text)
{
    debug("FORMS", "[FORMSDEBUG:] " . $text);
}
?>
