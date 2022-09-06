<?php

Actions::on("route", function() {
    Router::get("/", function() {
        return "Hello World!";
    });
});
