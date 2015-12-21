<?php namespace BuildR\ClassLoader\Tests;

use BuildR\ClassLoader\ClassLoaderInitializer;

class ClassLoaderInitializerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @type \BuildR\ClassLoader\ClassLoaderInitializer
     */
    private $initializer;

    public function setUp() {
        $this->initializer = new ClassLoaderInitializer();

        parent::setUp();
    }

    public function tearDown() {
        unset($this->initializer);

        parent::tearDown();
    }

    public function testInitializerShouldBeExtendable() {
        $this->initializer->extend([
            str_replace('/', DIRECTORY_SEPARATOR, 'Modules/PEAR/PEARClassLoaderModule.php'),
        ]);
    }

    public function testFilesLoadedCorrectly() {
        $this->initializer->load();
        $neededFiles = ClassLoaderInitializer::getLoadedFiles();
        $allLoadedFile = get_included_files();
        $foundFiles = [];

        foreach($allLoadedFile as $loadedFile) {
            foreach($neededFiles as $neededFile) {
                if(stripos($loadedFile, 'src' . DIRECTORY_SEPARATOR . ltrim($neededFile, '.')) !== FALSE) {
                    $foundFiles[] = $loadedFile;
                }
            }
        }

        $this->assertCount(count($neededFiles), $foundFiles);
    }

    public function testIsTriggerNoticeWhenItsAlreadyLoaded() {
        $self = $this;

        set_error_handler(function($errNo, $errStr) use (&$self) {
            $self->assertEquals(E_USER_NOTICE, $errNo);
            $self->assertEquals('Unable to load ClassLoader because its already loaded!', $errStr);
        });

        $this->initializer->load();
        $this->initializer->load();

        restore_error_handler();
    }

    public function testItTriggersErrorWhenTryToExtendLoadedInitializer() {
        $self = $this;

        set_error_handler(function($errNo, $errStr) use (&$self) {
            $self->assertEquals(E_USER_NOTICE, $errNo);
            $self->assertEquals('The initializer is loaded, so you cannot extend a loaded initializer!', $errStr);
        });

        $this->initializer->load();
        $this->initializer->extend([]);

        restore_error_handler();
    }

    public function testIsSetsLoadedFlagCorrectly() {
        $this->assertFalse($this->initializer->isLoaded());

        $this->initializer->load();

        $this->assertTrue($this->initializer->isLoaded());
    }

    public function testIsReturnTheFileStackCorrectly() {
        $allFiles = ClassLoaderInitializer::getLoadedFiles();

        $this->assertTrue(is_array($allFiles));
        $this->assertCount(count($allFiles), $allFiles);
    }

}
