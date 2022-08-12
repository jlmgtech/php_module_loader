<?php

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "list-alt"
    );
});

Actions::on("error", function($message) {
    register_shutdown_function(function() use ($message) {
        echo "<h1 style='color:red'>Error</h1>";
        echo "<p>$message</p>";
    });
});

Actions::on("routes", function() {
    AutoRouter::set("index", "/", "Auth::login_guard", function() {
        $output = "";
        $logs = Logging::get_logs();
        $logs = array_reverse($logs);
        $logs = array_slice($logs, 0, 100);
        $logs = array_map(function($log) {
            return $log;
        }, $logs);
        foreach ($logs as $log) {
            $output .= "
                <pre>{$log["time"]} - {$log["level"]} : {$log["message"]}</pre>
            ";
        }
        echo "
            <style>".file_get_contents(__DIR__."/styles.css")."</style>
            <div class='container'>
                <h1>Basic Logger</h1>
                <div class='row'>
                    <div class='col-md-12'>
                        {$output}
                    </div>
                </div>
            </div>
        ";
    });
});
