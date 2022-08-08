<?php

require_once __DIR__ . "/" . "helpers.php";

class DynamicResolver {

    public $routes = [];

    public function set(string $pattern, $callback) {
        $pattern = clean_path_string($pattern);
        if (isset($this->routes[$pattern])) {
            //do_action("error", "Route already exists: $pattern");
        } else {
            $this->routes[$pattern] = $callback;
        }
    }

    public function get(string $path) {
        $path = clean_path_string($path);
        return $this->routes[$path] ?? NULL;
    }

}
