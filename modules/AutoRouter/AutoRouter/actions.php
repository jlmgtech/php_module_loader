<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "code"
    );
});

Actions::on("routes", function() {

    AutoRouter::set("index", "/you-found-me", function() {
        include __DIR__ . "/views/index.php";
    });

    //Router::get("/cp/auto-router/", function() use($module) { });
});
