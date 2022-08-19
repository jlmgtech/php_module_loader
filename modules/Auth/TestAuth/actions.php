<?php

Actions::on("menu", function() {
    AppMenu::add("dot-circle");
});

Actions::on("routes", function() {
    AutoRouter::set("index", "", function() {
        echo Auth::get_cp();
    });
    AutoRouter::set("login", "", function() {
        echo Auth::get_login();
    });
    AutoRouter::set("logout", "", function() {
        echo Auth::get_logout();
    });
    AutoRouter::set("session", "", "Auth::login_guard", function() {
        echo Auth::get_cp();
    });

    Router::post(AutoRouter::get(Actions::current_module(), "login") , function() {
        echo Auth::post_login();
    });
});
