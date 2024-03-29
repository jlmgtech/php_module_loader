<?php

require_once __DIR__ . "/" . "Actions.php";
define("YELLOW", "\033[1;33m");
define("RED", "\033[1;31m");
define("GREEN", "\033[1;32m");
define("RED_BG", "\033[41m");
define("LIGHT_BLUE", "\033[1;34m");
define("RST", "\033[0m");
define("PURE_BLUE", "\033[0;34m");

function module_log($lvl, $msg) {
    $stderr = fopen("php://stderr", "w");
    $pre = "";
    switch ($lvl) {
        case "WARN":
            $pre = YELLOW . "WARN" . RST;
            break;
        case "FAIL":
            $pre = RST . RED_BG . "FAIL" . RST . RED;
            break;
        case "INFO":
            $pre = LIGHT_BLUE . "INFO" . RST;
            break;
        case "LOG":
            $pre = RST . " LOG" . RST;
            break;
        default:
            $pre = "";
            break;
    }
    fprintf($stderr, "%s %s %s%s\n", date("Y-m-d H:i:s"), $pre, $msg, RST);
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

    private static $action_module = "";
    private static $action_driver = "";
    private $modules_dir = "";

    public static function get_action_module() {
        return self::$action_module;
    }
    public static function get_action_driver() {
        return self::$action_driver;
    }

    public function __construct(array $modconf, string $modules_dir) {
        module_log("", GREEN . "\n======REQUEST started======");
        spl_autoload_register([$this, "load_module"]);
        $this->modules_dir = $modules_dir;
        $this->modconf = $modconf;
        $this->resolve_drivers();
        $this->add_module_actions();
        Actions::trigger("init");
    }

    public function __destruct() {
        module_log("", PURE_BLUE . "======REQUEST finished======\n");
    }

    public function get_all_modules(): array {
        static $output = NULL;
        if ($output !== NULL) return $output;

        $output = [];
        $module_dirs = get_dirs(__DIR__ . "/../modules");
        foreach ($module_dirs as $module) {
            $output[] = basename($module);
        }
        return $output;
    }
    public function get_enabled_modules(): array {
        static $output = NULL;
        if ($output !== NULL) return $output;

        $output = [];
        foreach ($this->modconf as $module => $drivers) {
            $output[] = $module;
        }
        return $output;
    }
    public function get_disabled_modules(): array {
        static $output = NULL;
        if ($output !== NULL) return $output;

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
        static $output = NULL;
        if ($output !== NULL) return $output;

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
            if ($driver) {
                $action_file = $this->get_action_file($module, $driver);
                if ($action_file) {
                    self::$action_module = $module;
                    self::$action_driver = $driver;
                    include_once($action_file);
                    self::$action_driver = "";
                    self::$action_module = "";
                }
            }
        }
    }

    private function resolve_drivers() {
        $this->drivers = [];
        foreach ($this->modconf as $module => $drivers) {
            if (is_string($drivers))
                $drivers = [$drivers];
            $this->drivers[$module] = $this->resolve_driver($module, $drivers);
        }
    }
    private function resolve_driver(string $module, array $drivers) {
        foreach ($drivers as $driver) {
            $driver_file = $this->get_driver_file($module, $driver);
            if ($driver_file) {
                return $driver;
            }
        }
        module_log("FAIL", "Could not resolve driver for module $module");
        return NULL;
    }

    /// we want to return NULL if the driver is not found so other autoloaders can work after this one fails
    public function load_module($module) {

        if (!in_array($module, $this->get_enabled_modules())) {
            return NULL;
        }

        if (class_exists($module)) {
            module_log("INFO", "module '$module' already loaded");
            return $module;
        }

        $driver = $this->drivers[$module];
        if ($driver === NULL) {
            module_log("FAIL", "Could not resolve driver for module $module");
            return NULL;
        }

        $driver_file = $this->get_driver_file($module, $driver);
        if (!$driver_file) {
            module_log("WARN", "requested $module driver '$driver' is not " .
                "installed on this system for module $module\n");
            return NULL;
        }

        include_once($driver_file);
        if (!class_exists($driver)) {
            module_log("WARN", "'$driver_file' did not export a module named $driver");
            return NULL;
        }

        if ($module !== $driver) {
            class_alias($driver, $module);
        }
        if (method_exists($module, "onload"))
            $module::onload();

        module_log("INFO", "successfully loaded module $module with driver '$driver'");
        return $driver;
    }

    private function get_module_dir(string $module) {
        // since the driver directory will be checked to exist, we don't need
        // to check the module dir.
        return sprintf("%s/%s", $this->modules_dir, $module);
    }

    private function get_driver_dir(string $module, string $driver) {
        // if you want, you can memoize this function
        $driver_dir = sprintf("%s/%s/%s", $this->modules_dir, $module, $driver);
        $dir = is_dir($driver_dir) ? $driver_dir : NULL;
        if (!$dir) {
            module_log("WARN", "driver directory not found: $driver_dir");
        }
        return $dir;
    }

    private function get_driver_file(string $module, string $driver) {
        // if you want, you can memoize this function
        $driver_file = sprintf("%s/%s.php", $this->get_driver_dir($module, $driver), $driver);
        $file = is_file($driver_file) ? $driver_file : NULL;
        if (!$file) {
            module_log("WARN", "driver file not found: $driver_file");
        }
        return $file;
    }

    private function get_action_file(string $module, string $driver) {
        // if you want, you can memoize this function
        $action_file = sprintf("%s/actions.php", $this->get_driver_dir($module, $driver));
        return is_file($action_file) ? $action_file : NULL;
    }

    private function get_dirs($dir) {
        $output = [];
        foreach (scandir($dir) as $path) {
            if ($path[0] === ".") continue; // ignore hidden files
            if (!is_dir("$dir/$path")) continue; // ignore non-directories
            $output[] = $path;
        }
        return $output;
    }

}


// TODO - consider refactoring into modules
// consider pulling out as much of this functionality into modules as possible
// if need be, we can create a special directory for core modules that are required for the system to run.
// For example:
//   Actions::in_action()
//   Actions::module()
//   Actions::driver()
//   Actions::action()
//   Actions::params()
//   Actions::last_action()
//   Actions::on(string $name, callable $callback)
//   Actions::trigger(string $name, ...$args)
//
//
// TODO - consider how you would implement sub-modules
// Modules themselves can get quite large, and may be subdivided into
// sub-modules that would not be used or useful by sibling modules. In this
// case, it does not make sense to clutter up the filesystem with these
// submodules, so it would make sense to place the code for those modules
// within the module itself.
