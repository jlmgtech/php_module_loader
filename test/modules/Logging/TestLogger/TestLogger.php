<?php

class TestLogger {

    public static function write(string $lvl, string $msg) {
        $file = sprintf("%s/%s", __DIR__, "log.log");
        $fp = fopen($file, "a");
        if (!$fp) {
            var_dump(error_get_last());
            throw new Exception("Could not open file $file");
        }
        fprintf($fp, "%s::%s::%s\n", date("Y-m-d H:i:s", strtotime("now")), $lvl, $msg);
        fclose($fp);
    }

    public static function log(string $msg) {
        self::write("LOG ", $msg);
    }

    public static function debug(string $msg) {
        self::write("DEBUG", $msg);
    }

    public static function info(string $msg) {
        self::write("INFO", $msg);
    }

    public static function warn(string $msg) {
        self::write("WARN", $msg);
    }

    public static function error(string $msg) {
        self::write("FAIL", $msg);
    }

    public static function get_logs() {
        $fdata = trim(file_get_contents(__DIR__ . "/" . "log.log"));
        $fdata = explode("\n", $fdata);
        $output = [];
        foreach ($fdata as $line) {
            if (trim($line) == "") {
                continue;
            }
            $line = explode("::", $line);
            $date = array_shift($line);
            $lvl = array_shift($line);
            $msg = implode(" ", $line);
            $output[] = [
                "time" => $date,
                "level" => $lvl,
                "message" => $msg,
            ];
        }
        return $output;
    }

}
