<?php

require_once __DIR__ . "/" . "DynamicResolver.php";
require_once __DIR__ . "/" . "SinglePageResolver.php";
require_once __DIR__ . "/" . "StaticResolver.php";
require_once __DIR__ . "/" . "helpers.php";

class Router {

    private static $get = NULL;
    private static $post = NULL;
    private static $static = NULL;
    private static $spas = NULL;

    public static function post(string $path, ...$callbacks) {
        $callback = compose_callbacks($callbacks);
        self::$post->set($path, $callback);
    }

    public static function get(string $path, ...$callbacks) {
        $callback = compose_callbacks($callbacks);
        self::$get->set($path, $callback);
    }

    public static function spa(string $path, string $file) {
        // serves one file for all requests with path as a prefix
    }

    public static function assets(string $path, string $dir) {
        self::$static->set($path, $dir);
    }

    public static function not_found() {
        header("HTTP/1.1 404 Not Found");
        header("Content-Type: text/plain");
        echo "404 Not Found";
    }

    public static function not_allowed() {
        header("HTTP/1.1 405 Method Not Allowed");
        header("Content-Type: text/plain");
        echo "405 Method Not Allowed";
    }

    private static function err(string $msg) {
        $stderr = fopen("php://stderr", "w");
        fwrite($stderr, $msg . "\n");
        fclose($stderr);
    }

    public static function render_static(string $file) {
        if (!file_exists($file)) {
            self::err("File not found in static directory $dir for $path");
            return self::not_found();
        }
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext === "php" || $ext === "inc") {
            self::err("File is a PHP file in static directory $dir for $path");
            return self::not_found();
        }
        $mime = mime_content_type($file);
        header("Content-Type: $mime");
        readfile($file);
        return;
    }

    public static function server_error() {
        header("HTTP/1.1 500 Internal Server Error");
        header("Content-Type: text/plain");
        echo "500 Internal Server Error";
    }

    public static function render() {

        self::$static = new StaticResolver();
        self::$get    = new DynamicResolver();
        self::$post   = new DynamicResolver();
        self::$spas   = new SinglePageResolver();
        $notfound = function() {
            Router::not_found();
        };
        $notallowed = function() {
            Router::not_allowed();
        };

        do_action("register_routes");

        $path = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method === "GET") {

            $callback = self::$get->get($path) ??
                self::$static->get($path) ??
                self::$spas->get($path) ??
                $notfound;

        } else if ($method === "POST") {

            $callback = self::$post->get($path) ?? $notfound;

        } else {

            $callback = $notallowed;

        }

        $callback();
    }

};
