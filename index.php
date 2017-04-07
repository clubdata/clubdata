<?php
/**
 * Clubdata start point
 *
 * Contains the start file of Clubdata
 *
 * @package Clubdata
 * @subpackage Clubdata
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes
 *
 * @todo: Find a tooltip script for jquery which loads tooltips via Ajax, see main.tpl
 *
 */

/**
 *
 */
/*
    index.php: Frame for Clubdata
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

ob_start();
// phpinfo(INFO_VARIABLES);

//Include server host specific include file if one exists.
// This is mostly used on debugging server, where multiple servers access the same
// piece of code. So you might have a configuration for linux and one for windows in parallel
//print("include/configuration.$_SERVER[SERVER_NAME].php");
//print("SERVER: $_SERVER[SERVER_NAME]<BR>");
if (file_exists("include/configuration.{$_SERVER['SERVER_NAME']}.php")) {
    error_log("CONFIGURATION: Using file include/configuration.{$_SERVER['SERVER_NAME']}.php");
    include_once("include/configuration.{$_SERVER['SERVER_NAME']}.php");
} elseif (file_exists("include/configuration.php")) {
    error_log('CONFIGURATION: Using file include/configuration.php');
    include_once('include/configuration.php');
}

if (!defined('LINKROOT')) {
    print(<<<END
		<h3>Configuration file 'include/configuration.php' not found!</h3>

        Please run installation routine <a href="Installation/install.php">Installation/install.php</a> and copy<br>
        <b>Installation/configuration.sample.php</b><br>
        to<br>
        <b>include/configuration.php</b>
END
    );
    exit(1);
}

if (DB_TYPE != 'mysqli') {
    print(<<<END
		<h3>Database type has changed from mysql to mysqli!</h3>

        Please change in <i>include/configuration.php</i><br>
        <pre>define("DB_TYPE", "mysql");</pre>
        to
        <pre>define("DB_TYPE", "mysqli");</pre>
END
    );
    exit(1);
}

require 'vendor/autoload.php';
include_once('include/clubdataDB.class.php');
require_once((defined('PHP2EXCEL_DIR') ? (PHP2EXCEL_DIR) : '') . 'biff.php');
require_once('include/clubdataSmarty.class.php');
require_once('include/error.class.php');
// Global Error handling
$APerr = new ErrorHandler();

require_once 'include/navigation.php';

require_once('include/Authentication/authentication.php');

$auth = new Auth($options);

if (isset($logout) && $logout == 1) {
    $auth->logout();
}

$auth->forceLogin();

require_once('include/function.php');

debug_r('MAIN', $auth, 'AUTH:');

debug_r('MAIN', '==============================================================', '=======');
debug_r('MAIN', $_POST, 'POST');
debug_r('MAIN', $_GET, 'GET');
debug_r('MAIN', $_SESSION, 'SESSION');
// debug_r('MAIN', $GLOBALS, 'GLOBALS');

$module = getGlobVar(
    'mod',
    'settings|help|jobs|queries|main|members|search|list|fees|admin|payments|email|conferences',
    'PG'
);

if (empty($module)) {
    if ($auth->isIdentified) {
        $startPage = getConfigEntry($db, 'Startpage');

        if (substr($startPage, 0, 1) == '?') {
            parse_str(substr($startPage, 1), $tmpGetArr);
            $_GET = array_unique(array_merge($tmpGetArr, $_GET));
            $module = $tmpGetArr['mod'];
        } else {
            $module = $startPage;
        }
    } else {
        $module = 'main';
    }
}

$_SESSION['mod'] = $module;
debug('MAIN', "MODULE: {$module}");

$modFile = "modules/{$module}/{$module}.php";

if (!file_exists(SCRIPTROOT . '/' . $modFile)) {
    $module = getConfigEntry($db, 'Startpage');
    $modFile = "modules/{$module}/{$module}.php";

    if (empty($modFile) || ! file_exists(SCRIPTROOT . '/' . $modFile)) {
        $module = 'main';
        $modFile = 'modules/main/main.php';
    }
}

debug('MAIN', "MODFILE1: {$modFile}");
include_once($modFile);
debug('MAIN', "MODFILE2: {$modFile}");

$modName = 'Cd' . ucFirst($module);
$modClass = new $modName();

$doAction = getGlobVar('Action', '', 'PG');
debug('MAIN', "DO_ACTION: {$doAction}");

if ($modClass->getModulePerm($doAction) !== true) {
    debug('MAIN', "DO_ACTION: {$doAction}, You do not have the appropriate permissions to perform this task");
    $APerr->setFatal(lang('You do not have the appropriate permissions to perform this task'));
    include_once('modules/main/main.php');
    unset($_POST['view'], $_GET['view'], $_SESSION['view']);
    $modClass = new CdMain();
    $modClass->getModulePerm();
} else {
    debug('MAIN', "DO_ACTION: {$doAction}, EXECUTE");

    if ($doAction) {
        debug('MAIN', "DO_ACTION: {$doAction}, EXECUTE1");
        $modClass->doAction($doAction);
    }
}
debug('MAIN', 'PERMISSION');

// write the HTML headers
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');      // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: no-cache, must-revalidate, no-store, post-check=0, pre-check=0, false');        // HTTP/1.1
header('Pragma: no-cache');    // HTTP/1.0
// ob_end_flush();

$modClass->display();
