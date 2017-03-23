<?php /* Authentication functions */
/**
 * @package Authentication
 * @subpackage General
 * @author Franz Domes <franz.domes@gmx.de>
 * @version $Revision: 1.3 $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2009, Franz Domes
 */

/**
 *
 */

define("ADMINISTRATOR", "0");
define("UPDATE", "1");
define("INSERT", "2");
define("VIEW", "3");
define("DELETE", "4");
define("ALL", "5");

/*
 define("AUTH_NEED_LOGIN", 0);
 define("AUTH_INVALID_USER", 1);
 define("AUTH_EXPIRED",2);
 define("AUTH_ACCESS_DENIED", 3);
 */

require_once('include/Authentication/Auth.php');
// require_once("Language/authentication.php");

$options = array(
    'usersTable' => '###_Users',
    'userIdField' => 'id',
    'usernameField' => 'Login',
    'passwordField' => 'PW_Login',
);


function random() {
    srand((double) microtime() * 1000000);
    return rand();
}


function cryptPassword($password) {
    return (empty($password)) ? "** EMPTY PASSWORD **" : md5($password);
}

function getClubUserInfo($field, $user = "") {
    global $auth;

    if ($auth->isIdentified === true) {
        return($auth->user[$field]);
    } else {
        $auth->forceLogin();
    }

    return($auth->user[$field]);
}

/**
 * isLoggedIn()
 *
 * @return true if user or member is logged in, false if not
 */
function isLoggedIn() {
    global $auth;

    $retVal = isset($auth->user['loggedIn']) && $auth->user['loggedIn'] == 1;

    return ($retVal);
}

/**
 * isMember()
 *
 * @return true if a member is logged in, false if a user is logged in
 */
function isMember() {
    global $auth;

    $retVal = isset($auth->user["MemberOnly"]) && $auth->user['MemberOnly'] == 1;

    return ($retVal);
}

/*
 * $userRight might be one of VIEW, INSERT, UPDATE, DELETE (as defined on top of this page) or a string
 *            In case of a string, it is taken litterally to look up the appropriate user rights
 */
function getUserType($userRight, $section = "ALL", $user = "") {
    global $auth;

    if (!isLoggedIn()) {
        return false;
    }

    debug('AUTH', "[getUserType] BERECHT: $userRight, SECTION: $section, USER $user, MEMBER {$auth->user['MemberOnly']}");

    if ($auth->user["Admin_yn"]) {
        return true;
    }

    if ($userRight != ADMINISTRATOR && $auth->user["MemberOnly"] == true) {
        switch ($section) {
            case "Fees":
            case "Payments":
                switch ($userRight) {
                    case ADMINISTRATOR:
                    case ALL:
                    case UPDATE:
                    case INSERT:
                    case DELETE:
                        return false;
                    default:
                        return true;
                }
                break;
            default:
                switch ($userRight) {
                    case ADMINISTRATOR:
                    case ALL:
                    case INSERT:
                    case DELETE:
                        return false;
                    default:
                        return true;
                }
                break;
        }
    }

    // Initial empty rights array
    $rightsArr = array();

    switch ($userRight) {
        case ADMINISTRATOR:
            $rightsArr[] = "Admin_yn";
            break;
        case ALL:
            $rightsArr[] = "View". $section ."_yn";
            $rightsArr[] = "ViewAll_yn";
            $rightsArr[] = "Delete". $section ."_yn";
            $rightsArr[] = "DeleteAll_yn";
            $rightsArr[] = "Update". $section ."_yn";
            $rightsArr[] = "UpdateAll_yn";
            $rightsArr[] = "Insert". $section ."_yn";
            $rightsArr[] = "InsertAll_yn";
            break;
        case UPDATE:
            $rightsArr[] = "Update". $section ."_yn";
            $rightsArr[] = "UpdateAll_yn";
            break;
        case INSERT:
            $rightsArr[] = "Insert". $section ."_yn";
            $rightsArr[] = "InsertAll_yn";
            break;
        case DELETE:
            $rightsArr[] = "Delete". $section ."_yn";
            $rightsArr[] = "DeleteAll_yn";
            break;
        case VIEW:
            $rightsArr[] = "View". $section ."_yn";
            $rightsArr[] = "ViewAll_yn";
            break;
        default:
            $rightsArr[] = $userRight . $section ."_yn";
            $rightsArr[] = $userRight ."All_yn";
            break;
    }

    foreach ($rightsArr as $right) {
        debug('AUTH', "[getUserType] SECTION: $section, RIGHT: $right");

        if (isset($auth->user[$right]) && $auth->user[$right]) {
            debug('AUTH', "[getUserType] RETURN TRUE");
            return true;
        }
    }

    debug('AUTH', "[getUserType] RETURN FALSE");
    return false;
}
