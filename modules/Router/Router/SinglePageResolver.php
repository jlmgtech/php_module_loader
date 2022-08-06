<?php

require_once __DIR__ . "/" . "helpers.php";

class SinglePageResolver {

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
        foreach ($this->routes as $pattern => $callback) {
            if (startswith($path, $pattern)) {
                return $callback;
            }
        }
        return NULL;
    }

}
