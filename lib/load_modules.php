<?php

require_once __DIR__ . "/" . "hook_functions.php";
define("MODULES_DIR", __DIR__ . "/../modules");

function get_module_dir(string $module) {
    $module_dir = sprintf("%s/%s/", MODULES_DIR, $module);
    return is_dir($module_dir) ? $module_dir : NULL;
}

function get_driver_dir(string $module, string $driver) {
    // if you want, you can memoize this function
    $driver_dir = sprintf("%s/%s/%s/", MODULES_DIR, $module, $driver);
    return is_dir($driver_dir) ? $driver_dir : NULL;
}

function get_driver_file(string $module, string $driver) {
    // if you want, you can memoize this function
    $driver_file = sprintf("%s/%s.php", get_driver_dir($module, $driver), $driver);
    return is_file($driver_file) ? $driver_file : NULL;
}

function get_action_file(string $module, string $driver) {
    // if you want, you can memoize this function
    $action_file = sprintf("%s/actions.php", get_driver_dir($module, $driver));
    return is_file($action_file) ? $action_file : NULL;
}

function get_dirs($dir) {
    $output = [];
    foreach (scandir($dir) as $path) {
        if ($path[0] === ".") continue; // ignore hidden files
        if (!is_dir("$dir/$path")) continue; // ignore non-directories
        $output[] = $path;
    }
    return $output;
}

function module_log($msg) {
    printf("\tLOG -> %s\n", $msg);
}


class ModuleLoader {

    public $modconf = [];
    // modconf is a list of required modules and suitable drivers. It looks like
    // [
    //     "Core" => ["ExampleCore"],
    //     "Billing" => ["InfusionsoftBilling", "ExampleBilling"],
    //     "Logging" => ["BasicLogger"],
    // ];

    public $drivers = [];
    // drivers is the map of each modconf module resolved to a single
    // respective driver.
    // It looks like this:
    // [
    //    "Core" => "ExampleCore",
    //    "Billing" => "InfusionsoftBilling",
    //    "Logging" => "BasicLogger",
    // ]

    public function __construct(array $modconf) {
        spl_autoload_register([$this, "load_module"]);
        $this->modconf = $modconf;
        $this->resolve_drivers();
        $this->add_module_actions();
    }

    public function get_all_modules(): array {
        $output = [];
        $module_dirs = get_dirs(__DIR__ . "/../modules");
        foreach ($module_dirs as $module) {
            $output[] = basename($module);
        }
        return $output;
    }
    public function get_enabled_modules(): array {
        $output = [];
        foreach ($this->modconf as $module => $drivers) {
            $output[] = $module;
        }
        return $output;
    }
    public function get_disabled_modules(): array {
        $output = [];
        $all_modules = $this->get_all_modules();
        foreach ($all_modules as $module) {
            if (!in_array($module, $this->get_enabled_modules())) {
                $output[] = $module;
            }
        }
        return $output;
    }

    public function get_all_drivers_for_module(string $module): array {
        // show all subdirectories in the module directory
        $output = [];
        $module_dir = get_module_dir($module);
        if (!$module_dir) {
            return $output;
        }
        $driver_dirs = get_dirs($module_dir);
        foreach ($driver_dirs as $driver) {
            $output[] = basename($driver);
        }
        return $output;
    }

    private function add_module_actions() {
        foreach ($this->drivers as $module => $driver) {
            $action_file = get_action_file($module, $driver);
            if ($action_file) {
                include_once($action_file);
            }
        }
    }

    private function resolve_drivers() {
        $this->drivers = [];
        foreach ($this->modconf as $module => $drivers) {
            $this->drivers[$module] = $this->resolve_driver($module, $drivers);
        }
    }
    private function resolve_driver(string $module, array $drivers) {
        foreach ($drivers as $driver) {
            module_log("DEBUG: resolving driver $driver for module $module");
            $driver_file = get_driver_file($module, $driver);
            if ($driver_file) {
                module_log("INFO: found driver $driver for module $module: $driver_file");
                return $driver;
            }
        }
        module_log("FAIL: Could not resolve driver for module $module");
        return NULL;
    }

    public function load_module($module): string {
        module_log("looking for module '$module'\n");
        if (class_exists($module)) {
            module_log("INFO: module '$module' already loaded\n");
            return $module;
        }

        $driver = $this->drivers[$module];
        if ($driver === NULL) {
            module_log("FAIL: Could not resolve driver for module $module\n");
            return NULL;
        }

        $driver_file = get_driver_file($module, $driver);
        if (!$driver_file) {
            module_log("WARN: requested $module driver '$driver' is not " .
                "installed on this system for module $module\n");
            return NULL;
        }

        include_once($driver_file);
        if (!class_exists($driver)) {
            module_log("WARN: '$driver_file' did not export a module named $driver\n");
            return NULL;
        }

        class_alias($driver, $module);
        if (method_exists($module, "onload"))
            $module::onload();

        module_log("INFO: successfully loaded module $module with driver '$driver'\n");
        return $driver;
    }
}
