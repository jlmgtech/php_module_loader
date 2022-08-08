<?php

on("init", function() {
    //echo "ExampleCore initialized\n";
});

on("menu", function() {
    AppMenu::add_to_menu("Router", "/cp/router/", "registered");
});

on("routes", function() {
    Router::single("/cp/router/", __DIR__ . "/" . "my-svelte-project/public/index.html");
    Router::assets("/cp/router/", __DIR__ . "/" . "my-svelte-project/public/");
});
