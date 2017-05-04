<?php
namespace Clubdata;

use Clubdata\Navigation\Route;

/**
 * @author Fernando Marquardt <fernando.marquardt@gmail.com>
 */
class Navigation {

    /**
     * @var Route[]
     */
    protected $routes = array();

    public function addRoute($name, $module, $view = null, $action = null) {
        $this->routes[$name] = new Route($module, $view, $action);
    }

    public function getRoute($name) {
        return ($this->routes[$name]) ? $this->routes[$name] : null;
    }

    public function getUrl($name, $params = array()) {
        $route = $this->getRoute($name);

        if ($route) {
            return $route->getUrl($params);
        }

        return ''; // TODO Do something when the route is not found. Give a 404 link, maybe?
    }

    public function redirectTo($name, $params = array()) {
        if ($this->routes[$name]) {
            $this->routes[$name]->redirect($params);
        }
    }
}
