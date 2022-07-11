<?php

require_once __DIR__ . "/" . "hook_functions.php";

class ModuleLoader {

    private $installed = [];
    private $modconf = [];
    private $actionfiles = [];
    private $home = __DIR__;

    public function __construct(string $home, array $modconf) {
        spl_autoload_register([$this, "load_module"]);
        $this->home = $home;
        $this->modconf = $modconf;
        $this->discover_modules();
        $this->add_module_actions();
    }

    private static function log($msg) {
        // TODO - uncomment this file to enable module loader logging
        //echo $msg . "\n";
    }

    public function get_modconf(): array {
        return $this->modconf;
    }

    public function get_installed(): array {
        return $this->installed;
    }

    private function add_module_actions() {
        foreach ($this->actionfiles as $module => $actionfile_path) {
            include_once($actionfile_path);
        }
    }

    public static function get_dirs($dir) {
        $output = [];
        foreach (scandir($dir) as $path) {
            if ($path[0] === ".") continue; // ignore hidden files
            if (!is_dir("$dir/$path")) continue; // ignore non-directories
            $output[] = $path;
        }
        return $output;
    }

    private function discover_modules() {
        $prefix = sprintf("%s/modules", $this->home);
        $installed = [];
        foreach (Self::get_dirs($prefix) as $mod) {
            $installed[$mod] = [];
            if (is_file("$prefix/$mod/actions.php")) {
                $this->actionfiles[$mod] = "$prefix/$mod/actions.php";
            }

            foreach (Self::get_dirs("$prefix/$mod") as $variant) {
                $variant_folder = "$prefix/$mod/$variant";
                $file = "$variant_folder/$variant.php";
                if (!is_file($file))
                    $this->log("NOT A FILE '$file'\n");

                $installed[$mod][$variant] = $file;
            }

            if (count($installed[$mod]) === 0) {
                $installed[$mod] = NULL;
            }
        }

        $this->installed = $installed;
    }

    public function load_module($module): string {
        $this->log("looking for module '$module'\n");
        if (class_exists($module)) {
            $this->log("module '$module' already loaded\n");
            return $module;
        }

        if (!isset($this->installed[$module])) {
            $this->log("WARN: no drivers installed for '$module'\n");
            return "";
        }

        $acceptable_drivers = $this->modconf[$module]??[];
        if (count($acceptable_drivers) === 0) {
            $this->log("WARN: no acceptable driver specified to fulfill requirement '$module'\n");
            return "";
        }

        foreach ($acceptable_drivers as $driver) {
            $installed_path = $this->installed[$module][$driver] ?? NULL;
            if (!$installed_path) {
                $this->log("WARN: requested $module driver '$driver' is not installed on this system for module $module\n");
                continue;
            }

            include_once($installed_path);
            if (!class_exists($driver)) {
                $this->log("WARN: '$installed_path' did not export a module named $driver\n");
                continue;
            }
            class_alias($driver, $module);
            if (method_exists($module, "onload"))
                $module::onload();

            $this->log("successfully loaded module $module with driver '$driver'\n");
            return $driver;
        }
    }
}
