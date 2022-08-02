<?php

add_action("init", function() {
    echo "BasicLogger initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("BasicLogger", "/cp/basic-logger/", "icon-here");
});
