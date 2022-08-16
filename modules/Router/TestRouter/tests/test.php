<?php

set_error_handler(
    function($errno, $errstr, $errfile, $errline) {
        global $_caughtError;
        $_caughtError = true;
        throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
    }
);
register_shutdown_function(function() {
    if ($error = error_get_last()) {
        header('HTTP/1.1 500 Internal Server Error');
        echo "<pre>Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}</pre>";
    }
});

require_once __DIR__ . "/" . "../../../../lib/ModuleLoader.php";

$safe_mode = [
    "Auth"          => "TestAuth",
    "Router"        => "TestRouter",
    "Utils"         => "Utils",
    "Core"          => "TestCore",
    "AppMenu"       => "TestAppMenu",
    "Logging"       => "TestLogger",
];

$modules_dir = __DIR__ . "/" . "../../../../modules/";
$loader = new ModuleLoader($safe_mode, $modules_dir);
Router::render();
