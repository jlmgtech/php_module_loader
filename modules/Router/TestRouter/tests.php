<?php
require_once __DIR__ . "/" . "StaticResolver.php";
require_once __DIR__ . "/" . "DynamicResolver.php";

function test() {
    $static = new StaticResolver();
    $get = new DynamicResolver();
    $static->set('/public/', __DIR__ . "/static");
    $get->set("/cp/admin/", function() { echo "GOOD\n"; });
    $get->set("/cp/admin/result", function() { echo "GOOD\n"; });
    $get->set("/things/", function() { echo "GOOD\n"; });

    ($get->get("cp/admin/")                  ?? function() { echo "FAIL\n"; })();
    ($get->get("/cp/admin/shouldfail")       ?? function() { echo "GOOD\n"; })();
    ($get->get("/cp//admin/result////")      ?? function() { echo "FAIL\n"; })();
    ($get->get("cp/admin/result/shouldfail") ?? function() { echo "GOOD\n"; })();
    ($get->get("/things")                    ?? function() { echo "FAIL\n"; })();
    ($get->get("things")                     ?? function() { echo "FAIL\n"; })();
    ($get->get("things/shouldfail")          ?? function() { echo "GOOD\n"; })();

    printf("static dir = '%s'\n", $static->get("/public/"));
}

test();
