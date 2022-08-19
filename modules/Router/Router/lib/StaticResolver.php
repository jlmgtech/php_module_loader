<?php

require_once __DIR__ . "/RouterRoute.php";
require_once __DIR__ . "/RouterResolver.php";

class StaticResolver extends RouterResolver {

    public function get(string $path) {
        // mitigate directory traversal attacks
        if (strpos($path, ".."))
            return NULL;

        // serve only files with extensions!
        if (!Utils::path_has_extension($path, "*"))
            return NULL;

        $path = Utils::clean_path($path);
        foreach ($this->routes as $pattern => $route) {
            $dir = $route->get_payload();
            // strpos can't handle empty strings, hence the extra checks...
            if ($pattern === "" && $path !== "")
                continue;
            if ($path === $pattern || strpos($path, $pattern) === 0) {
                $file = $dir . "/" . substr($path, strlen($pattern));
                if (file_exists($file) && is_file($file)) {
                    $this->set_current($route);
                    return function() use ($file) {
                        Router::render_static($file);
                    };
                }
            }

        }
        return NULL;
    }
}
