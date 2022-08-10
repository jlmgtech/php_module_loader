<?php
namespace module_loader;

require_once __DIR__ . "/" . "ActionListener.php";

class PriorityListenerQueue
{
    private $queue = [];

    public function push(\ActionListener $listener, int $priority): int
    {
        $this->queue[$priority] = $this->queue[$priority] ?? [];
        $this->queue[$priority][] = $listener;
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
        $listener = array_shift($this->queue[$highest]);
        return $listener->func;
    }

    public function remove(callable $target): int
    {
        $num_removed = 0;
        foreach ($this->queue as $priority => &$listeners) {
            $listeners = array_filter($listeners, function($listener) use($target, &$num_removed) {
                $is_target = $listener->func === $target;
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
