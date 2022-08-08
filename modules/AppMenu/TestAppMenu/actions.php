<?php

on("init", function() {
    //echo "ExampleCore initialized\n";
});

on("menu", function() {
    AppMenu::add_to_menu("App Menu", "/cp/app-menu/", "object-ungroup");
});

on("routes", function() {
    Router::get("/cp/app-menu/", "Auth::login_guard", function() {
        echo AppMenu::render();
    });
});


// things to consider:
//
// *    When a module changes its routes, how are developers of other modules
//      going to figure out why their links are no longer working?
//
// *    Perhaps a name for the route, and the link is derived by name in other modules?
//
// *    But then, what if they change the name of the route?
//
// *    Perhaps the route should be automatically based on the name of the driver, somehow?
//
// *    Should we get rid of the login guard pattern, and just call it at the
//      top of our callback?  It doesn't seem to save any typing otherwise... But
//      the idea of having middleware is pretty cool, because you could do things
//      like wrapping the output in a layout, or doing code analysis after the
//      render is done...  Idk, composition is pretty bad ass.
//
// *    custom 404 page for specified route nodes (e.g. 404 for everything
//      under /cp/router, a different 404 for everything under /cp/router/admin,
//      etc...)
//


// Things that can be done with the decorator strategy:
// 
//     * authentication
//     * authorization
//     * template processing
//     * layout wrapping
//     * keyword counting
//     * output size logging
// 
// It is also decoupled from the rendering process itself, so theoretically you
// should not need to worry about whether something is authenticated in your
// rendering function.  This enables you to reuse the rendering function, for
// instance.
