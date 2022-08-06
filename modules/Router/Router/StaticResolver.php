<?php

require_once __DIR__ . "/" . "helpers.php";

class StaticResolver {
    private $static = [];

    public function set(string $path, string $dir) {
        $this->static[clean_path_string($path)] = $dir;
    }

    public function get(string $path) {
        $path = clean_path_string($path);
        foreach ($this->static as $pattern => $dir) {

            if (strpos($path, $pattern) === 0) {
                return function() use ($pattern, $dir, $path) {
                    $file = $dir . "/" . substr($path, strlen($pattern));
                    Router::render_static($file);
                };

            }

        }
        return $notfound;
    }
}
