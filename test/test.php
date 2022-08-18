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
        echo "<pre>Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}</pre>";
    }
});

require_once __DIR__ . "/" . "../main.php";

$safe_mode = [
    "Auth"          => "TestAuth",
    "AutoRouter"    => "AutoRouter",
    "Router"        => "Router",
    "Utils"         => "Utils",
    "AppMenu"       => "AppMenu",
    "Logger"        => "ConsoleLogger",
];

$loader = new ModuleLoader($safe_mode, __DIR__ . "/" . "../modules/");
Router::render();

/* TODO:
 * - hooks and actions              loaded immediately              (done)
 * - lib functions                  loaded on demand                (done)
 * - navigation menu registration   loaded when menu fires ready    (done)
 * - url route registration         loaded during routing           (done)
 * - auto routing                   loaded during routing           (done)
 * - rpc registration               loaded on demand I guess?       (n/a) */