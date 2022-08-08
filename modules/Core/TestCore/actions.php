<?php

add_action("init", function() { });

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Example Core", "/cp/example-core/", "dot-circle");
});

add_action("register_routes", function() {
    Router::get("/", "Auth::login_guard", "Core::render");
    Router::get("/cp/example-core/", "Auth::login_guard", "Core::render");
});
