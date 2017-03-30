<?php
/**
 * Clubdata Administration Modules
 *
 * Contains classes to administer Clubdata.
 *
 * @package Installation
 * @subpackage Installation
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.4 $
 * @copyright Copyright (c) 2009, Franz Domes
 */

/*
 configuration.php: Configuration of Clubdata2
 Copyright (C) 2007-2009 Franz Domes

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
/**
 */
if (get_magic_quotes_runtime()) {
    set_magic_quotes_runtime(0);
}

date_default_timezone_set('America/Sao_Paulo');

/**
 * Defines Root of HTML-Documents. This overwrites the global $_SERVER['DOCUMENT_ROOT']
 *
 * Sometimes the php variable DOCUMENT_ROOT doesn't point to the correct directory
 * (Mostly when private home directories are used)
 * In this case, uncomment the following line and set SCRIPTROOT to the root directory
 * of Clubdata
 */
//define("SCRIPTROOT", "");

/**
 * Defines the root of Clubdata2-Skripts relative to DOCUMENT_ROOT (or SCRIPTROOT)
 *
 * Defines how to access Clubdata via the Browser.
 * LINKROOT is the directory relativ to your home directory of your homepage and should start with a slash (or backslash on Windows systems)
 */
define("LINKROOT", "/");

/**
 * Defines Path ADODB library
 *
 * If you don't have the following tools in a path defined by
 * include_dir in php.ini, you have to set the correct paths here !
 * The paths here are valid for the complete installation of Clubdata
 * (the tools are installed in the subdirectoy Tools)<BR>
 * The directories MUST end by a slash !!
 */
define("ADODB_DIR", "{$_SERVER['DOCUMENT_ROOT']}/vendor/adodb/adodb-php");

/**
 * Defines Path PHP2EXCEL library
 *
 * If you don't have the following tools in a path defined by
 * include_dir in php.ini, you have to set the correct paths here !
 * The paths here are valid for the complete installation of Clubdata
 * (the tools are installed in the subdirectoy Tools)<BR>
 * The directories MUST end by a slash !!
 */
define("PHP2EXCEL_DIR", "{$_SERVER['DOCUMENT_ROOT']}/Tools/php2excel/");

/**
 * Defines Path SMARTY library
 *
 * If you don't have the following tools in a path defined by
 * include_dir in php.ini, you have to set the correct paths here !
 * The paths here are valid for the complete installation of Clubdata
 * (the tools are installed in the subdirectoy Tools)<BR>
 * The directories MUST end by a slash !!
 */
define("SMARTY_DIR", "{$_SERVER['DOCUMENT_ROOT']}/vendor/smarty/smarty/libs/");

/**
 * Defines Path FORMSGENERATION library
 *
 * If you don't have the following tools in a path defined by
 * include_dir in php.ini, you have to set the correct paths here !
 * The paths here are valid for the complete installation of Clubdata
 * (the tools are installed in the subdirectoy Tools)<BR>
 * The directories MUST end by a slash !!
 */
define("FORMSGENERATION_DIR", "{$_SERVER['DOCUMENT_ROOT']}/vendor/phpclasses/formsgeneration/");

/**
 * Name of index.php file.
 *
 * Normally it should be called index.php
 * but sometimes the provider needs the extension .php5 to use PHP Version 5 (needed by Clubdata)
 * In this case you can rename index.php to index.php5 and change the constant INDEX_PHP to
 * index.php5
 */
define("INDEX_PHP", "");

/**
 * Path to store uploaded pictures
 *
 * Where to store uploaded pictures<BR>
 * This directory is relative to {@link LINKROOT} and must exist and must be writeable by the webserver
 */
define('DEST_HTTP_DIR', 'logos/');

/**
 * Maximum size of an upload in bytes
 */
define("MAX_SIZE_LIMIT", "500000");

/**
 * Mime-types of allowed uploads, separated by comma WITHOUT spaces
 */
define("ALLOWED_UPLOAD_TYPES", "image/jpeg,image/pjpeg,image/png,image/gif,application/pdf");


/**
 * Height of thumnails created from uploaded images
 */
define("MAXTHUMBNAILHEIGHT", "150");

/**
 * Height of thumnails created from uploaded images
 */
define("MAXTHUMBNAILWIDTH", "150");

/**
 * define type of database (only mysql at the moment)
 */
define("DB_TYPE", "mysqli");

/**
 * define database server
 */
define("DB_HOST", "localhost");

/**
 * define name of clubdata database
 */
define("DB_NAME", "clubdata");

/**
 * define name of database user
 */
define("DB_USER", "clubdata");

/**
 * define password of database user
 */
define("DB_PASSWD", "clubdata");

/**
 * Prefix for database tables. All tables will begin by this prefix
 */
define("DB_TABLEPREFIX", "cd_");

/**
 *  Predefine default language.
 *  This language will be used before a user logs in and as standard language where a language can be selected
 *  Possible values: UK, DE, FR (and any other languages defined in Clubdata2/Language/)
 */
define("DEFAULT_LANGUAGE", "UK");

/**
 * Define system type: UNIX or WINDOWS
 *
 * This defines the type of your system where Clubdata runs. Path separators and directoy separators will
 * be choosen depending on the value.
 * Allowed values: UNIX and WINDOWS
 */
define("SERVER_SYSTEM_TYPE", "WINDOWS");

/**
 * Define character set used
 *
 * Be carefull when changing this parameter.
 * When you upgrade from an old version, you should leave it at
 * ISO-8859-1, else you can set it to UTF8.
 * Make sure you have created the database by using the correct
 * configuration files
 */
define("CHARACTER_ENCODING", "UTF8");

/************************************************************************************
 /*                            DEMO MODE
 ************************************************************************************/
/**
 *  True of DemoModus should be used, false for normal use
 */
define("DEMOMODE", false);

/************************************************************************************
 /*                            DEBUGGING
 ************************************************************************************/
/**
 * Constant, defining debug mode, value bit coded
 *     0000 = (0) = no debugging,
 *     0001 = (1) = debug to screen
 *     0010 = (2) = debug to file (@see DEBUGFILE)
 */
define("DEBUG", 2);

/**
 * Constant, defining SQL debug mode (true, or false)
 */
define("SQLDEBUG", true);

/**
 * Constant, defining Name of debugging file
 */
define("DEBUGFILE", "{$_SERVER['DOCUMENT_ROOT']}/Clubdata2/tmp/clubdata.log");

/**
 * Constant, defining debugging of Smarty (true or false)
 */
define('SMARTY_DEBUGGING', false);

/**
 * Constant, defining classes to debug:
 *
 *     <b>MAIN</b>  debug main functions (like index.php)<br>
 *     <b>ADDRESSES</b> debug class addresses<br>
 *     <b>LIST</b> debug list<br>
 *     <b>DBLIST</b> debug class DBList<br>
 *     <b>AUTH</b> debug authentication<br>
 *     <b>SMARTY</b> SMARTY classes<br>
 *     <b>DBTABLE</b> debug class DBTABLE<br>
 *     <b>FORMS</b> debug formsgeneration class<br>
 *     <b>MEMBERINFO</b> debug class memberinfo<br>
 *     <b>M_PAYMENTS</b> debug module (and class) payments<br>
 *     <b>M_FEES</b> debug module (and class) fees<br>
 *     <b>M_ADMIN</b> debug module (and class) Admin<br>
 *     <b>M_JOBS</b> debug module (and class) Jobs<br>
 *     <b>M_SEARCH</b> debug module (and class) search<br>
 *     <b>M_MEMBER</b> debug module (and class) member<br>
 *     <b>M_MAIL</b> debug module (and class) mail<br>
 *     <b>M_QUERIES</b> debug module (and class) query<br>
 *     <b>M_HELP</b> debug module (and class) help<br>
 *     <b>M_LIST</b> debug module (and class) list<br>
 *     <b>CDDB</b> Clubdata Database interface<br>
 *     <b>M_CONFERENCES</b> debug module (and class) conferences<br>
 */
define("DEBUGCLASSES", "MAIN,ADDRESSES,LIST,DBTABLE,FORMS,MEMBERINFO,M_PAYMENTS,M_FEES,M_ADMIN,M_JOBS,M_SEARCH,M_MEMBER,M_MAIL,M_QUERIES,M_HELP,M_LIST,CDDB,M_CONFERENCES");

//error_reporting(E_ERROR | E_WARNING | E_PARSE | /*E_NOTICE | /**/ E_CORE_ERROR | E_CORE_WARNING);
error_reporting(E_ALL);
ini_set('display_errors', false);

// FD20110101 Set start value of language to DEFAULT_LANGUAGE. Will be overwritten by user defined value later
$language = DEFAULT_LANGUAGE;
