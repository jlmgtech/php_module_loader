<?php

on("init", function() { });

on("menu", function() {
    AppMenu::add_to_menu("Example Core", "/cp/example-core/", "dot-circle");
});

on("routes", function() {
    Router::get("/", "Auth::login_guard", "Core::render");
    Router::get("/cp/example-core/", "Auth::login_guard", "Core::render");
});
