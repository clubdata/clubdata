<?php
/**
 * Authentication class
 *
 * The Listing class contains methods to setup and manipulate listings of datas.
 * This class is rarely used directly, but often overload e.g. {@link DbList}
 *
 * @package icreativa
 * @subpackage General
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Julio C�ar Carrascal Urquijo <jcesar@phreaker.net>
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes, Julio C�ar Carrascal Urquijo <jcesar@phreaker.net>
 */


/* Auth.php - User authentication component.
 * Copyright (C) 2002 Julio C�ar Carrascal Urquijo <jcesar@phreaker.net>
 *
 * This  library  is  free  software;  you can redistribute it and/or modify it
 * under  the  terms  of the GNU Library General Public License as published by
 * the  Free  Software Foundation; either version 2 of the License, or (at your
 * option) any later version.
 *
 * This  library is distributed in the hope that it will be useful, but WITHOUT
 * ANY  WARRANTY;  without  even  the  implied  warranty  of MERCHANTABILITY or
 * FITNESS  FOR  A  PARTICULAR  PURPOSE.  See  the  GNU  Library General Public
 * License for more details.
 *
 * You  should  have  received a copy of the GNU Library General Public License
 * along  with  this  library;  if  not, write to the Free Software Foundation,
 * Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA. */

/**
 * Need a valid username/password pair.
 *
 * @access public
 */
define('AUTH_NEED_LOGIN',    -1);

/**
 * The username/password pair is invalid.
 *
 * @access public
 */
define('AUTH_INVALID_USER',  -2);

/**
 * The session has expired.
 *
 * @access public
 */
define('AUTH_EXPIRED',       -3);

/**
 * You don't have access to this area.
 *
 * @access public
 */
define('AUTH_ACCESS_DENIED', -4);

/**
 * Allow the browser to cache the page but proxys can't.
 *
 * @access public
 */
define('AUTH_CACHE', 2);	/// Cache

/**
 * Do not allow anyone to cache the page.
 *
 * @access public
 */
define('AUTH_NO_CACHE',   1);


/**
 * User authentication component.
 * This is the basic authentication component; you can use this class if you only need to
 * allow/disallow access to a page. If you need groups and roles support see the class
 * GroupAuth and the class RoleAuth documentation.
 *
 * @package icreativa
 * @author Julio C�ar Carrascal Urquijo <jcesar@phreaker.net>
 * @version 2.4 pl3
 * @access public
 */
class Auth {
	//public:

	/**
	 * Holds user information.
	 *
	 * @var array
	 * @access public
	 */
	var $user = array();

	/**
	 * If the user has been identified
	 *
	 * @var boolean
	 * @access public
	 */
	var $isIdentified = false;

	/**
	 * Wich cache level to use.
	 *
	 * @var integer
	 * @access public
	 */
	// 	var $cacheLevel = AUTH_CACHE;
	var $cacheLevel = AUTH_NO_CACHE;

	// Database connection information for ADOdb. Consult the ADOdb readme.html
	// file. This information is for the ADONewConnection() function and the
	// ADOConnection::Connect() method.

	/**
	 * Database driver. Example 'mysql', 'mssql', 'oci8'...
	 *
	 * @var string
	 * @access public
	 */
	var $dbdriver = DB_TYPE;

	/**
	 * Database hostname server.
	 *
	 * @var string
	 * @access public
	 */
	var $hostname = DB_HOST;

	/**
	 * Database username
	 *
	 * @var string
	 * @access public
	 */
	var $username = DB_USER;

	/**
	 * Database password
	 *
	 * @var string
	 * @access public
	 */
	var $password = DB_PASSWD;

	/**
	 * Database.
	 *
	 * @var string
	 * @access public
	 */
	var $database = DB_NAME;

	/**
	 * Tablenames used by Auth
	 *
	 * @var array
	 * @access private
	 */
	var $tableNames = array('USERS' => '###_Users',
                          'MEMBERS' => '###_Members',
                          'CONFIGURATION' => '###_Configuration',
                          'LOG' => '###_Log',
	);

	/**
	 * Constructor.
	 *
	 * @param array		key => val of configuration parameters.
	 * @access public
	 */
	function Auth($options = null) {
		if (!isset($_SERVER)) { // PHP 4.0.x
			$_SERVER = &$GLOBALS['HTTP_SERVER_VARS'];
		}

		//ob_start();

		// Database Squema. Change this so it reflects the names of tables and columns
		// on your site.
		//$this->_options['usersTable'] = 'users';
		//$this->_options['userIdField'] = 'id';
		//$this->_options['usernameField'] = 'username';
		//$this->_options['passwordField'] = 'password';

		// This settings affect the way the session is handled by PHP. See
		// http://www.php.net/session for an explanation of each one.
		$this->_options['cookieLifetime'] = 2592000;	// one month.
		$this->_options['cookiePath'] = '/';
		$this->_options['cookieDomain'] = null;
		$this->_options['sessionName'] = null;	// 'AUTHSESSID' for example

		// This settings affects the way the session works. 'sessionVariable' is the name
		// of the registered variable ($_SESSION['user'] by default).'expires'
		// is the time that a user has to refresh the session. 'forceRedirect' tells the
		// script to redirect the request to the page 'redirect'.
		$this->_options['sessionVariable'] = 'user';
		$this->_options['expires'] = 7200;	// two hour.
		$this->_options['forceRedirect'] = false;
		$this->_options['redirect'] = $_SERVER['PHP_SELF'];

		$this->_options = array_merge($this->_options, (array)$options);
		//var_dump($this->_options);
	}


	/**
	 * Initialize the session.
	 * Use this method only if loggin in to the current page is optional but you will
	 * want to have access to the user's information if he has already been identified.
	 *
	 * @access public
	 */
	function startSession() {
		if ($this->_sessionStarted) {
			return;
		}

		$this->_sessionStarted = true;

		$GLOBALS[$this->_options['sessionVariable']] = null;	// paranoia.

		if ($this->cacheLevel == AUTH_CACHE) {
			session_cache_limiter('private, must-revalidate');
			header('Cache-Control: private, must-revalidate');
		} else {
			session_cache_limiter('no-cache, must-revalidate');
			header('Cache-Control: no-cache, must-revalidate');
		}

		// Start the session.
		if (preg_match('!^\w+$!', $this->_options['sessionName'])) {
			session_name($this->_options['sessionName']);
		}

		session_set_cookie_params($this->_options['cookieLifetime'],
		$this->_options['cookiePath'], $this->_options['cookieDomain']);
		session_start();

		if (!isset($_SESSION)) { // PHP 4.0.x
			$_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];
		}

		if (!isset($_SESSION[$this->_options['sessionVariable']])) {
			$_SESSION[$this->_options['sessionVariable']] = array();
		}
		$this->user = &$_SESSION[$this->_options['sessionVariable']];

		// In case the user has already identified.
		$this->isIdentified = $this->_checkSession();
	}


	/**
	 * Force the user to identify him self.
	 *
	 * @access public
	 */
	function forceLogin() {
		$this->startSession();

		if (!isset($_POST)) { // PHP 4.0.x
			$_POST = &$GLOBALS['HTTP_POST_VARS'];
			$_SERVER = &$GLOBALS['HTTP_SERVER_VARS'];
			$_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];
		}

		if (!$this->isIdentified) {
			if (isset($_POST[$this->_options['usernameField']])) {
				$user = $this->_findByUsername(
				$_POST[$this->_options['usernameField']],
				$_POST[$this->_options['passwordField']]);

				// Update session.
				$user['::lastLogin::'] = time();
				$user['loggedIn'] = $this->isIdentified;

				// Save session.
				$_SESSION[$this->_options['sessionVariable']] = $user;
				$this->user = &$_SESSION[$this->_options['sessionVariable']];

				// Redirect so the username/password doesn't get saved in browser's post
				// history.
				if (null !== $this->_options['redirect']) {
					header('Location: '.$this->_options['redirect']);
					exit();
				}
			} else {
				$this->_callback(AUTH_NEED_LOGIN);
				$user['loggedIn'] = false;
			}
		}
		// Redirect if requested.
		if ($this->_options['forceRedirect'] && null !== $this->_options['redirect']) {
			header('Location: '.$this->_options['redirect']);
			exit();
		}
	}


	/**
	 * Delete all session information and logout the user.
	 *
	 * @access public
	 */
	function logout() {
		$this->forceLogin();

		if (!isset($_SESSION)) { // PHP 4.0.x
			$_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];
		}

		$GLOBALS[$this->_options['sessionVariable']] = null;	// paranoia.
		unset($_SESSION[$this->_options['sessionVariable']]);	// more paranoia.
		@session_destroy();
		$this->user = array();
	}


	/**
	 * Updates the user's information from the database.
	 * The user must be identified already. Usefull if you just updated the database and
	 * you need to update your session variable.
	 *
	 * @access public
	 */
	function refreshInfo() {
		if (!$this->isIdentified)
		{
		  $this->forceLogin();
		}

		if (!isset($_SESSION)) { // PHP 4.0.x
			$_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];
		}

		$userId = $this->user[$this->_options['userIdField']];
		$lastLogin = $this->user['::lastLogin::'];  // Save Session timeout
		$_SESSION[$this->_options['sessionVariable']] =
		$this->_findById($userId, $this->user["MemberOnly"]);
		$this->user = &$_SESSION[$this->_options['sessionVariable']];
		$this->user['::lastLogin::'] = $lastLogin;  // Set session timeout
		$this->user['loggedIn'] = true;
	}


	//protected:

	/**
	 * Holds an ADOConnection instance.
	 *
	 * @var object ADOConnection
	 */
	var $_conn = null;

	/**
	 * This array hold database configuration and execution options.
	 *
	 * @var array
	 */
	var $_options = array();

	/**
	 * Flag that states if the session has already started.
	 *
	 * @var bool
	 */
	var $_sessionStarted = false;

	/**
	 * Just calls the callback function and dies.
	 *
	 * @param int			What action should the callback function take. Has to be one
	 *                      of AUTH_NEED_LOGIN, AUTH_INVALID_USER, AUTH_ACCESS_DENIED or
	 *                      AUTH_EXPIRED.
	 * @param string		message to show to the user, optional.
	 */
	function _callback($action, $message = '') {
		// include the default callback function.
//		if (!defined('AUTH_CALLBACK'))
//		include_once(dirname(__FILE__).'/authCallback.php');
//		call_user_func(AUTH_CALLBACK, $action, $message, &$this);
//		exit();
	}


	/**
	 * Connect to the database only if necesary.
	 */
	function _connect() {
		if ($this->_conn === null) {
			$conn = &ADONewConnection($this->dbdriver);
			if ($conn === null) {
				trigger_error("$this->dbdriver is not supported", E_USER_ERROR);
				die();
			}
			$res = $conn->PConnect($this->hostname, $this->username,
			$this->password, $this->database);
			if ($res === false) {
				trigger_error("Can't connect to $this->hostname", E_USER_ERROR);
				die();	// paranoia.
			}
			$this->_conn = &$conn;

			// Get Tablenames used (Check if tables have already a prefix or not)
			$tableNamesArr = $this->_conn->MetaTables('TABLES');

			if ( array_lsearch(clubdata_mysqli::replaceDBprefix($this->tableNames['USERS'], DB_TABLEPREFIX), $tableNamesArr) === false )
			{
				$this->tableNames['USERS'] = 'Users';
				$this->tableNames['MEMBERS'] = 'Members';
				$this->tableNames['Log'] = 'Log';
				$this->tableNames['CONFIGURATION'] = 'Configuration';
			}
		}
	}


	/**
	 * Search the user in the database by his username and password.
	 *
	 * @param string
	 * @param string
	 * @return array		users information.
	 * @see _findById()
	 */
	function _findByUsername($username, $password) {
		$this->_connect();

		$adodbFetchMode = $GLOBALS['ADODB_FETCH_MODE'];
		$GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;

		$user = array();
		/*
		 $sql = sprintf('SELECT * FROM %s WHERE %s = %s AND %s = \'%s\'',
			$this->_options['usersTable'], $this->_options['usernameField'],
			$this->_conn->qstr($username, get_magic_quotes_gpc()),
			$this->_options['passwordField'],
			md5($password));
			*/
		$sql = sprintf("SELECT * FROM `" . $this->tableNames['USERS'] . "` WHERE Login = %s AND Password_pw = '%s'",
		$this->_conn->qstr($username, get_magic_quotes_gpc()),
		md5($password));
		$rs = $this->_conn->Execute($sql);
		if ($rs === false || $rs->EOF) {
			$sql = sprintf("SELECT * FROM `". $this->tableNames['MEMBERS'] . "` WHERE MemberID = %s AND LoginPassword_pw = '%s'",
			$this->_conn->qstr($username, get_magic_quotes_gpc()),
			md5($password));
			$rs = $this->_conn->Execute($sql);
			if ($rs === false || $rs->EOF) {
				//               print("ERROR: " . $this->_conn->ErrorMsg() . ":<BR>$sql");
				$this->_callback(AUTH_INVALID_USER /*,
				lang("Can't find the user's information in the database")*/);
				$user['loggedIn'] = false;
			} else {
				$user = $rs->fields;
				$user[$this->_options['userIdField']] = $rs->fields["MemberID"];
				$user[$this->_options['usernameField']] = $rs->fields["MemberID"];
				$user[$this->_options['passwordField']] = $rs->fields["Password_pw"];
				$user["MemberOnly"] = true;
				$user['loggedIn'] = true;
				$this->authLogEntry("LOGIN", $rs->fields["MemberID"]);
			}
		} else {
			$user = $rs->fields;
			$user["MemberOnly"] = false;
			$user['loggedIn'] = true;
			$this->authLogEntry("LOGIN", $rs->fields["Login"]);

			$user['PERSONAL_SETTINGS'] = json_decode($user['PersonalSettings_ro']);
		}

		$GLOBALS['ADODB_FETCH_MODE'] = $adodbFetchMode;
		return $user;
	}


	/**
	 * Search the user in the database by his user_id field.
	 *
	 * @param int			Of the user.
	 * @param bool $isMember true if login is a member, false if it is a user
	 * @return array		users information.
	 * @see _findByUsername()
	 */
	function _findById($userId, $isMember) {
		$this->_connect();

		$adodbFetchMode = $GLOBALS['ADODB_FETCH_MODE'];
		$GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;

		$user = array();

		$sql = sprintf('SELECT * FROM %s WHERE %s = %s',
		$isMember ? $this->tableNames['MEMBERS'] : $this->tableNames['USERS'],
		$this->_options['userIdField'],
		$userId);

		$rs = $this->_conn->Execute($sql);
		if ($rs === false || $rs->EOF) {
			$this->_callback(AUTH_INVALID_USER,
			lang("Can't find the user's information in the database"));
			$user['loggedIn'] = false;
		} else {
			$user = $rs->fields;
			$user["MemberOnly"] = $isMember;
			$user['loggedIn'] = true;

			if ( $isMember )
			{
    			$user[$this->_options['userIdField']] = $rs->fields["MemberID"];
    			$user[$this->_options['usernameField']] = $rs->fields["MemberID"];
    			$user[$this->_options['passwordField']] = $rs->fields["Password_pw"];
			}
			else
			{
			  $user['PERSONAL_SETTINGS'] = json_decode($user['PersonalSettings_ro']);
			}
		}

		$GLOBALS['ADODB_FETCH_MODE'] = $adodbFetchMode;
		return $user;
	}

	/**
	 * Search the user in the database by his user_id field.
	 *
	 * @param int			Of the user.
	 * @return array		users information.
	 * @see _findByUsername()
	 */
	function _getConfiguration($name) {
		$this->_connect();

		$adodbFetchMode = $GLOBALS['ADODB_FETCH_MODE'];
		$GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;

		$values = array();
		$sql = sprintf("SELECT * FROM `". $this->tableNames['CONFIGURATION'] . "` WHERE name = '%s'", $name);
		$rs = $this->_conn->Execute($sql);
		if ($rs === false || $rs->EOF) {
			echo "INVALID CONFIG ($name/". $this->tableNames['CONFIGURATION'] . ")";
			//            exit;
		} else {
			$values = $rs->fields;
		}

		$GLOBALS['ADODB_FETCH_MODE'] = $adodbFetchMode;
		return $values["value"];
	}

	/**
	 * Validates the current session.
	 *
	 * @return bool
	 */
	function _checkSession() {
		$identified = false;
		if (isset($this->user[$this->_options['userIdField']])) {
			$lastLogin = $this->user['::lastLogin::'];
			$time = time();
			if (($lastLogin + $this->_options['expires']) < $time) {
				if (!isset($_POST[$this->_options['usernameField']])) {
					$deltaSec = $time - $lastLogin;
					$deltaDay = (int)($deltaSec / (60 * 60 * 24 ));
					$deltaSec -= ($deltaDay * 3600*24);
					$deltaHour = (int)($deltaSec/ (3600));
					$deltaSec -= ($deltaHour * 3600);
					$deltaMin = (int)($deltaSec / 60);
					$deltaSec -= ($deltaMin* 60);
					$this->_callback(AUTH_EXPIRED /*, sprintf(lang('Your session expired after %sd %sh %sm %ss '),
					$deltaDay, $deltaHour, $deltaMin, $deltaSec)*/);
					$this->user['loggedIn'] = false;

				}
			} else {
				$this->user['::lastLogin::'] = $time;
				$this->user['loggedIn'] = true;
				$identified = true;
			}
		}
		return $identified;
	}

	function authLogEntry($task, $user)
	{
		global $db;

		$date = $this->_conn->DBTimeStamp(time());
		$host = $_SERVER["REMOTE_ADDR"];

		if ( $this->_getConfiguration("Clubdata_Version_DB") >= "V0.954 beta" )
		{
			$sqlCMD = "INSERT INTO `" . $this->tableNames['LOG'] . "` (user, date, host, task, parameter) VALUES ('$user', $date, '$host', '$task', '')";
			$this->_conn->Execute($sqlCMD) or print (__FILE__ . "(" . __LINE__ . "/$sqlCMD): ". $this->_conn->ErrorMsg());
			//print $sqlCMD;
		}
	}

}

?>
