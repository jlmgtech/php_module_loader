<?php

class ActionListener {
    public $module = "";
    public $driver = "";
    public $func = "";
    public function __construct(string $module, string $driver, callable $func) {
        $this->module = $module;
        $this->driver = $driver;
        $this->func = $func;
    }
}
