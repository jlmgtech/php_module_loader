<?php


class RouterResolver {

    public $routes = [];
    public $current = NULL;

    protected function set_current(RouterRoute $route) {
        $this->current = $route;
    }
    public function get_current() {
        return $this->current;
    }

    public function set(string $pattern, $payload) {
        $pattern = Utils::clean_path($pattern);
        if (isset($this->routes[$pattern])) {
            Actions::trigger("error", "RouteCollision", get_class($this), "Route already exists: $pattern");
        } else {
            $this->routes[$pattern] = new RouterRoute(
                Actions::current_module(),
                Actions::current_driver(),
                $payload
            );
        }
    }

    public function get(string $path) {
        throw new Exception(__METHOD__ . " not implemented");
    }

}
