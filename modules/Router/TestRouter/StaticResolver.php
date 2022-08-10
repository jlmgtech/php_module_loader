<?php

require_once __DIR__ . "/RouterRoute.php";
require_once __DIR__ . "/RouterResolver.php";

class StaticResolver extends RouterResolver {

    public function set(string $pattern, $dir) {
        $pattern = Utils::clean_path($pattern);
        if (isset($this->routes[$pattern])) {
            Actions::trigger("error", "Assets route already exists: $pattern");
        } else {
            $this->routes[$pattern] = new RouterRoute(
                Actions::current_module(),
                Actions::current_driver(),
                $dir
            );
        }
    }

    public function get(string $path) {
        // For security reasons, if there's two dots in a row, run away!
        // This is to prevent directory traversal attacks.
        // It may not be a perfect solution, but it's pretty decent (and fast)
        // TODO: Consider making this configurable?
        if (strpos($path, ".."))
            return NULL;

        // We only want to serve files, not directories.
        // This is to prevent collisions with single page resolver.
        if (!Utils::path_has_extension($path, "*"))
            return NULL;

        $path = Utils::clean_path($path);
        foreach ($this->routes as $pattern => $route) {
            // bit of a hack here... consider a tagged union here instead of
            // get_callback... OR, perhaps the callback just returns the path?
            // Maybe just rename to payload...
            $dir = $route->get_callback();

            if (strpos($path, $pattern) === 0) {
                $file = $dir . "/" . substr($path, strlen($pattern));
                if (file_exists($file) && is_file($file)) {
                    Logging::debug("StaticResolver::get($path) SUCCESS");
                    $this->set_current($route);
                    return function() use ($file) {
                        Router::render_static($file);
                    };
                } else {
                    Logging::debug("StaticResolver::get($file) is not a file");
                }
            }

        }
        Logging::debug("StaticResolver::get($path) not found");
        return NULL;
    }
}
