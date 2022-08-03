<?php

class Router {

    private static $routes = ["POST" => [], "GET" => []];

    public static function onload() {
    }

    public static function post(string $path, callable $callback) {
        $path = trim($path, "/");
        if (isset(self::$routes["POST"][$path])) {
            throw new Exception("POST Route already exists '$path'");
        }
        self::$routes["POST"][$path] = $callback;
    }

    public static function get(string $path, callable $callback) {
        $path = trim($path, "/");
        if (isset(self::$routes["GET"][$path])) {
            throw new Exception("GET Route already exists '$path'");
        }
        self::$routes["GET"][$path] = $callback;
    }

    public static function render() {
        $get_fxn = [get_class(), "get"];
        $post_fxn = [get_class(), "post"];
        do_action("register_routes", $get_fxn, $post_fxn);

        // echo the current url path
        $path = trim($_SERVER["REQUEST_URI"], '/');
        $method = $_SERVER["REQUEST_METHOD"];
        $callback = self::$routes[$method][$path] ?? NULL;
        if ($callback) {
            $output = $callback();
            echo $output;
        } else {
            return "
                <h1>404 - page not found</h1>
                <div>
                    <p>The page you are looking for does not exist.</p>
                    <p>Please check the URL and try again.</p>
                    <div>
                        <a href='/'>Go to home page</a>
                    </div>
                </div>
            ";
        }
    }

};
