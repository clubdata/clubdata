<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Clubdata2 Installation</title>

    <style type="text/css">
    h1 {
    	text-align: center;
    	background-color: #A5CBF7;
    }

    .hint {
    	border: 3px ridge #A5CBF7;
    	padding: 6px;
    	margin-bottom: 6px;
    	background-color: #A5CBF7;
    	/*      color: #ff0000;
          font-weight: bolder;*/
    }

    .warning {
    	border: 3px ridge #E5E500;
    	padding: 3px;
    	background-color: #E5E500;
    }

    .error {
    	border: 3px ridge #F50000;
    	padding: 3px;
    	background-color: #F50000;
    }

    .entrycol {
    	width: 100%;
    }

    .entrydesc {
    	padding-right: 1em;
    }

    .entryinput {
    	width: 100%
    }

    div.submitbar {
    	text-align: center;
    	margin: auto;
    }

    div.submitbar input {
    	margin: 0 1em;
    	font-weight: bolder;
    }

    input.save {
    	color: green;
    }

    input.abort {
    	color: red;
    }
    </style>
</head>
<body>
<?php
/**
 * @package Installation
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
/*
 install.php: Installation of Clubdata
 Copyright (C) 2009 Franz Domes

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
define('INSTDEBUG', 0);
define('INSTDEBUGFILE', "../tmp/clubdata.install.log");

require '../vendor/autoload.php';

function debug() {
    if (INSTDEBUG != 0) {
        $args = func_get_args();

        if (count($args) > 1) {
            $class = array_shift($args);

            if (count($args) > 1) {
                $txt = "DEB: " . call_user_func_array('sprintf', $args) . "<br>";
            } else {
                $txt = "DEB0: {$args[0]}<br>";
            }

            $handle = fopen(INSTDEBUGFILE, "a");

            if (!$handle) {
                echo "Cannot open " . INSTDEBUGFILE;
                exit;
            }

            fwrite($handle, $txt . "\n");
            fclose($handle);
        }
    }
}

/**
 * WINDOWS COMPATIBILITY FUNCTIONS
 *
 * The following function are needed to support Installation routines with windows
 */

/**
 *
 * Enter description here ...
 * @param unknown_type $path
 */
function is__writable($path) {
//will work in despite of Windows ACLs bug
//NOTE: use a trailing slash for folders!!!
//see http://bugs.php.net/bug.php?id=27609
//see http://bugs.php.net/bug.php?id=30931

    if ($path{strlen($path)-1}=='/') { // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    } elseif (is_dir($path)) {
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    }

    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');

    if ($f===false) {
        return false;
    }

    fclose($f);

    if (!$rm) {
        unlink($path);
    }

    return true;
}

// fixes windows paths...
// (windows accepts forward slashes and backwards slashes, so why does PHP use backwards?
function fix_path($path) {
    return str_replace('\\', '/', $path);
}

function _realpath($path) {
    return fix_path(realpath($path));
}

function changeConfigValue(&$confFileArr, $searchPattern, $newPattern) {
    reset($confFileArr);

    foreach ($confFileArr as $line_num => &$line) {
        $line = preg_replace("/" . $searchPattern . ".*$/", $newPattern, $line);
    }
}

function writeFile($filename, &$confFileArr) {
    // Sichergehen, dass die Datei existiert und beschreibbar ist
    if (is__writable($filename)) {
        // Wir öffnen $filename im "Anhänge"-Modus.
        // Der Dateizeiger befindet sich am Ende der Datei, und
        // dort wird $somecontent später mit fwrite() geschrieben.
        if (!$handle = fopen($filename, "w")) {
            print "Cannot open $filename";
            exit;
        }

        // Schreibe $confFileArr in die geöffnete Datei.
        if (!fwrite($handle, join('', $confFileArr))) {
            print "Cannot write to $filename";
            exit;
        }

        fclose($handle);
    } else {
        print("<div class=error>The file $filename is not writeable</div>");
    }
}

function checkWritePermission($state) {
    $errdirArr = null; // FIXME There is no need for it if it is not a global and it should be a global

    $tmpRootPath = (defined("SCRIPTROOT") && SCRIPTROOT) ? SCRIPTROOT : ($_SERVER['DOCUMENT_ROOT'] . LINKROOT);

    if (INSTDEBUG != 0 && file_exists(INSTDEBUGFILE) && ! is__writable(INSTDEBUGFILE)) {
        $errdirArr[] = _realpath(dirname(__FILE__) . "/" . INSTDEBUGFILE);
    } elseif (INSTDEBUG != 0 && ! is__writable(dirname(INSTDEBUGFILE))) {
        $errdirArr[] = _realpath(dirname(INSTDEBUGFILE));
    }

    if (!(is__writable(dirname($_SERVER["SCRIPT_FILENAME"]) . "/configuration.sample.php"))) {
        $errdirArr[] = dirname($_SERVER["SCRIPT_FILENAME"]) . "/configuration.sample.php";
    }

    foreach (array('style/fresh/templates_c', 'style/fresh/cache') as $tmpDir) {
        if (!is__writable($tmpRootPath . "/" . $tmpDir)) {
            $errdirArr[] = _realpath($tmpRootPath . "/" . $tmpDir) . " [ROOT: {$tmpRootPath}, PATH: {$tmpDir}]";
        }
    }

    if ($state == 'UPLOAD'
        && isset($_POST['DEST_HTTP_DIR'])
        && ! is__writable($tmpRootPath . "/" . $_POST['DEST_HTTP_DIR'])) {
        $errdirArr[] = _realpath($tmpRootPath . "/" . $_POST['DEST_HTTP_DIR']) . " [$_POST[DEST_HTTP_DIR]]";
    }

    if (count($errdirArr) > 0) {
        print("<div class='error'>The following " . count($errdirArr) . " files/directories are not writable:<BR>");

        foreach ($errdirArr as $errdir) {
            print"&nbsp;$errdir<BR>";
        }

        print("<p>Your webserver must have write access to this files/directories. Please correct the permissions.</p>".
              "</div>");
    }
}

function getLanguages() {
    return preg_filter("/^([A-Z]{2})\.php$/", "$1", scandir('../Language/UTF8'));
}

# set_magic_quotes_runtime(0); is deprecated
ini_set('magic_quotes_runtime', 0);

$confFileArr = file(dirname(__FILE__) . "/configuration.sample.php");
// print(dirname(__FILE__) . "/configuration.sample.php");

print("<h1>Clubdata2 Installation Tool</h1>");

print ('<form action="" method="post">');
print('<div class="hint">
       <div class="submitbar">
       Configuration:<br>
       (1)<input type="submit" name="NEWSTATE" value="BASE">
       (2)<input type="submit" name="NEWSTATE" value="PATH">
       (3)<input type="submit" name="NEWSTATE" value="UPLOAD">
       (5)<input type="submit" name="NEWSTATE" value="DB">
       (6)<input type="submit" name="NEWSTATE" value="DBLOAD">
       (7)<input type="submit" name="NEWSTATE" value="DEBUG">
       </div></div>');

// Set state to save and to show
$state = empty($_POST['NEWSTATE']) ? (empty($_POST['STATE']) ? 'BASE' : $_POST['STATE'] ) : $_POST['NEWSTATE'];

// If Parameter ACTION is set to SAVE, do it.
if (isset($_POST['ACTION']) && ($_POST['ACTION'] == 'SAVE' || $_POST['ACTION'] == 'SAVE AND NEXT')) {
    switch ($state) {
        case 'BASE':
            foreach (array('INDEX_PHP', 'SERVER_SYSTEM_TYPE', 'DEFAULT_LANGUAGE') as $path) {
                changeConfigValue(
                    $confFileArr,
                    '(\/\/)?\s*define\s*\(\s*["\']' . $path . '["\']',
                    'define("' . $path . '", "' . $_POST[$path] . '");'
                );
            }
            $state = ($_POST['ACTION'] == 'SAVE AND NEXT' ? 'PATH' : 'BASE');
            break;
        case 'PATH':
            if (empty($_POST['SCRIPTROOT'])) {
                $pathValue = str_replace(
                    $_SERVER['DOCUMENT_ROOT'],
                    '{$_SERVER[\'DOCUMENT_ROOT\']}',
                    $_POST['SCRIPTROOT']
                );
                changeConfigValue(
                    $confFileArr,
                    '(\/\/)?\s*define\s*\(\s*"SCRIPTROOT"',
                    '//define("SCRIPTROOT", "' . $pathValue . '");'
                );
            } else {
                $pathValue = str_replace(
                    $_SERVER['DOCUMENT_ROOT'],
                    '{$_SERVER[\'DOCUMENT_ROOT\']}',
                    $_POST['SCRIPTROOT']
                );
                changeConfigValue(
                    $confFileArr,
                    '(\/\/)?\s*define\s*\(\s*"SCRIPTROOT"',
                    'define("SCRIPTROOT", "' . $pathValue . '");'
                );
            }

            $path_names = array('LINKROOT', 'ADODB_DIR', 'PHP2EXCEL_DIR', 'SMARTY_DIR', 'FORMSGENERATION_DIR', 'BACKUPDIR');

            foreach ($path_names as $path) {
                $pathValue = str_replace($_SERVER['DOCUMENT_ROOT'], '{$_SERVER[\'DOCUMENT_ROOT\']}', $_POST[$path]);
                changeConfigValue(
                    $confFileArr,
                    '(\/\/)?\s*define\s*\(\s*"' . $path . '"',
                    'define("' . $path . '", "' . $pathValue . '");'
                );
            }

            $state = ($_POST['ACTION'] == 'SAVE AND NEXT' ? 'UPLOAD' : 'PATH');
            break;
        case 'UPLOAD':
            $path_names = array(
                'DEST_HTTP_DIR',
                'MAX_SIZE_LIMIT',
                'ALLOWED_UPLOAD_TYPES',
                'MAXTHUMBNAILHEIGHT',
                'MAXTHUMBNAILWIDTH'
            );

            foreach ($path_names as $path) {
                changeConfigValue(
                    $confFileArr,
                    '(\/\/)?\s*define\s*\(\s*"' . $path . '"',
                    'define("' . $path . '", "' . $_POST[$path] . '");'
                );
            }

            $state = ($_POST['ACTION'] == 'SAVE AND NEXT' ? 'DB' : 'UPLOAD');
            break;
        case 'DB':
            foreach (array('DB_TYPE', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWD', 'DB_TABLEPREFIX') as $path) {
                changeConfigValue(
                    $confFileArr,
                    '(\/\/)?\s*define\s*\(\s*["\']' . $path . '["\']',
                    'define("' . $path . '", "' . $_POST[$path] . '");'
                );
            }

            $state = ($_POST['ACTION'] == 'SAVE AND NEXT' ? 'DBLOAD' : 'DB');
            break;
        case 'DBLOAD':
            require_once("./configuration.sample.php");
            require_once("../include/clubdataDB.class.php");

            $host = DB_HOST;
            $name = 'mysql';
            $user = $_POST['USER'];
            $passwd = $_POST['PASSWD'];

            $db = &ADONewConnection(DB_TYPE); # create a connection

            if (CHARACTER_ENCODING == 'UTF8') {
                $ch1 = "utf8";
                $ch2 = "utf8_general_ci";
            } else {
                $ch1 = 'latin1';
                $ch2 = 'latin1_swedish_ci';
            }

            $ok = true;
            print("<div class='hint'>Creating database " . DB_NAME . ": ");

            if (isset($_POST['createDatabase'])) {
                $db->PConnect($host, $user, $passwd, $name);
                $db->Execute("SET NAMES '" . CHARACTER_ENCODING . "'");
                $db->Execute("set character set " . CHARACTER_ENCODING);
                $sql = "CREATE DATABASE `" . DB_NAME . "` DEFAULT CHARACTER SET $ch1 COLLATE $ch2";

                if (($ok = $db->Execute($sql)) === false) {
                    print("<div class='error'>Cannot create database " . DB_NAME . ": <BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                } else {
                    print("OK");
                }
            } else {
                print("Database " . DB_NAME . " not created. Using existing one !");
                $db->PConnect($host, $user, $passwd, DB_NAME);
                $db->Execute("SET NAMES '" . CHARACTER_ENCODING . "'");
                $db->Execute("set character set " . CHARACTER_ENCODING);
                $ok = true;
            }

            print("</div>");

            if ($ok) {
                print("<div class='hint'>Changing database to " . DB_NAME . ": ");
                $sql = "USE `" . DB_NAME . "`";

                if (($ok = $db->Execute($sql)) === false) {
                    print("<div class='error'>Cannot change to database " . DB_NAME . ": <BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                } else {
                    print("OK");
                }

                print("</div>");
            }

            if ($ok) {
                print("<div class='hint'>Creating user " . DB_USER . "@" . DB_HOST . ": ");
                $sql = "CREATE USER '" . DB_USER . "'@'" . DB_HOST . "' IDENTIFIED BY '" . DB_PASSWD  . "'";

                if ($db->Execute($sql) === false) {
                    print("<div class='error'>Cannot create user " . DB_USER . ", continuing anyway: <BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                } else {
                    print("OK");
                }

                print("</div>");

                print("<div class='hint'>Setting permission for user " . DB_USER . "@" . DB_HOST . ": ");
                $sql = "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, CREATE TEMPORARY TABLES ON `" . DB_NAME . "` . * TO '" . DB_USER . "'@'" . DB_HOST . "'";

                if ($db->Execute($sql) === false) {
                    print("<div class='error'>Cannot set permissions for user " . DB_USER . ", continuing anyway: <BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                } else {
                    print("OK");
                }

                print("</div>");
            }

            if ($ok) {
                print("<div class='hint'>Creating Tables: <BR>");

                if (false === ($structSQL = file_get_contents(dirname(__FILE__).'/Clubdata2-structure.mysql.sql'))) {
                    print("<div class='error'>Cannot read " . dirname(__FILE__).'/Clubdata2-structure.mysql.sql' . "</div>");
                } else {
                    $structSQLArr = explode(';', $structSQL);

                    foreach ($structSQLArr as $sql) {
                        $sql = trim($sql);

                        if (!empty($sql) && $sql != '') {
                            if (($ok = $db->Execute($sql)) === false) {
                                print("<div class='error'>Cannot Execute SQL from Clubdata2-structure.mysql.sql<BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                                break;
                            }
                        }
                    }

                    if ($ok) {
                        print("OK");
                    }

                    print("</div>");
                }
            }

            if ($ok) {
                print("<div class='hint'>Importing initial data: <BR>");

                if (false === ($structSQL = file_get_contents(dirname(__FILE__).'/Clubdata2-data.mysql.sql'))) {
                    print("<div class='error'>Cannot read " . dirname(__FILE__).'/Clubdata2-data.mysql.sql' . "</div>");
                } else {
                    // Split only on ; at end of line, as there are also semicolons in the text fields
                    $structSQLArr = preg_split("/;\s*\n/", $structSQL, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                    foreach ($structSQLArr as $sql) {
                        $sql = trim($sql);

                        if (!empty($sql) && $sql != '') {
                            if (($ok = $db->Execute($sql)) === false) {
                                print("<div class='error'>Cannot Execute SQL from Clubdata2-data.mysql.sql<BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                                break;
                            }
                        }
                    }

                    if ($ok) {
                        print("OK");
                    }

                    print("</div>");
                }
            }

            if ($ok) {
                print("<div class='hint'>Importing initial help data: <BR>");

                if (false === ($structSQL = file_get_contents(dirname(__FILE__).'/Clubdata2-help.mysql.sql'))) {
                    print("<div class='error'>Cannot read " . dirname(__FILE__).'/Clubdata2-help.mysql.sql' . "</div>");
                } else {
                    // Split only on ; at end of line, as there are also semicolons in the text fields
                    $structSQLArr = preg_split("/;\s*\n/", $structSQL, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

                    foreach ($structSQLArr as $sql) {
                        $sql = trim($sql);

                        if (!empty($sql) && $sql != '') {
                            if (($ok = $db->Execute($sql)) === false) {
                                print("<div class='error'>Cannot Execute SQL from Clubdata2-help.mysql.sql<BR>" . $db->ErrorMsg() . "<BR>SQL: {$sql}</div>");
                                break;
                            }
                        }
                    }

                    if ($ok) {
                        print("OK");
                    }

                    print("</div>");
                }
            }

            if ($ok) {
                $state = 'DEBUG';
            }
            break;
        case 'DEBUG':
            foreach (array('DEBUG', 'SQLDEBUG', 'DEBUGFILE', 'DEBUGCLASSES') as $path) {
                switch ($path) {
                    case 'DEBUGCLASSES':
                        $value = '"' . join(',', $_POST[$path]) . '"';
                        break;
                    case 'SQLDEBUG':
                    case 'SMARTY_DEBUGGING':
                        $value = $_POST[$path] ? 'true' : 'false';
                        break;
                    case 'DEBUG':
                        $value = $_POST[$path];
                        break;
                    default:
                        $pathValue = str_replace($_SERVER['DOCUMENT_ROOT'], '{$_SERVER[\'DOCUMENT_ROOT\']}', $_POST[$path]);
                        $value = '"' . $pathValue . '"';
                        break;
                }

                changeConfigValue($confFileArr, '(\/\/)?\s*define\s*\(\s*[\'"]' . $path . '[\'"]', 'define("' . $path . '", ' . $value . ');');
            }

            $state = ($_POST['ACTION'] == 'SAVE AND NEXT' ? 'FINISHED' : 'DEBUG');
            break;
    }

    writeFile(dirname(__FILE__) . '/configuration.sample.php', $confFileArr);
}

if (file_exists("../include/configuration.php")
    && filemtime("../include/configuration.php") > filemtime("./configuration.sample.php")) {
    print("<div class=hint>ATTENTION: There exists a configuration file in the include directory which is newer than " . dirname(__FILE__) . '/configuration.sample.php' . "!<BR> Please copy it to " . dirname(__FILE__) . '/configuration.sample.php' . " to use your actual values as default values !</div>");
    $sample = 0;
} else {
    $sample = 1;
}

require_once("./configuration.sample.php");

checkWritePermission($state);

// Show parameters depending on state
switch ($state) {
    case 'BASE':
        $selectableValuesArr = array(
            'SERVER_SYSTEM_TYPE' => array('UNIX', 'WINDOWS'),
            'CHARACTER_ENCODING' => array('UTF8', 'ISO-8859-1'),
            'DEFAULT_LANGUAGE'   => getLanguages()
        );

        print("<h2>Base configuration</h2>");
        print("<table class='entrytable'>");

        foreach ($selectableValuesArr as $path => $optionArr) {
            $pathValue = constant($path);

            print("<tr class=\"entryrow\">
              <td class=\"entrydesc\" nowrap>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><select name='{$path}'>");

            foreach ($optionArr as $option) {
                print("<option value='$option'" . (constant($path) == $option ? 'selected=selected' : '') . ">{$option}</option>");
            }

            print("</select></td></tr>");
        }

        print("</tr></table>");
        break;
    case 'PATH':
        // Try to guess path out of installation script
        $tmpBaseUrl = dirname(dirname($_SERVER["SCRIPT_NAME"]));
        $tmpBaseUrl = (substr($tmpBaseUrl, -1) == '/' ? "$tmpBaseUrl" . '.' : $tmpBaseUrl);
        define('BASE_URL', $tmpBaseUrl); // double dirname to cut of Installation subdirectory

        $tmpBasePath = preg_replace('/'. preg_quote(BASE_URL, '/') . '$/', '', _realpath('..'), 1);
        $tmpBasePath = (substr($tmpBasePath, -1) != '/' && substr($tmpBasePath, -1) != '\\'
            ? "$tmpBasePath" . '/'
            : $tmpBasePath);

        define('BASE_PATH', $tmpBasePath); // Assume that this install.php is in a subdirectory of Clubdata V2

        print("<h2>Path configuration</h2>");
        print("<div class='hint'>System Paths:<br>
				&nbsp;\$_SERVER['DOCUMENT_ROOT']: " . $_SERVER['DOCUMENT_ROOT'] . "<br>" .
               "&nbsp;\$_SERVER['SCRIPT_NAME']: " . $_SERVER['SCRIPT_NAME'] . "<p>");
        print("Calculated Paths:<BR>
				&nbsp;SCRIPTROOT: " . BASE_PATH . (_realpath(BASE_PATH) == _realpath($_SERVER['DOCUMENT_ROOT']) ? " (which is identical to \$_SERVER[DOCUMENT_ROOT], SCRIPTROOT may stay empty)" : " (which is <b>not</b> identical to \$_SERVER[DOCUMENT_ROOT], please define SCRIPTROOT)") .
                "<br />&nbsp;LINKROOT: " . BASE_URL . (BASE_URL == LINKROOT ? "" : " (which is <b>not</b> identical to LINKROOT, please correct LINKROOT)") .
                "<br /><br />The path $_SERVER[DOCUMENT_ROOT] will be replaced by \$_SERVER['DOCUMENT_ROOT']</div>");

        if (defined('SCRIPTROOT') && SCRIPTROOT != '') {
            if (!is_dir(SCRIPTROOT . LINKROOT)) {
                print("<div class='warning'>SCRIPTROOT (" . constant('SCRIPTROOT') . ") is not correct and doesn't point to Clubdata V2. <BR>Please adjust SCRIPTROOT to point to your Clubdata V2 installation !</div>");
            }
        } else {
            if (!is_dir($_SERVER['DOCUMENT_ROOT']. LINKROOT)) {
                print("<div class='warning'>Either \$_SERVER[DOCUMENT_ROOT] ($_SERVER[DOCUMENT_ROOT]) or LINKROOT (". LINKROOT . ") (or both) are not set correctly and doesn't point to Clubdata V2. <BR>Please set SCRIPTROOT to point to your Clubdata V2 installation !</div>");
            }
        }

        print("<table class='entrytable'>");

        $path_names = array(
            'SCRIPTROOT',
            'LINKROOT',
            'ADODB_DIR',
            'PHP2EXCEL_DIR',
            'SMARTY_DIR',
            'FORMSGENERATION_DIR',
            'BACKUPDIR'
        );

        foreach ($path_names as $path) {
            $pathValue = defined($path) ? constant($path) : null;

            if ($path != "SCRIPTROOT" && $path != 'LINKROOT' && ! is_dir($pathValue)) {
                print("<tr><td></td><td colspan='1'><div class='warning'>{$path} ({$pathValue}) doesn't exist. Please set correct {$path}!</div></td></tr>");
            }

            print("<tr class='entryrow'>
              <td class='entrydesc'>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><input class='entryinput' name='{$path}' id='{$path}' value=" . (defined($path) ? $pathValue : '') . "></td>
              </tr> ");
        }

        print("</tr></table>");
        break;
    case 'UPLOAD':
        print("<H2>Upload configuration</H2>");
        print("<table class='entrytable'>");

        $path_names = array(
            'DEST_HTTP_DIR',
            'MAX_SIZE_LIMIT',
            'ALLOWED_UPLOAD_TYPES',
            'MAXTHUMBNAILHEIGHT',
            'MAXTHUMBNAILWIDTH'
        );

        foreach ($path_names as $path) {
            $pathValue = constant($path);

            print(" <tr class='entryrow'>
              <td class='entrydesc'>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><input class='entryinput' name='{$path}' value=" . (defined($path) ? $pathValue : '') . "></td>
              </tr> ");
        }

        print("</tr></table>");
        break;
    case 'DB':
        print("<H2>Database configuration</H2>");
        print("<table class='entrytable'>");

        foreach (array('DB_TYPE', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWD', 'DB_TABLEPREFIX') as $path) {
            $pathValue = constant($path);

            print(" <tr class='entryrow'>
              <td class='entrydesc'>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><input class='entryinput' name='{$path}' value=" . (defined($path) ? $pathValue : '') . "></td>
              </tr> ");
        }

        print("</tr></table>");
        break;
    case 'DBLOAD':
        print("<div class=hint>Clicking on 'SAVE' will create a new database on the server " . DB_HOST . ". The name of the database will be " . DB_NAME . " on server " . DB_HOST . " (as defined in configuration step DB)<BR>");
        print("Please make sure, the user defined in the following fields has permission to create a database and create tables. It needn't to be the same user as defined in configuration step DB<BR>");
        print("<BR>NOTE: If you do not have permission to create a database, and/or the database " . DB_NAME . " exists already, uncheck <I>Create Database</I> below<BR>");
        print("</div>");
        print("<table class='entrytable'>");
        print(" <tr class='entryrow'>
             <td class='entrydesc'>Create Database:</td><td class='entrycol'><input type=checkbox checked class='entryinput' name='createDatabase'></td>
             </tr> ");

        foreach (array('USER', 'PASSWD') as $path) {
            $pathValue = isset($_POST[$path]) ? $_POST[$path] : constant("DB_" . $path);

            print(" <tr class='entryrow'>
             <td class='entrydesc'>{$path}:</td><td class='entrycol'><input class='entryinput' type='" . ($path == 'PASSWD' ? 'password' : 'text') . "' name='{$path}' value=" . $pathValue . "></td>
             </tr> ");
        }

        print("</tr></table>");
        break;
    case 'DEBUG':
        $selectableValuesArr = array(
            'DEBUG' => array(
                '0' => '(0) No debugging',
                '1' => '(1) Debugging to screen (not suggested!)',
                '2' => '(2) Debugging to file'
            ),
            'SQLDEBUG' => array('false' => 'False', 'true' => 'True'),
            'SMARTY_DEBUGGING' => array('false' => 'False', 'true' => 'True'),
        );

        $selectableValuesArr1 = array(
            'DEBUGCLASSES' => array(
                'MAIN' => 'debug main functions (like index.php)',
                'ADDRESSES' => 'debug class addresses',
                'LIST' => 'debug list',
                'DBLIST' => 'debug class DBList',
                'AUTH' => 'debug authentication',
                'SMARTY' => 'SMARTY classes',
                'DBTABLE' => 'debug class DBTABLE',
                'FORMS' => 'debug formsgeneration class',
                'MEMBERINFO' => 'debug class memberinfo',
                'M_PAYMENTS' => 'debug module (and class) payments',
                'M_FEES' => 'debug module (and class) fees',
                'M_ADMIN' => 'debug module (and class) Admin',
                'M_JOBS' => 'debug module (and class) Jobs',
                'M_SEARCH' => 'debug module (and class) search',
                'M_MEMBER' => 'debug module (and class) member',
                'M_MAIL' => 'debug module (and class) mail',
                'M_QUERIES' => 'debug module (and class) query',
                'M_HELP' => 'debug module (and class) help',
                'M_LIST' => 'debug module (and class) list',
                'CDDB' => 'Clubdata Database interface',
                'M_CONFERENCES' => 'debug module (and class) conferences'
            ),
        );

        print("<H2>Debugging configuration</H2>");
        print("<div class='hint'>The path $_SERVER[DOCUMENT_ROOT] will be replaced by \$_SERVER['DOCUMENT_ROOT']</div>");
        print("<table class='entrytable'>");

        foreach ($selectableValuesArr as $path => $optionArr) {
            $pathValue = defined($path) ? constant($path) : null;

            print(" <tr class='entryrow'>
              <td class='entrydesc' nowrap>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><select name='{$path}'>");

            foreach ($optionArr as $optionIdx => $optionVal) {
                print("<option value='$optionIdx'" . (constant($path) == $optionIdx ? 'selected=selected' : '') . ">{$optionVal}</option>");
            }

            print("</select></td></tr>");
        }

        foreach (array('DEBUGFILE') as $path) {
            $pathValue = constant($path);

            print(" <tr class='entryrow'>
              <td class='entrydesc'>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><input class='entryinput' name='{$path}' value=" . (defined($path) ? $pathValue : '') . "></td>
              </tr> ");
        }

        foreach ($selectableValuesArr1 as $path => $optionArr) {
            $pathValueArr = preg_split("/[\s,]+/", constant($path));

            print(" <tr class='entryrow'>
              <td class='entrydesc' nowrap>{$path} <a target=_blank href='DOCU/Installation/Installation/_configuration.sample.php.html#define" . $path . "'><img src='info.png' border=0></a></td><td class='entrycol'><select " . ($path == 'DEBUGCLASSES' ? "multiple " : "") . "name='{$path}[]'>");

            foreach ($optionArr as $optionIdx => $optionVal) {
                print("optionIdx: $optionIdx<BR>");
                print("<option value='{$optionIdx}'" . (in_array($optionIdx, $pathValueArr) ? 'selected=selected' : '') . ">$optionVal</option>");
            }

            print("</select></td></tr>");
        }

        print("</tr></table>");
        break;
    case 'FINISHED':
        print("<div class=hint style='font-weight: bolder' >You have successfully installed the clubdata database. Please copy the configuration file <BR><span style='font-style: italic'>" . dirname(__FILE__) . "/configuration.sample.php");
        print("</span><BR>to<BR><span style='font-style: italic'>" . $_SERVER['DOCUMENT_ROOT'] . LINKROOT . "/include/configuration.php</span><BR>");
        print("</div>");
        break;
}

if ($state != 'FINISHED') {
    print("<input type=hidden name='STATE' value='{$state}'>");
    print("<div class='submitbar'><input class=save type=submit name=ACTION value=SAVE>&nbsp;<input class=save type=submit name=ACTION value='SAVE AND NEXT'>&nbsp;<input class=abort type=submit name=ACTION value=ABORT></div>");
    print("</form>");
}

print("<hr><pre>");
print(htmlentities(implode('', $confFileArr)));
print("</pre>");
print("<pre>");
print_r($_POST);
print("</pre>");
?>

<script type="text/javascript" src="../bower_components/jquery/dist/jquery.min.js" charset="utf8"></script>
</body>
</html>
