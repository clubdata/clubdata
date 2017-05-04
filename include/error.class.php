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
define("NONE",  0);
define("INFO",  1);
define("WARN",  2);
define("ERROR", 3);
define("FATAL", 4);

/**
 * @package Clubdata
 */
class ErrorHandler {

    var $errorMsg = array();
    var $errorLvl =  NONE;
    var $errorLvlTxt = array();

    function ErrorHandler()
    {
        $this->errorLvlTxt[NONE]  = "OK";
        $this->errorLvlTxt[INFO]  = "info";
        $this->errorLvlTxt[WARN]  = "warning";
        $this->errorLvlTxt[ERROR] = "error";
        $this->errorLvlTxt[FATAL] = "fatal";
    }

    function setError()
    {
        $paramArr = func_get_args();
        $this->errorMsg = array_merge($this->errorMsg, $paramArr);
//         print("<PRE>"); print_r($paramArr); print_r($this->errorMsg); print("</PRE>");
        $this->errorLvl = ($this->errorLvl < ERROR) ? ERROR : $this->errorLvl;
    }

    function setFatal()
    {
        $paramArr = func_get_args();
        $this->errorMsg = array_merge($this->errorMsg, $paramArr);
        $this->errorLvl = ($this->errorLvl < FATAL) ? FATAL : $this->errorLvl;

        debug_backtr('MAIN');

    }
    function setWarn()
    {
        $paramArr = func_get_args();
        $this->errorMsg = array_merge($this->errorMsg, $paramArr);
        $this->errorLvl = ($this->errorLvl < WARN) ?  WARN: $this->errorLvl;
    }
    function setInfo()
    {
        $paramArr = func_get_args();
        $this->errorMsg = array_merge($this->errorMsg, $paramArr);
        $this->errorLvl = ($this->errorLvl < INFO) ? INFO : $this->errorLvl;
    }

    function hasMessage()
    {
        return ( $this->errorLvl > NONE );
    }

    function getMessages()
    {
        return ( $this->errorMsg );
    }

    function resetError()
    {
        $this->errorMsg = array();
        $this->errorLvl =  NONE;
    }

    function getErrorLvlTxt()
    {
        return $this->errorLvlTxt[$this->errorLvl];
    }

    function getErrorLevel()
    {
        return $this->errorLvl;
    }
}

?>
