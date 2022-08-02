<?php

add_action("init", function() {
    echo "Hive initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Hive", "/cp/hive/", "icon-here");
});
