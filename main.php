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
        echo "Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}";
    }
});

/*
 * TODO:
 * - hooks and actions              loaded immediately              (done)
 * - lib functions                  loaded on demand                (done)
 * - navigation menu registration   loaded when menu fires ready    (easy)
 * - ers core simulation            n/a                             (n/a)
 * - url route registration         loaded during routing           (easy)
 *      option 1: all routes under a subdomain call a routing function implemented on the module
 *      option 2: routes under a subdomain are registered by the module calling route registration functions like get() or post()
 * - rpc registration               loaded on demand I guess?       (hard)
 * */

require_once __DIR__ . "/" . "lib/load_modules.php";
require_once __DIR__ . "/" . "lib/hook_functions.php";

$safe_mode = [
    "Auth" => "SessionAuth",
    "Router" => "Router",
    "Core" => "ExampleCore",
    "ModuleManager" => "ModuleManager",
    "Logging" => "BasicLogger",
];

$user_mode = [
    "Auth" => "SessionAuth",
    "Router" => "Router",
    "Core" => "ExampleCore",
    "ModuleManager" => "ModuleManager",
    "Logging" => "BasicLogger", 
];

$mode = $user_mode;
$loader = new ModuleLoader($mode);
do_action("init");

Router::render();

//$html = file_get_contents(__DIR__ . "/" . "index.tpl");
//$html = str_replace("[code]", $code ?? "", $html);
//echo $html;
