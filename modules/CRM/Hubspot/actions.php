<?php

add_action("init", function() {
    echo "Hubspot initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Hubspot", "/cp/hubspot/", "icon-here");
});
