<?php

class AutoRouter {

    private $modules = [];

    /// returns link to the page for the given module
    public static function getlink(string $module, string $page): string {
    }

    /// sets the link to the page for the given module to the subroute
    public static function setlink(string $page, string $subroute): void {
        $module = ModuleLoader::get_action_module(); // returns "" if not in an action
        if ($module === "") {
            throw new Exception("setlink cannot be called outside of an action");
        }
        $path = sprintf("%s/%s/%s", $module, $page, Utils::clean_path($subroute));
        $this->modules[$module] = $this->modules[$module] ?? [];
        $this->modules[$module][$page] = $subroute;
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
