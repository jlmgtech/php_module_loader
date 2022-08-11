<?php

require_once __DIR__ . "/RouterRoute.php";
require_once __DIR__ . "/RouterResolver.php";

class SinglePageResolver extends RouterResolver {

    public function get(string $path) {
        $path = Utils::clean_path($path);

        // files with extensions only:
        if (Utils::path_has_extension($path, "*"))
            return NULL;

        foreach ($this->routes as $pattern => $route) {

            $file = $route->get_payload();
            if (strpos($path, $pattern) === 0) {
                $this->set_current($route);
                return function() use ($pattern, $file, $path) {
                    Router::render_static($file);
                };
            }

        }
        return NULL;
    }
}
