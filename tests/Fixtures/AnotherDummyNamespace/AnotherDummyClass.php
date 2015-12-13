<?php namespace BuildR\ClassLoader\Tests\Fixtures\AnotherDummyNamespace;

class AnotherDummyClass {

    public function __construct() {
        define('ANOTHER_DUMMY_CLASS_LOADED', 'TRUE');
    }

}
