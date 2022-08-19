<?php

Actions::on("not_in_action_err", function($type) {
    $module = Router::current_module();
    $driver = Router::current_driver();
    module_log("FAIL", "$module/$driver - trying to access 'Actions::current_$type()' when not in an action, try calling Router::current_$type() instead.");
});
