<?php

on("menu", function() {
    AppMenu::add_to_menu("AutoRouter", "/cp/auto-router/", "code");
});

on("routes", function() {
    $module = ModuleLoader::get_action_module();
    module_log("INFO", "AutoRouter: module is $module");
    Router::get("/cp/auto-router/", function() use($module) {
        $_GLOBALS["module"] = $module;
        include __DIR__ . "/views/index.php";
        unset($_GLOBALS["module"]);
    });
});
