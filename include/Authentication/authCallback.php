<?php
/**
 * @package Authentication
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */
/*
 authCallback.php: callback function for user authentication
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

define('AUTH_CALLBACK', 'authCallback');

/**
 * Generates login forms and shows authentication messages to the user when needed.
 * This is where you can change the aspect of this forms. Also, if you want to log your
 * authentication trafic set $logging variable to true. and set the corresponding
 * variables according to http://www.php.net/error_log
 *
 * @version 2.4 pl3
 * @param $action		int, one of AUTH_NEED_LOGIN, AUTH_INVALID_USER, AUTH_EXPIRED,
 *                      AUTH_ACCESS_DENIED.
 * @param $message		string a message to show to the user.
 * @param $auth			object Auth.
 * @access public
 */
function auth_lang($text)
{
	global $lang;

	// require_once ("Language/authentication.php");

	/*
	 echo "TEXT: $text<BR>, ISSET: " . $lang[$text];
	 echo "LANG: $lang<BR><pre>";var_dump($lang);echo "</pre>";
	 */
	return isset($lang[$text]) ? $lang[$text] : $text;
}

function authCallback($action, $message = '', &$auth) {
	if(!isset($_GET)) {
		$_COOKIE = &$GLOBALS['HTTP_COOKIE_VARS'];
		$_ENV = &$GLOBALS['HTTP_ENV_VARS'];
		$_GET = &$GLOBALS['HTTP_GET_VARS'];
		$_POST = &$GLOBALS['HTTP_POST_VARS'];
		$_SERVER = &$GLOBALS['HTTP_SERVER_VARS'];
		$_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];
		$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
	}

	// Configuration.
	$logging = false;
	$logType = 0;
	$logDest = '';
	$logHeaders = '';

	?>
<!DOCTYPE public "-//w3c//dtd html 4.01 transitional//en"
		"http://www.w3.org/TR/html4/loose.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Authentication.</title>
<style type="text/css">
/*<![CDATA[*/
body {
	background-color: #FFF;
	font-family: Verdana, Helvetica, sans-serif;
	font-size: 10pt;
}

div,li,p,td,th {
	font-family: Verdana, Helvetica, sans-serif;
	font-size: 10pt;
}

.content {
	background-color: #EEE;
	border: 1px solid #CCC;
	width: 450px;
}

.content .title {
	background: white;
	border: 1px solid #CCC;
	color: #369;
	font-size: 12pt;
	font-weight: bold;
	padding: 10px;
}

.content th {
	background-color: #BDE;
	border: 1px solid #ABD;
	font-size: 12pt;
	font-wight: bold;
}

.content td {
	text-align: center;
}

.content .text {
	background-color: #DDD;
	border: 1px inset #CCC;
	font-size: 8pt;
	width: 200px;
}

.content .button {
	background-color: #DDD;
	border: 1px outset #CCC;
	font-size: 8pt;
	padding: 2px 4px 2px 4px;
}
/*]]>*/
</style>
</head>
<body class=invisible>
<table align="center" class="content" cellspacing="10">
<?php
$email = $auth->_getConfiguration("Email");

switch($action) {
	case AUTH_NEED_LOGIN:
		echo '<tr>
                <td class="title" colspan="2">' . auth_lang('A valid username/password pair is needed') . '</td>
             </tr>';
		echo '<form action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post">';
		echo '<tr><th>' . auth_lang("Username") . ':</th>';
		echo '<td><input class="text" name="' . $auth->_options['usernameField'] . '" type="text" maxlength="32"/></td>';
		echo '<tr><th>' . auth_lang("Password") . ':</th>';
		echo '<td><input class="text" name="' . $auth->_options['passwordField'] . '" type="password"/></td>';
		echo '<tr><td colspan="2">' . auth_lang("To access Clubdata you need to provide a valid username/password pair.") . '<BR>';
		echo auth_lang('If you lost your account info you can send an email to ');
		echo "<a href=\"mailto:/$email\">$email</a></td></tr>";
		echo '<tr><td colspan="2"><input class="button" type="submit" value="Login"/> <input class="button" onclick="history.go(-1);" type="button" value="Cancel"/></td></tr>';
		echo '</form>';
		break;

	case AUTH_INVALID_USER:
		echo '<tr>
                <td class="title" colspan="2">' . auth_lang('A valid username/password pair is needed') . '</td>
             </tr>';
		echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
		echo '<tr><th>' . auth_lang("Username") . ':</th>';
		echo '<td><input class="text" name="' . $auth->_options['usernameField'] . '" type="text" maxlength="32"/></td>';
		echo '<tr><th>' . auth_lang("Password") . ':</th>';
		echo '<td><input class="text" name="' . $auth->_options['passwordField'] . '" type="password"/></td>';
		echo '<tr><td colspan="2">' . auth_lang("Your account doesn\'t exists. Please correct your information.") . '<BR>';
		echo auth_lang('If you lost your account info you can send an email to ');
		echo "<a href=\"mailto:/$email\">$email</a></td></tr>";
		echo '<tr><td colspan="2"><input class="button" type="submit" value="Login"/> <input class="button" onclick="history.go(-1);" type="button" value="Cancel"/></td></tr>';
		echo '</form>';
		break;

	case AUTH_EXPIRED:
		echo '<tr>
                <td class="title" colspan="2">' . auth_lang('Session expired') . '</td>
             </tr>';
		echo '<tr><td colspan="2">' . auth_lang("Your session just expired. Please login again.") . '</td></tr>';
		echo '<form action="'. INDEX_PHP . '" method="post">';
		echo '<tr><th>' . auth_lang("Username") . ':</th>';
		echo '<td><input class="text" name="' . $auth->_options['usernameField'] . '" type="text" maxlength="32"/></td>';
		echo '<tr><th>' . auth_lang("Password") . ':</th>';
		echo '<td><input class="text" name="' . $auth->_options['passwordField'] . '" type="password"/></td>';
		echo '<tr><td colspan="2">' . auth_lang("To access Clubdata you need to provide a valid username/password pair.") . '<BR>';
		echo auth_lang('If you lost your account info you can send an email to ');
		echo "<a href='mailto:/$email'>$email</a></td></tr>";
		echo '<tr><td colspan="2"><input class="button" type="submit" value="Login"/> <input class="button" onclick="history.go(-1);" type="button" value="Cancel"/></td></tr>';
		echo '</form>';
		break;

	case AUTH_ACCESS_DENIED:
	default:
		echo '<tr>
                <td class="title" colspan="2">' . auth_lang('A valid username/password pair is needed') . '</td>
             </tr>';
		echo '<tr><td>Your don\'t have access to this zone. Please leave it now!.</td>';
		echo '<tr><td><input class="button" onclick="history.go(-1);" type="button" value="Exit"/></td></tr>';
		break;
}
?>

</table>
</BODY>
</html>
<?php

if($logging) {
	error_log("AUTH MESSAGE: $message", logType, $logDest,
	$logHeaders);
}
die();
}

?>
