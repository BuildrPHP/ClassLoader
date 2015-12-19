<?php namespace BuildR\ClassLoader\Tests\Modules;

use BuildR\ClassLoader\ClassLoaderRegistry;
use BuildR\ClassLoader\Modules\PEAR\PEARClassLoaderModule;
use Test_Module_DummyClass as DummyClass;
use BuildR\ClassLoader\Tests\Fixtures\AnotherDummyNamespace\AnotherDummyClass;

class PEARModuleTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoaderRegistry
     */
    private $registry;

    /**
     * @type \BuildR\ClassLoader\Modules\PEAR\PEARClassLoaderModule
     */
    private $PEARModule;

    public function setUp() {
        $this->registry = ClassLoaderRegistry::create();
        $this->PEARModule = $this->registry->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/PEAR/PEARClassLoaderModule.php',
            PEARClassLoaderModule::class
        );

        parent::setUp();
    }

    public function tearDown() {
        $this->registry->unRegisterLoader();
        unset($this->PEARModule, $this->registry);

        parent::tearDown();
    }

    public function testItRegisterPrefixes() {
        $this->PEARModule->registerPrefix('Test_', __DIR__);

        $this->assertTrue($this->PEARModule->prefixIsRegistered('Test_'));
    }

    public function testPrefixUnRegistration() {
        $this->PEARModule->registerPrefix('Test_', __DIR__);
        $this->PEARModule->unregisterPrefix('Test_');

        $this->assertFalse($this->PEARModule->prefixIsRegistered('Test_'));
    }


    /**
     * @expectedException \BuildR\ClassLoader\Modules\PEAR\PEARModuleException
     * @expectedExceptionMessage This prefix (Test_) is already occupied!
     */
    public function testItThrowsExceptionWhenTryToRegisterSameNamespaceTwice() {
        $this->PEARModule->registerPrefix('Test_', __DIR__);
        $this->PEARModule->registerPrefix('Test_', __DIR__);
    }

    public function testItLoadFilesCorrectly() {
        $this->PEARModule->registerPrefix(
            'Test_',
            __DIR__ . DIRECTORY_SEPARATOR . '../Fixtures/PEAR/Test'
        );

        $this->assertTrue($this->PEARModule->load(DummyClass::class));
        $this->assertFalse($this->PEARModule->load(AnotherDummyClass::class));
    }

    public function testIsReturningFalseWhenNoNamespaceRegistered() {
        $r = $this->PEARModule->load('UnregisteredPrefix_Module_ClassName');

        $this->assertFalse($r);
    }

}
