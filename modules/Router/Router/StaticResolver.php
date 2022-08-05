<?php

require_once __DIR__ . "/" . "helpers.php";

class StaticResolver {
    private $static = [];

    public function set(string $path, string $dir) {
        $this->static[clean_path_string($path)] = $dir;
    }

    public function get(string $path): string {
        $path = clean_path_string($path);
        if (isset($this->static[$path])) {
            return $this->static[$path];
        }
        return "";
    }
}
