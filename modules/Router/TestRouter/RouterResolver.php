<?php


class RouterResolver {

    public $routes = [];
    public $current = NULL;

    protected function set_current(RouterRoute $route) {
        $this->current = $route;
    }
    public function get_current() {
        return $this->current;
    }

    public function set(string $pattern, $callback) {
        throw new Exception(__METHOD__ . " not implemented");
    }
    public function get(string $path) {
        throw new Exception(__METHOD__ . " not implemented");
    }

}
