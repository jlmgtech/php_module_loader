<?php

add_action("init", function() {
    echo "infusionsoft driver initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Infusionsoft", "/cp/infusionsoft/", "icon-here");
});
