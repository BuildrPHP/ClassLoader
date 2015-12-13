<?php namespace BuildR\ClassLoader\Tests\Fixtures\DummyNamespace;

class DummyClass {

    public function __construct() {
        define('DUMMY_CLASS_LOADED', 'TRUE');
    }

}
