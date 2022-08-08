<?php

require_once __DIR__ . "/" . "PriorityListenerQueue.php";

$_ACTIONS = [];

/// add listener on a hook and return the number of other listeners at that same priority
function on(string $name, callable $func, $priority=10): int
{
    global $_ACTIONS;
    $_ACTIONS[$name] = $_ACTIONS[$name] ?? new \module_loader\PriorityListenerQueue();
    return $_ACTIONS[$name]->push($func, $priority);
}

/// remove all references to the function <func> from the topic and return the number removed (usually one)
function remove_action(string $name, callable $func): int
{
    global $_ACTIONS;
    if (isset($_ACTIONS[$name])) {
        return $_ACTIONS[$name]->remove($func);
    } else {
        return 0;
    }
}

/// run all listeners for a given hook
function trigger(string $name, ...$args)
{
    global $_ACTIONS;
    $output = "";
    if (isset($_ACTIONS[$name])) {
        foreach ($_ACTIONS[$name]->all() as $listener) {
            $str = $listener(...$args);
            if ($str !== NULL) {
                $output .= $str;
            }
        }
    }
    return $output;
}

//function test_action_impl() {
//    function logname(string $name) {
//        echo "logname($name)\n";
//    }
//    function other(string $name) {
//        echo "other($name)\n";
//    }
//    add_hook_listener("display_name", "logname");
//    add_hook_listener("display_name", "other");
//    // now the actual code
//    dispatch_hook("display_name", "johnathan_byers");
//    remove_hook_listener("display_name", "logname");
//    dispatch_hook("display_name", "able_cain_jonas");
//}
//test_action_impl();
