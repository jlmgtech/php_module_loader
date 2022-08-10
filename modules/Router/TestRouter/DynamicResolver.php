<?php

require_once __DIR__ . "/RouterRoute.php";
require_once __DIR__ . "/RouterResolver.php";

class DynamicResolver extends RouterResolver {

    public function set(string $pattern, $callback) {
        $pattern = Utils::clean_path($pattern);
        if (isset($this->routes[$pattern])) {
            Actions::trigger("error", "Route already exists: $pattern");
        } else {
            $this->routes[$pattern] = new RouterRoute(
                Actions::current_module(),
                Actions::current_driver(),
                $callback
            );
        }
    }

    // returns the callback for the given route, or null if not found
    public function get(string $path) {
        $path = Utils::clean_path($path);
        $route = $this->routes[$path] ?? NULL;
        if ($route) {
            $this->set_current($route);
            return $route->get_callback();
        } else {
            return NULL;
        }
    }

}
