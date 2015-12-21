<?php namespace BuildR\ClassLoader\Tests\Modules;

use BuildR\ClassLoader\ClassLoader;

class AbstractModuleTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoader
     */
    protected $classLoader;

    /**
     * @type \BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule
     */
    protected $PSR4Module;

    /**
     * @inheritDoc
     */
    protected function setUp() {
        $this->classLoader = ClassLoader::create();

        /** @type \BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule $module */
        $this->PSR4Module = $this->classLoader->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/PSR4/PSR4ClassLoaderModule.php',
            \BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule::class
        );

        $this->PSR4Module->registerNamespace('BuildR\\ClassLoader\\', __DIR__ . DIRECTORY_SEPARATOR . '../../src');

        $this->classLoader->registerLoader();

        parent::setUp();
    }

}
