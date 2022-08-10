<?php

class AutoRouter {

    private static $modules = [];

    /// returns link to the page for the given module
    public static function get(string $module, string $page): string {
        return self::$modules[$module][$page];
    }

    public static function go(string $module, string $page): void {
        header("Location: " . self::get($module, $page));
    }

    /// sets the link to the page for the given module to the subroute
    public static function set(string $page, string $subroute, callable $fn): void {
        $module = Actions::current_module(); // returns "" if not in an action
        if ($module === "")
            throw new Exception(__METHOD__."(...) can't be called outside an action");

        $path = sprintf("%s/%s/%s", $module, $page, Utils::clean_path($subroute));
        self::$modules[$module] = self::$modules[$module] ?? [];
        self::$modules[$module][$page] = $subroute;
        module_log("WARN", $path);
        Router::get($path, $fn);
    }

}


// modules should have the ability to have auto-generated routes that other
// modules can access if they know the name of the module.
// For instance, if a module is named "foo", it should be able to define a list
// of pages and their corresponding sub-routes.
// Then, when another module asks for a given page from a given module, it
// should only need to know the name of the page and the name of the module,
// and NOT the actual route.
// This way, the modules can be decoupled from each other, and routes can be
// changed at will without needing to update the rest of the application.
