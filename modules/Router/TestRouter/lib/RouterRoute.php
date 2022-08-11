<?php

class RouterRoute {

    private $payload = NULL; // callable | string | NULL
    private $module = NULL;
    private $driver = NULL;

    public function __construct(string $module, string $driver, $payload) {
        $this->module = $module;
        $this->driver = $driver;
        $this->payload = $payload;
    }

    public function get_payload() {
        return $this->payload;
    }
    public function get_module(): string {
        return $this->module ?: NULL;
    }
    public function get_driver(): string {
        return $this->driver ?: NULL;
    }

}
