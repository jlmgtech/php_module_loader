<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "object-ungroup"
    );
});

Actions::on("routes", function() {
    Router::get("/admin", function() {
        AutoRouter::go(Actions::current_module(), "index");
    });
    AutoRouter::set("index", "/", function() {
        include __DIR__ . "/" . "views/index.php";
    });
});

