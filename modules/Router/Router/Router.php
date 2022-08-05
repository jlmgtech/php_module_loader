<?php

class Router {

    private static $routes = ["POST" => [], "GET" => []];

    public static function any(string $path, ...$callbacks) {
        self::post($path, $callbacks);
        self::get($path, $callbacks);
    }

    public static function post(string $path, ...$callbacks) {
        $path = trim($path, "/");
        if (isset(self::$routes["POST"][$path])) {
            throw new Exception("POST Route already exists '$path'");
        }
        $callback = self::compose_callbacks($callbacks);
        self::$routes["POST"][$path] = $callback;
    }

    public static function get(string $path, ...$callbacks) {
        $path = trim($path, "/");
        if (isset(self::$routes["GET"][$path])) {
            throw new Exception("GET Route already exists '$path'");
        }

        // compose the callbacks into a single callback
        $callback = self::compose_callbacks($callbacks);
        self::$routes["GET"][$path] = $callback;
    }

    private static function compose_callbacks(array $callbacks): callable {
        return function (...$args) use ($callbacks) {
            foreach ($callbacks as $callback) {
                if ($callback(...$args) === false)
                    return false;
            }
            return true;
        };
    }

    public static function assets() {
        // TODO
        // TODO - change the way paths are resolved
        // given the REQUEST_URI and a GET method, read the respective file
    }

    public static function render() {
        do_action("register_routes");

        $path = trim($_SERVER["REQUEST_URI"], '/');
        $method = $_SERVER["REQUEST_METHOD"];
        $callback = self::$routes[$method][$path] ?? NULL;
        if ($callback !== NULL) {
            $callback();
        } else {
            header("HTTP/1.1 404 Not Found");
            header("Content-Type: text/html; charset=utf-8");
            echo "
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
