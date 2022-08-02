<?php

add_action("init", function() {
    //echo "ExampleBilling initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    echo "register_menu\n";
    $add_to_menu("Example Billing", "/cp/plugins/example-billing", "money-bill-alt");
});
