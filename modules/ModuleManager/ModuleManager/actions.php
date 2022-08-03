<?php

add_action("init", function() {
    //echo "ExampleCore initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Module Menu", "/cp/module-menu/", "object-ungroup");
});

add_action("register_routes", function(callable $get, callable $post) {
    $get("/cp/module-menu/", Auth::login_wrap(function() {
        return ModuleManager::render();
    }));
});
