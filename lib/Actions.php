<?php

require_once __DIR__ . "/" . "PriorityListenerQueue.php";

class Actions {

    /// Map<String, Array<PriorityListenerQueue>>
    private static $_actions = [];

    /// add listener on a hook and return the number of other listeners at that same priority
    public static function on(string $name, callable $func, $priority=10): int
    {
        self::$_actions[$name] = self::$_actions[$name] ?? new \module_loader\PriorityListenerQueue();
        return self::$_actions[$name]->push($func, $priority);
    }

    /// remove all references to the function <func> from the topic and return the number removed (usually one)
    public static function remove_action(string $name, callable $func): int
    {
        if (isset(self::$_actions[$name])) {
            return self::$_actions[$name]->remove($func);
        } else {
            return 0;
        }
    }

    /// run all listeners for a given hook
    public static function trigger(string $name, ...$args)
    {
        $output = "";
        if (isset(self::$_actions[$name])) {
            foreach (self::$_actions[$name]->all() as $listener) {
                $str = $listener(...$args);
                if ($str !== NULL) {
                    $output .= $str;
                }
            }
        }
        return $output;
    }

}
