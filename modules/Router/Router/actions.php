<?php

add_action("init", function() {
    //echo "ExampleCore initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Router", "/cp/router/", "registered");
});

add_action("register_routes", function() {
    Router::static("/cp/router/", __DIR__ . "/" . "my-svelte-project/public/");
});
