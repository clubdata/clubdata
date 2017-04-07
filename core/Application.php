<?php
namespace Clubdata;

/**
 * @author Fernando Marquardt <fernando.marquardt@gmail.com>
 */
class Application {

    private $navigation;

    private $request;

    private static $instance;

    private function __construct() {
        $this->navigation = new Navigation();
        $this->request = new Request();
    }

    public function getNavigation() {
        return $this->navigation;
    }

    public function getRequest() {
        return $this->request;
    }

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new Application();
        }

        return self::$instance;
    }
}
