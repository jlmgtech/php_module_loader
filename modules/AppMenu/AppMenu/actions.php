<?php

Actions::on("menu", function() {
    AppMenu::add("object-ungroup");
});

Actions::on("routes", function() {
    AutoRouter::set("index", "Auth::login_guard", function() {
        include __DIR__ . "/" . "views/index.php";
    });
    Router::get("/admin", function() {
        AutoRouter::go(Router::current_module(), "index");
    });
});

