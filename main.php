<?php

require_once __DIR__ . "/" . "lib/load_modules.php";

$safe_mode = [
    "Core" => ["ExampleCore"],
    "Billing" => ["ExampleBilling"],
    "Logging" => ["BasicLogger"],
];

$user_mode = [
    "Core" => ["ExampleCore"],
    "Billing" => ["Infusionsoft", "ExampleBilling"],
    "Logging" => ["HappyLogging", "BasicLogger"],
];

$mode = $safe_mode;
$loader = new ModuleLoader(__DIR__, $mode);
Core::render($loader);
