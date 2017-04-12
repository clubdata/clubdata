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

    public function addRoute($id, $module, $view = null, $action = null) {
        $this->routes[$id] = new Route($module, $view, $action);
    }

    public function getRoute($id) {
        return ($this->routes[$id]) ? $this->routes[$id] : null;
    }

    public function getUrl($id, $params = array()) {
        $route = $this->getRoute($id);

        if ($route) {
            return $route->getUrl($params);
        }

        return ''; // TODO Do something when the route is not found. Give a 404 link, maybe?
    }

    public function redirectTo($id, $params = array()) {
        if ($this->routes[$id]) {
            $this->routes[$id]->redirect($params);
        }
    }
}
