<?php

require_once __DIR__ . "/" . "helpers.php";

class StaticResolver {
    private $static = [];

    public function set(string $pattern, string $dir) {
        $pattern = clean_path_string($pattern);
        if (isset($this->static[$pattern])) {
            do_action("error", "Assets route already exists: $pattern");
        } else {
            $this->static[$pattern] = $dir;
        }
    }

    public function get(string $path) {
        $path = clean_path_string($path);
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
