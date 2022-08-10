<?php

class StaticResolver {
    private $static = [];

    public function set(string $pattern, string $dir) {
        $pattern = Utils::clean_path($pattern);
        if (isset($this->static[$pattern])) {
            Actions::trigger("error", "Assets route already exists: $pattern");
        } else {
            $this->static[$pattern] = $dir;
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
        foreach ($this->static as $pattern => $dir) {

            if (strpos($path, $pattern) === 0) {
                Logging::debug("StaticResolver::get($path) SUCCESS");
                $file = $dir . "/" . substr($path, strlen($pattern));
                if (file_exists($file) && is_file($file)) {
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
