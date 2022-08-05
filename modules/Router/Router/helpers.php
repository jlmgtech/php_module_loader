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
