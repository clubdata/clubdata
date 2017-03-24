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
 * @author Julio César Carrascal Urquijo <jcesar@phreaker.net>
 * @author Franz Domes <franz.domes@gmx.de>
 * @version 2.0
 * @copyright Copyright (c) 2009, Franz Domes, Julio César Carrascal Urquijo <jcesar@phreaker.net>
 */


/* Auth.php - User authentication component.
 * Copyright (C) 2002 Julio César Carrascal Urquijo <jcesar@phreaker.net>
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
define('AUTH_NEED_LOGIN', -1);

/**
 * The username/password pair is invalid.
 *
 * @access public
 */
define('AUTH_INVALID_USER', -2);

/**
 * The session has expired.
 *
 * @access public
 */
define('AUTH_EXPIRED', -3);

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
define('AUTH_CACHE', 2); /// Cache

/**
 * Do not allow anyone to cache the page.
 *
 * @access public
 */
define('AUTH_NO_CACHE', 1);


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
     */
    public $user = array();

    /**
     * If the user has been identified
     *
     * @var boolean
     */
    public $isIdentified = false;

    /**
     * Wich cache level to use.
     *
     * @var integer
     */
    //     var $cacheLevel = AUTH_CACHE;
    public $cacheLevel = AUTH_NO_CACHE;

    // Database connection information for ADOdb. Consult the ADOdb readme.html
    // file. This information is for the ADONewConnection() function and the
    // ADOConnection::Connect() method.

    /**
     * Database driver. Example 'mysql', 'mssql', 'oci8'...
     *
     * @var string
     */
    public $dbdriver = DB_TYPE;

    /**
     * Database hostname server.
     *
     * @var string
     */
    public $hostname = DB_HOST;

    /**
     * Database username
     *
     * @var string
     */
    public $username = DB_USER;

    /**
     * Database password
     *
     * @var string
     */
    public $password = DB_PASSWD;

    /**
     * Database.
     *
     * @var string
     */
    public $database = DB_NAME;

    /**
     * Holds an ADOConnection instance.
     *
     * @var object ADOConnection
     */
    protected $conn = null;

    /**
     * This array hold database configuration and execution options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Flag that states if the session has already started.
     *
     * @var bool
     */
    protected $sessionStarted = false;

    /**
     * Tablenames used by Auth
     *
     * @var array
     */
    private $tableNames = array(
        'USERS'         => '###_Users',
        'MEMBERS'       => '###_Members',
        'CONFIGURATION' => '###_Configuration',
        'LOG'           => '###_Log'
    );

    /**
     * Constructor.
     *
     * @param array key => val of configuration parameters.
     * @access public
     */
    public function __construct($options = null) {
        //ob_start();

        // Database Squema. Change this so it reflects the names of tables and columns
        // on your site.
        //$this->options['usersTable'] = 'users';
        //$this->options['userIdField'] = 'id';
        //$this->options['usernameField'] = 'username';
        //$this->options['passwordField'] = 'password';

        // This settings affect the way the session is handled by PHP. See
        // http://www.php.net/session for an explanation of each one.
        $this->options['cookieLifetime'] = 2592000; // one month.
        $this->options['cookiePath'] = '/';
        $this->options['cookieDomain'] = null;
        $this->options['sessionName'] = null; // 'AUTHSESSID' for example

        // This settings affects the way the session works. 'sessionVariable' is the name
        // of the registered variable ($_SESSION['user'] by default).'expires'
        // is the time that a user has to refresh the session. 'forceRedirect' tells the
        // script to redirect the request to the page 'redirect'.
        $this->options['sessionVariable'] = 'user';
        $this->options['expires'] = 7200; // two hour.
        $this->options['forceRedirect'] = false;
        $this->options['redirect'] = $_SERVER['PHP_SELF'];

        $this->options = array_merge($this->options, (array) $options);
    }


    /**
     * Initialize the session.
     * Use this method only if loggin in to the current page is optional but you will
     * want to have access to the user's information if he has already been identified.
     */
    public function startSession() {
        if ($this->sessionStarted) {
            return;
        }

        $this->sessionStarted = true;

        $GLOBALS[$this->options['sessionVariable']] = null; // paranoia.

        if ($this->cacheLevel == AUTH_CACHE) {
            session_cache_limiter('private, must-revalidate');
            header('Cache-Control: private, must-revalidate');
        } else {
            session_cache_limiter('no-cache, must-revalidate');
            header('Cache-Control: no-cache, must-revalidate');
        }

        // Start the session.
        if (preg_match('!^\w+$!', $this->options['sessionName'])) {
            session_name($this->options['sessionName']);
        }

        session_set_cookie_params(
            $this->options['cookieLifetime'],
            $this->options['cookiePath'],
            $this->options['cookieDomain']
        );
        session_start();

        if (!isset($_SESSION[$this->options['sessionVariable']])) {
            $_SESSION[$this->options['sessionVariable']] = array();
        }

        $this->user = &$_SESSION[$this->options['sessionVariable']];

        // In case the user has already identified.
        $this->isIdentified = $this->checkSession();
    }


    /**
     * Force the user to identify him self.
     */
    public function forceLogin() {
        $this->startSession();

        if (!$this->isIdentified) {
            if (isset($_POST[$this->options['usernameField']])) {
                $user = $this->findByUsername(
                    $_POST[$this->options['usernameField']],
                    $_POST[$this->options['passwordField']]
                );

                // Update session.
                $user['::lastLogin::'] = time();
                $user['loggedIn'] = $this->isIdentified;

                // Save session.
                $_SESSION[$this->options['sessionVariable']] = $user;
                $this->user = &$_SESSION[$this->options['sessionVariable']];

                // Redirect so the username/password doesn't get saved in browser's post history.
                if (null !== $this->options['redirect']) {
                    header('Location: '.$this->options['redirect']);
                    exit();
                }
            } else {
                $this->callback(AUTH_NEED_LOGIN);
                $user['loggedIn'] = false;
            }
        }

        // Redirect if requested.
        if ($this->options['forceRedirect'] && null !== $this->options['redirect']) {
            header('Location: '.$this->options['redirect']);
            exit();
        }
    }

    /**
     * Delete all session information and logout the user.
     */
    public function logout() {
        $this->forceLogin();

        $GLOBALS[$this->options['sessionVariable']] = null; // paranoia.
        unset($_SESSION[$this->options['sessionVariable']]); // more paranoia.
        @session_destroy();
        $this->user = array();
    }

    /**
     * Updates the user's information from the database.
     * The user must be identified already. Usefull if you just updated the database and
     * you need to update your session variable.
     */
    public function refreshInfo() {
        if (!$this->isIdentified) {
            $this->forceLogin();
        }

        $userId = $this->user[$this->options['userIdField']];
        $lastLogin = $this->user['::lastLogin::'];  // Save Session timeout
        $_SESSION[$this->options['sessionVariable']] =
        $this->findById($userId, $this->user["MemberOnly"]);
        $this->user = &$_SESSION[$this->options['sessionVariable']];
        $this->user['::lastLogin::'] = $lastLogin;  // Set session timeout
        $this->user['loggedIn'] = true;
    }

    /**
     * Just calls the callback function and dies.
     *
     * @param int What action should the callback function take. Has to be one
     *            of AUTH_NEED_LOGIN, AUTH_INVALID_USER, AUTH_ACCESS_DENIED or
     *            AUTH_EXPIRED.
     * @param string message to show to the user, optional.
     */
    protected function callback($action, $message = '') {
        // include the default callback function.
//		if (!defined('AUTH_CALLBACK'))
//		include_once(dirname(__FILE__).'/authCallback.php');
//		call_user_func(AUTH_CALLBACK, $action, $message, &$this);
//		exit();
    }

    /**
     * Connect to the database only if necesary.
     */
    protected function connect() {
        if ($this->conn === null) {
            $conn = ADONewConnection($this->dbdriver);

            if ($conn === null) {
                trigger_error("$this->dbdriver is not supported", E_USER_ERROR);
                die();
            }

            $res = $conn->PConnect($this->hostname, $this->username, $this->password, $this->database);

            if ($res === false) {
                trigger_error("Can't connect to $this->hostname", E_USER_ERROR);
                die(); // paranoia.
            }

            $this->conn = &$conn;

            // Get Tablenames used (Check if tables have already a prefix or not)
            $tableNamesArr = $this->conn->MetaTables('TABLES');
            $tableName = clubdata_mysqli::replaceDBprefix($this->tableNames['USERS'], DB_TABLEPREFIX);

            if (array_search(strtolower($tableName), array_map('strtolower', $tableNamesArr)) === false) {
                $this->tableNames['USERS']         = 'Users';
                $this->tableNames['MEMBERS']       = 'Members';
                $this->tableNames['Log']           = 'Log';
                $this->tableNames['CONFIGURATION'] = 'Configuration';
            }
        }
    }


    /**
     * Search the user in the database by his username and password.
     *
     * @param string
     * @param string
     * @return array users information.
     * @see findById()
     */
    protected function findByUsername($username, $password) {
        $this->connect();

        $adodbFetchMode = $GLOBALS['ADODB_FETCH_MODE'];
        $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;

        $user = array();

        $sql = sprintf(
            "SELECT * FROM `" . $this->tableNames['USERS'] . "` WHERE Login = %s AND Password_pw = '%s'",
            $this->conn->qstr($username, get_magic_quotes_gpc()),
            md5($password)
        );

        $rs = $this->conn->Execute($sql);

        if ($rs === false || $rs->EOF) {
            $sql = sprintf(
                "SELECT * FROM `". $this->tableNames['MEMBERS'] . "` WHERE MemberID = %s AND LoginPassword_pw = '%s'",
                $this->conn->qstr($username, get_magic_quotes_gpc()),
                md5($password)
            );

            $rs = $this->conn->Execute($sql);

            if ($rs === false || $rs->EOF) {
                $this->callback(AUTH_INVALID_USER);
                $user['loggedIn'] = false;
            } else {
                $user = $rs->fields;
                $user[$this->options['userIdField']] = $rs->fields["MemberID"];
                $user[$this->options['usernameField']] = $rs->fields["MemberID"];
                $user[$this->options['passwordField']] = $rs->fields["Password_pw"];
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
     * @param int Of the user.
     * @param bool $isMember true if login is a member, false if it is a user
     * @return array users information.
     * @see findByUsername()
     */
    protected function findById($userId, $isMember) {
        $this->connect();

        $adodbFetchMode = $GLOBALS['ADODB_FETCH_MODE'];
        $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;

        $user = array();

        $sql = sprintf(
            'SELECT * FROM %s WHERE %s = %s',
            $isMember ? $this->tableNames['MEMBERS'] : $this->tableNames['USERS'],
            $this->options['userIdField'],
            $userId
        );

        $rs = $this->conn->Execute($sql);

        if ($rs === false || $rs->EOF) {
            $this->callback(AUTH_INVALID_USER, lang("Can't find the user's information in the database"));
            $user['loggedIn'] = false;
        } else {
            $user = $rs->fields;
            $user["MemberOnly"] = $isMember;
            $user['loggedIn'] = true;

            if ($isMember) {
                $user[$this->options['userIdField']] = $rs->fields["MemberID"];
                $user[$this->options['usernameField']] = $rs->fields["MemberID"];
                $user[$this->options['passwordField']] = $rs->fields["Password_pw"];
            } else {
                $user['PERSONAL_SETTINGS'] = json_decode($user['PersonalSettings_ro']);
            }
        }

        $GLOBALS['ADODB_FETCH_MODE'] = $adodbFetchMode;
        return $user;
    }

    /**
     * Search the user in the database by his user_id field.
     *
     * @param int Of the user.
     * @return array users information.
     * @see findByUsername()
     */
    protected function getConfiguration($name) {
        $this->connect();

        $adodbFetchMode = $GLOBALS['ADODB_FETCH_MODE'];
        $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;

        $values = array();
        $sql = sprintf("SELECT * FROM `". $this->tableNames['CONFIGURATION'] . "` WHERE name = '%s'", $name);
        $rs = $this->conn->Execute($sql);

        if ($rs === false || $rs->EOF) {
            echo "INVALID CONFIG ($name/". $this->tableNames['CONFIGURATION'] . ")";
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
    protected function checkSession() {
        $identified = false;

        if (isset($this->user[$this->options['userIdField']])) {
            $lastLogin = $this->user['::lastLogin::'];
            $time = time();

            if (($lastLogin + $this->options['expires']) < $time) {
                if (!isset($_POST[$this->options['usernameField']])) {
                    $deltaSec = $time - $lastLogin;
                    $deltaDay = (int) ($deltaSec / (60 * 60 * 24 ));
                    $deltaSec -= ($deltaDay * 3600*24);
                    $deltaHour = (int) ($deltaSec/ (3600));
                    $deltaSec -= ($deltaHour * 3600);
                    $deltaMin = (int) ($deltaSec / 60);
                    $deltaSec -= ($deltaMin* 60);
                    $this->callback(AUTH_EXPIRED /*, sprintf(lang('Your session expired after %sd %sh %sm %ss '),
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

    protected function authLogEntry($task, $user) {
        global $db;

        $date = $this->conn->DBTimeStamp(time());
        $host = $_SERVER["REMOTE_ADDR"];

        if ($this->getConfiguration("Clubdata_Version_DB") >= "V0.954 beta") {
            $sqlCMD = "INSERT INTO `" . $this->tableNames['LOG'] . "` (user, date, host, task, parameter) VALUES ('$user', $date, '$host', '$task', '')";
            $this->conn->Execute($sqlCMD) or print (__FILE__ . "(" . __LINE__ . "/$sqlCMD): ". $this->conn->ErrorMsg());
        }
    }
}
