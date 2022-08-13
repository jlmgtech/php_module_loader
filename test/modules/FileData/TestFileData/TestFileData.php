<?php

class FileData {

    private static function file_parts(string $file): array {
        $dir = __DIR__ . "/data/" . Utils::clean_path(dirname($file));
        $file = basename($file);
        $path = $dir . "/" . $file;
        return [$dir, $file, $path];
    }

    public static function append(string $file, string $data) {
        self::write($file, $data, "a");
    }

    public static function write(string $file, string $data, $mode="w") {
        if (!is_string($mode))
            throw new Exception("Mode must be a string");

        list($dir, $file, $path) = self::file_parts($file);
        if (!$file) {
            throw new Exception("Cannot write to a directory");
        }

        // show unix user
        module_log("WARN",  "User: " . get_current_user());

        if (!is_dir($dir))
            mkdir($dir, 0755, true);
        $fd = fopen($path, $mode);
        if (!$fd) {
            throw new Exception("Cannot open file for writing");
        }
        $nwrote = fwrite($fd, $data);
        if ($nwrote != strlen($data)) {
            throw new Exception("Cannot write to file");
        }
        fclose($fd);
    }

    public static function read(string $file) {
        list($dir, $file, $path) = self::file_parts($file);
        return file_get_contents($path);
    }

    public static function ls(string $dir) {
        $dir = __DIR__ . "/data/" . Utils::clean_path($dir);
        $files = scandir($dir);
        // remove hidden files
        $files = array_filter($files, function($file) {
            return $file[0] !== ".";
        });
        return $files;
    }

}
