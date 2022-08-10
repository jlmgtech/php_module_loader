<?php

class Utils {

    public static function clean_path(string $path): string {
        $result = trim(preg_replace('/\/+/', '/', $path), "/");
        return $result ?: "/";
    }

    public static function compose_callbacks(array $callbacks): callable {
        return function (...$args) use ($callbacks) {
            foreach ($callbacks as $callback) {
                if ($callback(...$args) === false)
                    return false;
            }
            return true;
        };
    }

    public static function path_has_extension(string $path, string $pattern): bool {
        $bname = basename($path);
        if ($pattern === "*") {
            return strpos($bname, ".") !== false;
        } else {
            return substr($bname, -strlen($pattern)) === $pattern;
        }
    }

    public static function apply_decorators($fns) {
        $output = array_pop($fns);
        while (count($fns)) {
            $fn = array_pop($fns);
            $output = $fn($output);
        }
        return $output;
    }

    // lol this could be more efficient...
    public static function to_slug(string $str): string {
        $output = "";
        for ($i = 0; $i < strlen($str); $i++) {
            if (ord($str[$i]) >= ord("A") && ord($str[$i]) <= ord("Z")) {
                $output .= "-";
            }
            $output .= strtolower($str[$i]);
        }
        return rawurlencode(trim($output, "-"));
    }

}
