<?php

add_action("init", function() {
    echo "ExampleCore initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("ModuleMenu", "/cp/module-menu/", "icon-here");
});
