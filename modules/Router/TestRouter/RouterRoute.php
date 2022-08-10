<?php

class RouterRoute {

    private $callback = NULL; // callable | string | NULL
    private $module = NULL;
    private $driver = NULL;

    public function __construct(string $module, string $driver, $callback) {
        $this->module = $module;
        $this->driver = $driver;
        $this->callback = $callback;
    }

    public function set_callback($callback) {
        $this->callback = $callback;
    }
    public function set_module(string $module) {
        $this->module = $module;
    }
    public function set_driver(string $driver) {
        $this->driver = $driver;
    }

    public function get_callback() {
        return $this->callback;
    }
    public function get_module(): string {
        return $this->module ?: NULL;
    }
    public function get_driver(): string {
        return $this->driver ?: NULL;
    }

}
