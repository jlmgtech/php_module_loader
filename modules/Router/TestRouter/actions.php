<?php

Actions::on("init", function() {
    //echo "ExampleCore initialized\n";
});

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "registered"
    );
});

Actions::on("routes", function() {
    AutoRouter::set("index", "/routing", "Auth::login_guard", function() {
        include __DIR__ . "/" . "views/index.php";
    });

    Router::single("/cp/router/", __DIR__ . "/" . "my-svelte-project/public/index.html");
    Router::assets("/cp/router/", __DIR__ . "/" . "my-svelte-project/public/");
});
