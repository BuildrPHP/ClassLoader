<?php namespace BuildR\ClassLoader\Tests\Modules;

use BuildR\ClassLoader\Modules\Map\MapClassLoaderModule;
use BuildR\ClassLoader\Tests\Fixtures\Map\DummyClass;
use BuildR\ClassLoader\Tests\Fixtures\AnotherDummyNamespace\AnotherDummyClass;

class MapModuleTest extends AbstractModuleTestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoader
     */
    protected $classLoader;

    /**
     * @type \BuildR\ClassLoader\Modules\Map\MapClassLoaderModule
     */
    private $MapModule;

    public function setUp() {
        parent::setUp();

        $this->MapModule = $this->classLoader->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/Map/MapClassLoaderModule.php',
            MapClassLoaderModule::class
        );
    }

    public function tearDown() {
        $this->classLoader->unRegisterLoader();
        unset($this->MapModule, $this->classLoader);

        parent::tearDown();
    }

    public function createDummyMap() {
        return [
            'testMap',
            [
                DummyClass::class => realpath(__DIR__ . '/../Fixtures/Map') . DIRECTORY_SEPARATOR . 'DummyClass.php',
                AnotherDummyClass::class => realpath(__DIR__ . '/../Fixtures/Map') . '/AnotherDummyClass.php',
            ],
        ];
    }

    public function testItRegisterMaps() {
        list($mapName, $map) = $this->createDummyMap();
        $this->MapModule->registerMap($mapName, $map);

        $this->assertTrue($this->MapModule->mapIsRegistered($mapName));
    }

    public function testMapUnRegistration() {
        list($mapName, $map) = $this->createDummyMap();

        //Remove existing map
        $this->MapModule->registerMap($mapName, $map);
        $removeResult = $this->MapModule->removeMap($mapName);

        //Remove non-existong map
        $removeResultNonExisting = $this->MapModule->removeMap('NonExistingMap');


        $this->assertTrue($removeResult);
        $this->assertFalse($removeResultNonExisting);
        $this->assertFalse($this->MapModule->mapIsRegistered($mapName));
    }

    /**
     * @expectedException \BuildR\ClassLoader\Modules\Map\MapModuleException
     * @expectedExceptionMessage The following map name is already occupied: testMap
     */
    public function testItThrowsExceptionWhenTryToRegisterSameMapNameTwice() {
        list($mapName, $map) = $this->createDummyMap();
        $this->MapModule->registerMap($mapName, $map);
        $this->MapModule->registerMap($mapName, $map);
    }

    public function testItLoadFilesCorrectly() {
        list($mapName, $map) = $this->createDummyMap();
        $this->MapModule->registerMap($mapName, $map);

        $this->assertTrue($this->MapModule->load(DummyClass::class));
        $this->assertFalse($this->MapModule->load(AnotherDummyClass::class));
    }

    public function testIsReturnFalseWhenClassFileNotExist() {
        list($mapName, $map) = $this->createDummyMap();
        $this->MapModule->registerMap($mapName, $map);

        $this->assertFalse($this->MapModule->load(AnotherDummyClass::class));
    }

    public function testIsReturningFalseWhenNoMapRegistered() {
        $r = $this->MapModule->load('NonExistingVendor\\Namespace\\ClassName');

        $this->assertFalse($r);
    }

}
