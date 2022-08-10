<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu("AutoRouter", "/cp/auto-router/", "code");
});

Actions::on("routes", function() {
    $module = Actions::current_module();
    Router::get("/cp/auto-router/", function() use($module) {
        $_GLOBALS["module"] = $module;
        include __DIR__ . "/views/index.php";
        unset($_GLOBALS["module"]);
    });
});
