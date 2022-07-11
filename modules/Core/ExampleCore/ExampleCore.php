<?php

class ExampleCore {

    public static function onload() { }

    public function render($loader) {
        $modconf = $loader->get_modconf();
        $output = "";
        // load every module and tell it to spit out its app icon
        foreach ($modconf as $module => $drivers) {
            $driver = $loader->load_module($module);
            $output .= sprintf("%s as %s\n", $module, $driver);
        }
        printf("================\n%s\n", $output);
    }
};

