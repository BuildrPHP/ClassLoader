<?php namespace BuildR\ClassLoader\Tests\Modules;

use BuildR\ClassLoader\ClassLoader;
use BuildR\ClassLoader\Modules\Transformable\TransformableClassLoaderModule;
use BuildR\ClassLoader\Tests\Fixtures\AnotherDummyNamespace\AnotherDummyClass;
use Vendor_Package_DummyClass as DummyClass;

class TransformableModuleTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoader
     */
    private $classLoader;

    /**
     * @type \BuildR\ClassLoader\Modules\Transformable\TransformableClassLoaderModule
     */
    private $TransformableModule;

    public function setUp() {
        $this->classLoader = ClassLoader::create();
        $this->TransformableModule = $this->classLoader->loadModule(
            __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/Transformable/TransformableClassLoaderModule.php',
            TransformableClassLoaderModule::class
        );

        parent::setUp();
    }

    public function tearDown() {
        $this->classLoader->unRegisterLoader();
        unset($this->TransformableModule, $this->classLoader);

        parent::tearDown();
    }

    private function getDummyTransformer() {
        return [
            'dummyTransformer',
            function($className) {
                $nsParts = explode('_', $className);
                $folder = realpath(__DIR__  . '/..' . '/Fixtures/Transformable') . DIRECTORY_SEPARATOR;
                array_pop($nsParts);
                $folder = $folder . implode(DIRECTORY_SEPARATOR, $nsParts);
                $file = $folder . DIRECTORY_SEPARATOR . $className . '.php';

                return $file;
            },
        ];
    }

    public function testItRegisterTransformers() {
        list($name, $transformer) = $this->getDummyTransformer();
        $this->TransformableModule->registerTransformer($name, $transformer);

        $this->assertTrue($this->TransformableModule->transformerIsRegistered($name));
    }

    public function testTransformerUnRegistration() {
        list($name, $transformer) = $this->getDummyTransformer();
        $this->TransformableModule->registerTransformer($name, $transformer);
        $resultExisting = $this->TransformableModule->removeTransformer($name);
        $resultNonExisting = $this->TransformableModule->removeTransformer('NonRegistered');

        $this->assertFalse($resultNonExisting);
        $this->assertTrue($resultExisting);
        $this->assertFalse($this->TransformableModule->transformerIsRegistered($name));
    }

    /**
     * @expectedException \BuildR\ClassLoader\Modules\Transformable\TransformableModuleException
     * @expectedExceptionMessage Transformer is already registered with name: dummyTransformer
     */
    public function testItThrowsExceptionWhenTryToRegisterSameTransformerNameTwice() {
        list($name, $transformer) = $this->getDummyTransformer();
        $this->TransformableModule->registerTransformer($name, $transformer);
        $this->TransformableModule->registerTransformer($name, $transformer);
    }

    public function testItLoadFilesCorrectly() {
        list($name, $transformer) = $this->getDummyTransformer();
        $this->TransformableModule->registerTransformer($name, $transformer);

        $this->assertTrue($this->TransformableModule->load(DummyClass::class));
        $this->assertFalse($this->TransformableModule->load(AnotherDummyClass::class));
    }

    public function testIsReturningFalseWhenNoTransformerRegistered() {
        $r = $this->TransformableModule->load('UnregisteredPrefix_Module_ClassName');

        $this->assertFalse($r);
    }

}
