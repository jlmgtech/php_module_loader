<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "dot-circle"
    );
});

Actions::on("routes", function() {
    AutoRouter::set("index", "/session", function() {
        echo Auth::get_cp();
    });
    Router::get("/login", function() {
        echo Auth::get_login();
    });
    Router::get("/logout", function() {
        echo Auth::get_logout();
    });
    Router::post("/login", function() {
        echo Auth::post_login();
    });
    Router::get("/cp/session", "Auth::login_guard", function() {
        echo Auth::get_cp();
    });
});
