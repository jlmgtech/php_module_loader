<?php

Actions::on("init", function() {
    //echo "ExampleCore initialized\n";
});

Actions::on("menu", function() {
    AppMenu::add_to_menu("App Menu", "/cp/app-menu/", "object-ungroup");
});

Actions::on("routes", function() {
    Router::get("/cp/app-menu/", "Auth::login_guard", function() {
        echo AppMenu::render();
    });
});

