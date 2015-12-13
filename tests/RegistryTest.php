<?php namespace Buildr\ClassLoader\Tests;

use BuildR\ClassLoader\ClassLoaderRegistry;
use BuildR\ClassLoader\Exception\ModuleException;
use BuildR\ClassLoader\Tests\Fixtures\Modules\DummyModule;

class RegistryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoaderRegistry
     */
    private $registry;

    public function setUp() {
        $this->registry = ClassLoaderRegistry::create();

        parent::setUp();
    }

    public function tearDown() {
        $this->registry->unRegisterLoader();
        unset($this->registry);

        parent::tearDown();
    }

    public function testItRegistersTheSPLLoaderCorrectly() {
        $functions = spl_autoload_functions();
        $registryClass = ClassLoaderRegistry::class;
        $found = FALSE;

        foreach($functions as $loaders) {
            if(isset($loaders[0]) && ($loaders[0] instanceof $registryClass)) {
                $found = TRUE;
            }
        }

        $this->assertTrue($found);
    }

    public function testModuleRegistration() {
        $this->loadDummyModule();

        $moduleStack = $this->registry->getModuleStack();

        //Module is loaded
        $this->assertArrayHasKey(100, $moduleStack);

        //onRegister() method is called
        $this->assertTrue(defined('DUMMY_MODULE_LOADED'));
        $this->assertEquals('TRUE', DUMMY_MODULE_LOADED);
    }

    /**
     * @expectedException \BuildR\ClassLoader\Exception\ClassLoaderException
     * @expectedExceptionMessage The priority cannot be increased more!
     */
    public function testItTriggersErrorWhenModuleLoadedWithSamePriority() {
        //Use @ (error suppression operator) to prevent PHPUnit convert
        //triggered errors to exceptions.

        @$this->loadDummyModule();
        @$this->loadDummyModule();
        @$this->loadDummyModule();
        @$this->loadDummyModule();
        @$this->loadDummyModule();
        @$this->loadDummyModule();

        //Here is the point that the exception will thrown, because the default priority
        //cannot be increased more.
        @$this->loadDummyModule();
    }

    public function testThePriorityOverrideWorks() {
        $this->loadDummyModule();
        $this->loadDummyModule(110);

        $moduleStack = $this->registry->getModuleStack();

        $this->assertArrayHasKey(110, $moduleStack);
        $this->assertInstanceOf(DummyModule::class, $moduleStack[110]);
    }

    public function testItThrowsExceptionWhenTryToRemoveNonRegisteredModule() {
        $this->loadDummyModule();
        $exceptionCaught = FALSE;

        try {
            $this->registry->removeModule(DummyModule::getName(), 110);
        } catch(ModuleException $e) {
            $this->assertEquals('Name: DummyModule Priority: 110', $e->getModuleClass());
            $exceptionCaught = TRUE;
        } finally {
            if(!$exceptionCaught) {
                $this->fail('Removing Non exist module not thrown an exception: Method: ' . __METHOD__);
            }
        }
    }

    public function testItRemovesModuleCorrectly() {
        $this->loadDummyModule();
        $this->registry->removeModule(DummyModule::getName(), 100);
        $moduleStack = $this->registry->getModuleStack();

        $this->assertCount(0, $moduleStack);
    }

    public function testItThrowsExceptionWhenTrToGetNonExistingModule() {
        $this->loadDummyModule();
        $exceptionCaught = FALSE;

        try {
            $this->registry->getModule(DummyModule::getName(), 110);
        } catch(ModuleException $e) {
            $this->assertEquals('Name: DummyModule', $e->getModuleClass());
            $exceptionCaught = TRUE;
        } finally {
            if(!$exceptionCaught) {
                $this->fail('Get Non existing module not thrown an exception: Method: ' . __METHOD__);
            }
        }
    }

    public function testItRetrievesSingleModuleCorrectly() {
        $this->loadDummyModule();
        $module = $this->registry->getModule(DummyModule::getName(), 100);

        $this->assertInstanceOf(DummyModule::class, $module);
    }

    public function testItLoadFiles() {
        $this->loadDummyModule();

        $result = $this->registry->loadClass('Foo\\Bar');

        $this->assertTrue($result);
        $this->assertTrue(defined('DUMMY_MODULE_LAST_LOADED_CLASS_FOO\BAR'));
    }

    public function testItLoadFilesWithNoModuleRegistered() {
        $result = $this->registry->loadClass('Foo\\Bar');

        $this->assertFalse($result);
    }

    private function loadDummyModule($priority = NULL) {
        $this->registry->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures/Modules/DummyModule.php',
            DummyModule::class,
            $priority
        );
    }

}
