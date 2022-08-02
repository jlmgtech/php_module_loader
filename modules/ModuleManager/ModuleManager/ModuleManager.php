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

    public static function render() {
        $output = "";
        // dispatch registration hook
        do_action("register_menu", [get_class(), "add_menu_entry"]);

        // render menu
        $output .= "<ul>\n";
        foreach (self::$menu as $name => $entry) {
            $output .= "
                <li>
                    <a href='{$entry["url"]}'>
                        <div>{$name}</div>
                        <br />
                        <div><i class='fa fa-{$entry["icon"]}'></i></div>
                    </a>
                </li>\n
            ";
        }
        $output .= "</ul>\n";
        return "
            <style>a[href] {text-decoration:none;text-align:center;} ul{list-style:none;display:flex;} li{background: #cccccc; padding: 1em; width: 30%;margin:1em;}</style>
            <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css' />
            <div style='width:50%;margin:auto;font-family:sans-serif;'>
                <h1>Modules</h1>
                <div>$output</div>
            </div>
        ";
    }

};

