<?php

class Infusionsoft {

    public static function onload(): void
    {
    }

    public static function next_bill_date(): string
    {
        return date("Y-m-d H:i:s");
    }

    public static function add_tag(string $cid, string $tid): bool
    {
        return true;
    }

    public static function get_config_html(): string
    {
        $str = file_get_contents(__DIR__ . "/" . "config.html");
        $other_plugins = do_action("Billing_other_links");
        return str_replace("[other_links]", $other_plugins, $str);
    }

}
