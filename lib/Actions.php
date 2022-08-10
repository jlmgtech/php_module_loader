<?php

require_once __DIR__ . "/" . "PriorityListenerQueue.php";

class Actions {

    /// Map<String, Array<PriorityListenerQueue>>
    private static $_actions = [];

    private static $_current_action = "";
    private static $_current_driver = "";
    private static $_current_module = "";
    private static $_current_action_params = [];

    public static function current_action(): string {
        return self::$_current_action;
    }
    public static function current_driver(): string {
        return self::$_current_driver;
    }
    public static function current_module(): string {
        return self::$_current_module;
    }
    public static function current_action_params(): array {
        return self::$_current_action_params;
    }

    /// add listener on a hook and return the number of other listeners at that same priority
    public static function on(string $name, callable $func, $priority=10): int
    {
        $listener = new ActionListener(
            ModuleLoader::get_action_module(),
            ModuleLoader::get_action_driver(),
            $func
        );
        self::$_actions[$name] = self::$_actions[$name] ?? new \module_loader\PriorityListenerQueue();
        return self::$_actions[$name]->push($listener, $priority);
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
        //$old_action = self::$_current_action;
        //$old_action_params = self::$_current_action_params;

        self::$_current_action = $name;
        self::$_current_action_params = $args;

        $output = "";
        if (isset(self::$_actions[$name])) {
            foreach (self::$_actions[$name]->all() as $listener) {

                //$old_driver = self::$_current_driver;
                //$old_module = self::$_current_module;

                self::$_current_module = $listener->module;
                self::$_current_driver = $listener->driver;
                $func = $listener->func;

                $str = $func(...$args);
                if ($str !== NULL) {
                    $output .= $str;
                }

                //self::$_current_driver = $old_driver;
                //self::$_current_module = $old_module;

            }
        }

        //self::$_current_action = $old_action;
        //self::$_current_action_params = $old_action_params;

        return $output;
    }

}
