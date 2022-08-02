<?php

add_action("init", function() {
    //echo "infusionsoft driver initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Infusionsoft", "/cp/infusionsoft/", "credit-card");
});

add_action("register_routes", function(callable $get, callable $post) {
    $get("/cp/infusionsoft/", function() {
        return Billing::get_config_html();
    });
});
