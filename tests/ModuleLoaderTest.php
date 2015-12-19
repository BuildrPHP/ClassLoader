<?php namespace BuildR\ClassLoader\Tests;

use BuildR\ClassLoader\ModuleLoader;
use BuildR\ClassLoader\Tests\Fixtures\Modules\DummyModule;
use BuildR\ClassLoader\Tests\Fixtures\Modules\WrongModule;

class ModuleLoaderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ModuleLoader
     */
    protected $moduleLoader;

    public function setUp() {
        $this->moduleLoader = new ModuleLoader();
        parent::setUp();
    }

    /**
     * @expectedException \BuildR\ClassLoader\Exception\ModuleException
     * @expectedExceptionMessage Invalid Class Loader Module!
     */
    public function testItThrowsExceptionWithWrongModule() {
        include_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures/Modules/WrongModule.php';
        $this->moduleLoader->preLoad(WrongModule::class);
    }

    public function testIsPreLoadingTheModuleFiles() {
        include_once __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures/Modules/DummyModule.php';
        $this->moduleLoader->preLoad(DummyModule::class);

        $this->assertTrue(function_exists('preLoadTest'));
        $this->assertEquals('Hello Class PreLoader', preLoadTest());
    }

}
