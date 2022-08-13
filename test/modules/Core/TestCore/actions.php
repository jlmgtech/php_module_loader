<?php

Actions::on("init", function() { });

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "dot-circle"
    );
});

Actions::on("routes", function() {
    Router::get("/", "Auth::login_guard", "Core::render");
    AutoRouter::set("index", "/", "Auth::login_guard", "Core::render");
});
