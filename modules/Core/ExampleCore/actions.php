<?php

add_action("init", function() { });

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Example Core", "/cp/example-core/", "dot-circle");
});

add_action("register_routes", function(callable $get, callable $post) {
    $get("/", ["Core", "render"]);
    $get("/cp/example-core/", ["Core", "render"]);
});
