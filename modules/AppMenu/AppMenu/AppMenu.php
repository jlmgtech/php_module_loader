<?php

class AppMenu {
    public static $menu = [];
    public static function add_to_menu(string $name, string $url, string $icon) {
        self::$menu[$name] = [
            "url" => $url,
            "icon" => $icon,
        ];
    }
};

