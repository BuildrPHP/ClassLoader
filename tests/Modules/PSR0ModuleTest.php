<?php namespace BuildR\ClassLoader\Tests\Modules;

use BuildR\ClassLoader\ClassLoaderRegistry;
use BuildR\ClassLoader\Modules\PSR0\PSR0ClassLoaderModule;
use PSR0Vendor\Package\DummyClass;
use BuildR\ClassLoader\Tests\Fixtures\AnotherDummyNamespace\AnotherDummyClass;

class PSR0ModuleTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoaderRegistry
     */
    private $registry;

    /**
     * @type \BuildR\ClassLoader\Modules\PSR0\PSR0ClassLoaderModule
     */
    private $PSR0Module;

    public function setUp() {
        $this->registry = ClassLoaderRegistry::create();
        $this->PSR0Module = $this->registry->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/PSR0/PSR0ClassLoaderModule.php',
            PSR0ClassLoaderModule::class
        );

        parent::setUp();
    }

    public function tearDown() {
        $this->registry->unRegisterLoader();
        unset($this->PSR0Module, $this->registry);

        parent::tearDown();
    }

    public function testItRegisterNamespaces() {
        $this->PSR0Module->registerNamespace('Test\\Namespace', __DIR__);

        $this->assertTrue($this->PSR0Module->namespaceIsRegistered('Test\\Namespace'));
    }

    public function testNamespaceUnRegistration() {
        $this->PSR0Module->registerNamespace('Test\\Namespace', __DIR__);
        $this->PSR0Module->unRegisterNamespace('Test\\Namespace');

        $this->assertFalse($this->PSR0Module->namespaceIsRegistered('Test\\Namespace'));
    }

    /**
     * @expectedException \BuildR\ClassLoader\Modules\PSR0\PSR0ModuleException
     * @expectedExceptionMessage This namespace (Test\Namespace) is already registered!
     */
    public function testItThrowsExceptionWhenTryToRegisterSameNamespaceTwice() {
        $this->PSR0Module->registerNamespace('Test\\Namespace', __DIR__);
        $this->PSR0Module->registerNamespace('Test\\Namespace', __DIR__);
    }

    public function testItLoadFilesCorrectly() {
        $this->PSR0Module->registerNamespace(
            'PSR0Vendor\\Package',
            __DIR__ . DIRECTORY_SEPARATOR . '../Fixtures/PSR0DummyNamespace'
        );

        $this->assertTrue($this->PSR0Module->load(DummyClass::class));
        $this->assertFalse($this->PSR0Module->load(AnotherDummyClass::class));
    }

    public function testIsReturningFalseWhenNoNamespaceRegistered() {
        $r = $this->PSR0Module->load('NonExistingVendor\\Namespace\\ClassName');

        $this->assertFalse($r);
    }

}
