<?php

require_once __DIR__ . "/" . "DynamicResolver.php";
require_once __DIR__ . "/" . "SinglePageResolver.php";
require_once __DIR__ . "/" . "StaticResolver.php";
require_once __DIR__ . "/" . "helpers.php";

class TestRouter {

    private static $get = NULL;
    private static $post = NULL;
    private static $static = NULL;
    private static $spas = NULL;

    public static function post(string $path, ...$callbacks) {
        $callback = apply_decorators($callbacks);
        self::$post->set($path, $callback);
    }

    public static function get(string $path, ...$callbacks) {
        $callback = apply_decorators($callbacks);
        self::$get->set($path, $callback);
    }

    public static function single(string $path, string $file) {
        // TODO:
        // should we allow middleware for these requests?
        // like, how would you do authentication.
        // Should we do that in the first place?
        // So many questions...
        // Consider the same for ->assets()
        self::$spas->set($path, $file);
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
            self::err("File not found '$file'");
            return self::not_found();
        }
        if (is_dir($file)) {
            self::err("Cannot render a directory '$file'");
            return self::not_found();
        }
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext === "php" || $ext === "inc") {
            self::err("File is a PHP file in static directory $dir for $path");
            return self::not_found();
        }
        switch (strtolower($ext)) {
            case "js":
                header("Content-Type: application/javascript");
                break;
            case "css":
                header("Content-Type: text/css");
                break;
            case "html":
                header("Content-Type: text/html");
                break;
            case "txt":
                header("Content-Type: text/plain");
                break;
            case "json":
                header("Content-Type: application/json");
                break;
            case "xml":
                header("Content-Type: application/xml");
                break;
            case "svg":
                header("Content-Type: image/svg+xml");
                break;
            case "png":
                header("Content-Type: image/png");
                break;
            case "jpg":
                header("Content-Type: image/jpeg");
                break;
            case "gif":
                header("Content-Type: image/gif");
                break;
            case "ico":
                header("Content-Type: image/x-icon");
                break;
            case "woff":
                header("Content-Type: application/font-woff");
                break;
            case "woff2":
                header("Content-Type: application/font-woff2");
                break;
            case "ttf":
                header("Content-Type: application/font-ttf");
                break;
            case "eot":
                header("Content-Type: application/vnd.ms-fontobject");
                break;
            case "otf":
                header("Content-Type: application/font-otf");
                break;
            case "mjs":
                header("Content-Type: application/javascript");
                break;
            default:
                header("Content-Type: " . mime_content_type($file));
        }
        return readfile($file);
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

        trigger("routes");

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

// things to consider:
//
// *    When a module changes its routes, how are developers of other modules
//      going to figure out why their links are no longer working?
//
// *    Perhaps a name for the route, and the link is derived by name in other modules?
//
// *    But then, what if they change the name of the route?
//
// *    Perhaps the route should be automatically based on the name of the driver, somehow?
//
// *    Should we get rid of the login guard pattern, and just call it at the
//      top of our callback?  It doesn't seem to save any typing otherwise... But
//      the idea of having middleware is pretty cool, because you could do things
//      like wrapping the output in a layout, or doing code analysis after the
//      render is done...  Idk, composition is pretty bad ass.
//
// *    custom 404 page for specified route nodes (e.g. 404 for everything
//      under /cp/router, a different 404 for everything under /cp/router/admin,
//      etc...)
//


// Things that can be done with the decorator strategy:
// 
//     * authentication
//     * authorization
//     * template processing
//     * layout wrapping
//     * keyword counting
//     * output size logging
// 
// It is also decoupled from the rendering process itself, so theoretically you
// should not need to worry about whether something is authenticated in your
// rendering function.  This enables you to reuse the rendering function, for
// instance.
