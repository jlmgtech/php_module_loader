<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu("AutoRouter", "/cp/auto-router/", "code");
});

Actions::on("routes", function() {

    AutoRouter::set("index", "/you-found-me", function() {
        include __DIR__ . "/views/index.php";
    });

    //Router::get("/cp/auto-router/", function() use($module) { });
});
