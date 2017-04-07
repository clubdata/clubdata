<?php
namespace Clubdata;

/**
 * @author Fernando Marquardt <fernando.marquardt@gmail.com>
 */
class Request {

    public function getQueryParam($name, $default = null) {
        return (isset($_GET[$name])) ? $_GET['name'] : $default;
    }
}
