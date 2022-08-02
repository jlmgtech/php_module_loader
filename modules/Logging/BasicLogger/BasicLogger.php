<?php

class BasicLogger {

    public static function write($lvl, $msg) {
        $file = sprintf("%s/%s", __DIR__, "log.txt");
        $fp = fopen($file, "a");
        if (!$fp) {
            var_dump(error_get_last());
            throw new Exception("Could not open file $file");
        }
        fprintf($fp, "%s::%s::%s\n", date("Y-m-d H:i:s", strtotime("now")), $lvl, $msg);
        fclose($fp);
    }

    public static function log($msg) {
        self::write("LOG ", $msg);
    }

    public static function info($msg) {
        self::write("INFO", $msg);
    }

    public static function warn($msg) {
        self::write("WARN", $msg);
    }

    public static function error($msg) {
        self::write("FAIL", $msg);
    }

    public static function get_logs() {
        $fdata = trim(file_get_contents(__DIR__ . "/" . "log.txt"));
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
