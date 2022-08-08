<?php

class PriorityListenerQueue
{
    private $queue = [];

    public function push(callable $value, int $priority): int
    {
        $this->queue[$priority] = $this->queue[$priority] ?? [];
        $this->queue[$priority][] = $value;
        return count($this->queue[$priority]);
    }

    public function all(): \Generator
    {
        $keys = array_keys($this->queue);
        sort($keys);
        foreach ($keys as $priority) {
            foreach ($this->queue[$priority] as $listener) {
                yield $listener;
            }
        }
    }

    public function pop(): callable
    {
        $highest = max(array_keys($this->queue));
        return array_shift($this->queue[$highest]);
    }

    public function remove(callable $target): int
    {
        $num_removed = 0;
        foreach ($this->queue as $priority => &$listeners) {
            $listeners = array_filter($listeners, function($listener) use($target, &$num_removed) {
                $is_target = $listener === $target;
                if ($is_target) {
                    $num_removed++;
                    return false;
                } else {
                    return true;
                }
            });
        }
        return $num_removed;
    }
}

$_ACTIONS = [];

/// add listener on a hook and return the number of other listeners at that same priority
function on(string $name, callable $func, $priority=10): int
{
    global $_ACTIONS;
    $_ACTIONS[$name] = $_ACTIONS[$name] ?? new PriorityListenerQueue();
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
    //hook_read(); // clear buffer from extraneous use
    $output = "";
    if (isset($_ACTIONS[$name])) {
        foreach ($_ACTIONS[$name]->all() as $listener) {
            $str = $listener(...$args);
            if ($str !== NULL) {
                $output .= $str;
            }
        }
    }
    //return hook_read();
    return $output;
}

//$hook_fd = NULL;
//function hook_echo($msg) {
//    global $hook_fd;
//    if (!$hook_fd) {
//        $hook_fd = fopen("php://memory", "rw");
//    }
//    fwrite($hook_fd, $msg);
//}

//function hook_read(): string {
//    global $hook_fd;
//    if (!$hook_fd) {
//        return "";
//    }
//
//    fseek($hook_fd, 0, SEEK_END);
//    $size = ftell($hook_fd);
//    rewind($hook_fd);
//    $output = fread($hook_fd, $size);
//    fclose($hook_fd);
//    $hook_fd = NULL;
//    return $output;
//}

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
