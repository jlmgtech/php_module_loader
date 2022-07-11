<?php

class BasicLogger {
    public static function info($msg) {
        echo "BASICLOGGER.INFO  : '$msg'\n";
    }
    public static function warn($msg) {
        echo "BASICLOGGER.WARN  : '$msg'\n";
    }
    public static function error($msg) {
        echo "BASICLOGGER.ERROR : '$msg'\n";
    }
}
