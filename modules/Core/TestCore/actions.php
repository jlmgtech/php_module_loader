<?php

Actions::on("init", function() { });

Actions::on("menu", function() {
    AppMenu::add_to_menu("Example Core", "/cp/example-core/", "dot-circle");
});

Actions::on("routes", function() {
    Router::get("/", "Auth::login_guard", "Core::render");
    Router::get("/cp/example-core/", "Auth::login_guard", "Core::render");
});
