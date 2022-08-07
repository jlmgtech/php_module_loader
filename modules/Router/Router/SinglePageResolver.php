<?php

require_once __DIR__ . "/" . "helpers.php";

class SinglePageResolver {

    public $routes = [];

    public function set(string $pattern, string $file) {
        $pattern = clean_path_string($pattern);
        if (isset($this->routes[$pattern])) {
            do_action("error", "SPA Route already exists: $pattern");
        } else {
            $this->routes[$pattern] = $file;
        }
    }

    public function get(string $path) {
        $path = clean_path_string($path);
        foreach ($this->routes as $pattern => $file) {

            if (strpos($path, $pattern) === 0) {
                Logging::debug("SinglePageResolver::get($path) SUCCESS");
                return function() use ($pattern, $file, $path) {
                    Router::render_static($file);
                };
            }

        }
        Logging::debug("SPA Resolver::get($path) not found");
        return NULL;
    }
}
