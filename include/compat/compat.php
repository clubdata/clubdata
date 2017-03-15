<?php
if ( !function_exists('json_decode') ){
    function json_decode($content, $assoc=false){
                require_once 'include/compat/JSON/JSON.php';
                if ( $assoc ){
                    $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
                    $json = new Services_JSON;
                }
        return $json->decode($content);
    }
}

if ( !function_exists('json_encode') ){
    function json_encode($content){
                require_once 'include/compat/JSON/JSON.php';
                $json = new Services_JSON;

        return $json->encode($content);
    }
}

// Fix for removed Session functions
function fix_session_register(){
	function session_register(){
		$args = func_get_args();
		foreach ($args as $key){
			$_SESSION[$key]=$GLOBALS[$key];
		}
	}
	function session_is_registered($key){
		return isset($_SESSION[$key]);
	}
	function session_unregister($key){
		unset($_SESSION[$key]);
	}
}
if (!function_exists('session_register')) fix_session_register();

?>
