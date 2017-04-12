<?php
namespace Clubdata\Navigation;

/**
 * @author Fernando Marquardt <fernando.marquardt@gmail.com>
 */
class Route {

    private $module;

    private $view;

    private $action;

    public function __construct($module, $view = null, $action = null) {
        $this->module = $module;
        $this->view = $view;
        $this->action = $action;
    }

    public function getUrl($params = array()) {
        $url = INDEX_PHP . "?mod={$this->module}";

        if ($this->view) {
            $url .= "&view={$this->view}";
        }

        if ($this->action) {
            $url .= "&Action={$this->action}";
        }

        foreach ($params as $name => $value) {
            $url .= "&{$name}=". urlencode($value);
        }

        return $url;
    }

    public function redirect($params = array()) {
        if (!headers_sent()) {
            header("Location: {$this->getUrl($params)}");
            exit();
        }
    }
}
