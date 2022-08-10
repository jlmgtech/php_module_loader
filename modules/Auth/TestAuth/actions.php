<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu("Session", "/cp/session/", "dot-circle");
});

Actions::on("routes", function() {
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
