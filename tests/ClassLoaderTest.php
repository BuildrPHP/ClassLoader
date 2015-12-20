<?php namespace BuildR\ClassLoader\Tests;

use BuildR\ClassLoader\ClassLoader;
use BuildR\ClassLoader\Exception\ModuleException;
use BuildR\ClassLoader\Tests\Fixtures\Modules\DummyModule;

class ClassLoaderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\
     */
    private $classLoader;

    public function setUp() {
        $this->classLoader = ClassLoader::create();
        $this->classLoader->registerLoader();

        parent::setUp();
    }

    public function tearDown() {
        $this->classLoader->unRegisterLoader();
        unset($this->classLoader);

        parent::tearDown();
    }

    public function testItRegistersTheSPLLoaderCorrectly() {
        $functions = spl_autoload_functions();
        $loaderClass = ClassLoader::class;
        $found = FALSE;

        foreach($functions as $loaders) {
            if(isset($loaders[0]) && ($loaders[0] instanceof $loaderClass)) {
                $found = TRUE;
            }
        }

        $this->assertTrue($found);
    }

    public function testModuleRegistration() {
        $this->loadDummyModule();

        $moduleStack = $this->classLoader->getModuleStack();

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

        $moduleStack = $this->classLoader->getModuleStack();

        $this->assertArrayHasKey(110, $moduleStack);
        $this->assertInstanceOf(DummyModule::class, $moduleStack[110]);
    }

    public function testItThrowsExceptionWhenTryToRemoveNonRegisteredModule() {
        $this->loadDummyModule();
        $exceptionCaught = FALSE;

        try {
            $this->classLoader->removeModule(DummyModule::getName(), 110);
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
        $this->classLoader->removeModule(DummyModule::getName(), 100);
        $moduleStack = $this->classLoader->getModuleStack();

        $this->assertCount(0, $moduleStack);
    }

    public function testItThrowsExceptionWhenTrToGetNonExistingModule() {
        $this->loadDummyModule();
        $exceptionCaught = FALSE;

        try {
            $this->classLoader->getModule(DummyModule::getName(), 110);
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
        $module = $this->classLoader->getModule(DummyModule::getName(), 100);

        $this->assertInstanceOf(DummyModule::class, $module);
    }

    public function testItLoadFiles() {
        $this->loadDummyModule();

        $result = $this->classLoader->loadClass('Foo\\Bar');

        $this->assertTrue($result);
        $this->assertTrue(defined('DUMMY_MODULE_LAST_LOADED_CLASS_FOO\BAR'));
    }

    public function testItLoadFilesWithNoModuleRegistered() {
        $result = $this->classLoader->loadClass('Foo\\Bar');

        $this->assertFalse($result);
    }

    private function loadDummyModule($priority = NULL) {
        $this->classLoader->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures/Modules/DummyModule.php',
            DummyModule::class,
            $priority
        );
    }

}
