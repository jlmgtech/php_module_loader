<?php

class TestModuleManager {

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
            $green = rand()%100;
            $red = $green + rand()%100;
            $blue = rand()%100;

            $random_color = sprintf("rgb(%d, %d, %d)", $red, $green, rand()%200);
            $output .= "
                <li style='background:$random_color'>
                    <a href='{$entry["url"]}' style='color:#fff'>
                        <div>{$name}</div>
                        <br />
                        <div><i class='fa fa-{$entry["icon"]}'></i></div>
                    </a>
                </li>\n
            ";
        }
        $output .= "</ul>\n";
        return "
            <style>body{background:#111;color:#fff}a[href] {text-decoration:none;text-align:center;} ul{list-style:none} li{padding:1em;margin:1em;min-width:30%;display:inline-block;}</style>
            <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css' />
            <div style='width:50%;margin:auto;font-family:sans-serif;'>
                <h1>Modules</h1>
                <div>$output</div>
            </div>
        ";
    }

};

