<?php

class TestAppMenu {

    private static $menu = [];

    public static function onload() {
    }

    public static function add_to_menu(string $name, string $url, string $icon) {
        self::$menu[$name] = [
            "url" => $url,
            "icon" => $icon,
        ];
    }

    public static function render() {
        $output = "";
        // dispatch registration hook
        trigger("menu");

        // render menu
        $output .= "<ul>\n";
        $i = 0;
        foreach (self::$menu as $name => $entry) {
            $output .= "
                <li>
                    <a href='{$entry["url"]}'>
                        <div class='icon'><i class='fa fa-{$entry["icon"]}'></i></div>
                        <div class='name'>{$name}</div>
                    </a>
                </li>\n
            ";
            $i++;
        }
        $output .= "</ul>\n";
        return "
            <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css' />
            <style>".file_get_contents(__DIR__ . "/styles.css")."</style>
            <div class='menu'>
                <br />
                <h1>Apps</h1>
                <br />
                <div>$output</div>
            </div>
        ";
    }

};

