<?php

add_action("init", function() {
    //echo "BasicLogger initialized\n";
});

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Basic Logger", "/cp/basic-logger/", "list-alt");
});

add_action("register_routes", function(callable $get, callable $route) {
    $get("/cp/basic-logger/", function() {
        $output = "";
        $logs = Logging::get_logs();
        $logs = array_reverse($logs);
        $logs = array_slice($logs, 0, 100);
        $logs = array_map(function($log) {
            $log["time"] = date("Y-m-d H:i:s", $log["time"]);
            return $log;
        }, $logs);
        foreach ($logs as $log) {
            $output .= "
                <div>{$log["time"]} - {$log["level"]} : {$log["msg"]}</div>
            ";
        }
        return $output;
    });
});
