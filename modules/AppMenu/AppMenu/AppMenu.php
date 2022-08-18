<?php

class AppMenu {
    public static $menu = [];

    public static function add(string $icon) {
        self::add_to_menu(
            Actions::current_driver(),
            AutoRouter::get(Actions::current_module(), "index"),
            $icon
        );
    }

    public static function add_to_menu(string $name, string $url, string $icon) {
        self::$menu[$name] = [
            "url" => $url,
            "icon" => $icon,
        ];
    }
};

