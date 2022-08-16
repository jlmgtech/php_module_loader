<?php

Actions::on("init", function() {
    //echo "ExampleCore initialized\n";
});

Actions::on("menu", function() {
    AppMenu::add_to_menu(
        Actions::current_driver(),
        AutoRouter::get(Actions::current_module(), "index"),
        "object-ungroup"
    );
});

Actions::on("routes", function() {
    AutoRouter::set("index", "/", function() {
        echo AppMenu::render();
    });
});

