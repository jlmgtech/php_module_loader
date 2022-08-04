<?php

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Session", "/cp/session/", "dot-circle");
});

add_action("register_routes", function() {
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
