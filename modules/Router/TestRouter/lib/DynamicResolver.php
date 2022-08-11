<?php

require_once __DIR__ . "/RouterRoute.php";
require_once __DIR__ . "/RouterResolver.php";

class DynamicResolver extends RouterResolver {

    // returns the callback for the given route,
    // or null if not found
    public function get(string $path) {
        $path = Utils::clean_path($path);
        $route = $this->routes[$path] ?? NULL;
        if ($route) {
            $this->set_current($route);
            return $route->get_payload();
        } else {
            return NULL;
        }
    }

}
