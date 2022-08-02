<?php

class ExampleCore {

    private static $menu = [];

    public static function onload() {}
    public static function add_menu_entry(string $name, string $url, string $icon) {
        self::$menu[$name] = [
            "url" => $url,
            "icon" => $icon,
        ];
    }

    public static function get_current_account(): string {
        return "my_account";
    }

    public static function render() {
        $account = self::get_current_account();
        return "
            <h1>Example Core ($account)</h1>
            <div>
                <a href='/cp/module-menu/'>Module Manager</a>
            </div>
        ";
    }
};
