<?php namespace BuildR\ClassLoader\Tests\Fixtures\Map;

class DummyClass {

    public function __construct() {
        define('MAP_MODULE_DUMMY_CLASS_REGISTERED', 'TRUE');
    }

}
