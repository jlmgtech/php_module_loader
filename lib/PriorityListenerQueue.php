<?php
namespace module_loader;

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
