<?php

require_once __DIR__ . "/RouterRoute.php";
require_once __DIR__ . "/RouterResolver.php";

class SinglePageResolver extends RouterResolver {

    public $routes = [];

    public function set(string $pattern, $file) {
        $pattern = Utils::clean_path($pattern);
        if (isset($this->routes[$pattern])) {
            Actions::trigger("error", "SPA Route already exists: $pattern");
        } else {
            $this->routes[$pattern] = new RouterRoute(
                Actions::current_module(),
                Actions::current_driver(),
                $file
            );
        }
    }

    public function get(string $path) {
        $path = Utils::clean_path($path);

        // single page resolver only acts on directories
        // this is to prevent collisions with static resolver
        if (Utils::path_has_extension($path, "*"))
            return NULL;

        foreach ($this->routes as $pattern => $file) {

            if (strpos($path, $pattern) === 0) {
                Logging::debug("SinglePageResolver::get($path) SUCCESS");
                $this->set_current($route);
                return function() use ($pattern, $file, $path) {
                    Router::render_static($file);
                };
            }

        }
        Logging::debug("SPA Resolver::get($path) not found");
        return NULL;
    }
}
