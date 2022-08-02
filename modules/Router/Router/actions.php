<?php

add_action("init", function() {
    //echo "ExampleCore initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Router", "/cp/router/", "registered");
});
