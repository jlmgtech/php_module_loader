<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "object-ungroup"
    );
});

Actions::on("routes", function() {
    Router::get("/", function() {
        include __DIR__ . "/" . "views/index.php";
    });
    AutoRouter::set("index", "/", function() {
        include __DIR__ . "/" . "views/index.php";
    });
});

