<?php

class ModuleManager {

    private static $menu = [];

    public static function onload() {
    }

    public static function add_menu_entry(string $name, string $url, string $icon) {
        self::$menu[$name] = [
            "url" => $url,
            "icon" => $icon,
        ];
    }

    public static function render($loader) {
        $modconf = $loader->modconf;
        $output = "";

        // dispatch registration hook
        do_action("register_menu", ["ExampleCore", "add_menu_entry"]);

        // render menu
        $output .= "<ul>\n";
        foreach (self::$menu as $name => $entry) {
            $output .= "<li><a href='{$entry["url"]}'><i class='fa fa-{$entry["icon"]}'></i> {$name}</a></li>\n";
        }
        $output .= "</ul>\n";
        return $output;
    }

};

