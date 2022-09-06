<?php

Actions::on("routes", function() {
    Router::get("/", function() {
        echo "Hello World!";
    });
});
