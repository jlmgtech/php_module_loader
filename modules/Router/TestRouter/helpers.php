<?php

function clean_path_string(string $path): string {
     $result = trim(preg_replace('/\/+/', '/', $path), "/");
     return $result ?: "/";
}

function compose_callbacks(array $callbacks): callable {
    return function (...$args) use ($callbacks) {
        foreach ($callbacks as $callback) {
            if ($callback(...$args) === false)
                return false;
        }
        return true;
    };
}

function path_has_extension(string $path): bool {
    return strpos(basename($path), ".") !== false;
}

function apply_decorators($fns) {
    $output = array_pop($fns);
    while (count($fns)) {
        $fn = array_pop($fns);
        $output = $fn($output);
    }
    return $output;
}
