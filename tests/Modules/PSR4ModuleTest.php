<?php namespace BuildR\ClassLoader\Tests\Modules;

use BuildR\ClassLoader\ClassLoaderRegistry;
use BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule;
use BuildR\ClassLoader\Tests\Fixtures\DummyNamespace\DummyClass;
use BuildR\ClassLoader\Tests\Fixtures\AnotherDummyNamespace\AnotherDummyClass;

class PSR4ModuleTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoaderRegistry
     */
    private $registry;

    /**
     * @type \BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule
     */
    private $PSR4Module;

    public function setUp() {
        $this->registry = ClassLoaderRegistry::create();
        $this->PSR4Module = $this->registry->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/PSR4/PSR4ClassLoaderModule.php',
            PSR4ClassLoaderModule::class
        );

        parent::setUp();
    }

    public function tearDown() {
        $this->registry->unRegisterLoader();
        unset($this->PSR4Module, $this->registry);

        parent::tearDown();
    }

    public function testItRegisterNamespaces() {
        $this->PSR4Module->registerNamespace('Test\\Namespace', __DIR__);

        $this->assertTrue($this->PSR4Module->namespaceIsRegistered('Test\\Namespace'));
    }

    public function testNamespaceUnRegistration() {
        $this->PSR4Module->registerNamespace('Test\\Namespace', __DIR__);
        $this->PSR4Module->unRegisterNamespace('Test\\Namespace');

        $this->assertFalse($this->PSR4Module->namespaceIsRegistered('Test\\Namespace'));
    }

    /**
     * @expectedException \BuildR\ClassLoader\Modules\PSR4\PSR4ModuleException
     * @expectedExceptionMessage This namespace (Test\Namespace) is already registered!
     */
    public function testItThrowsExceptionWhenTryToRegisterSameNamespaceTwice() {
        $this->PSR4Module->registerNamespace('Test\\Namespace', __DIR__);
        $this->PSR4Module->registerNamespace('Test\\Namespace', __DIR__);
    }

    public function testItLoadFilesCorrectly() {
        $this->PSR4Module->registerNamespace(
            'BuildR\\ClassLoader\\Tests\\Fixtures\\DummyNamespace',
            __DIR__ . DIRECTORY_SEPARATOR . '../Fixtures/DummyNamespace'
        );

        $this->assertTrue($this->PSR4Module->load(DummyClass::class));
        $this->assertFalse($this->PSR4Module->load(AnotherDummyClass::class));
    }

}
