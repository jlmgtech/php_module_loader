<?php

add_action("register_menu", function(callable $add_to_menu) {
    $add_to_menu("Session", "/cp/session/", "dot-circle");
});

add_action("register_routes", function(callable $get, callable $post) {
    $get("/login", ["Auth", "get_login"]);
    $get("/logout", ["Auth", "get_logout"]);
    $post("/login", ["Auth", "post_login"]);
    $get("/cp/session", Auth::login_wrap(["Auth", "get_cp"]));
});
