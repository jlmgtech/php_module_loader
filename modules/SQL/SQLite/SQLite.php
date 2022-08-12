<?php

class SQLite {

    private static $dbs = [];

    public static function create_db(string $name) {
        if (isset(self::$dbs[$name])) {
            return;
        }
        self::$dbs[$name] = new SQLite3(__DIR__ . "$name.db");
    }

    public static function drop_db(string $name) {
        if (!isset(self::$dbs[$name])) {
            return;
        }
        unset(self::$dbs[$name]);
        unlink(__DIR__ . "$name.db");
    }

    public static function clear_db(string $name) {
        self::drop_db($name);
        self::create_db($name);
    }

    public static function query(string $query, ...$args) {
        $result = self::$db->query($query);
        if (!$result) {
            throw new Exception(self::$db->lastErrorMsg());
        }
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            yield $row;
        }
    }

    public static function execute(string $query, ...$args) {
        $stmt = self::$db->prepare($query);
        if ($stmt === false) {
            throw new Exception(self::$db->lastErrorMsg());
        }
        foreach ($args as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();
    }

}
