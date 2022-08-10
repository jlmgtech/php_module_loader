<?php

class DynamicResolver {

    public $routes = [];

    public function set(string $pattern, $callback) {
        $pattern = Utils::clean_path($pattern);
        if (isset($this->routes[$pattern])) {
            //Actions::trigger("error", "Route already exists: $pattern");
        } else {
            $this->routes[$pattern] = $callback;
        }
    }

    public function get(string $path) {
        $path = Utils::clean_path($path);
        return $this->routes[$path] ?? NULL;
    }

}
