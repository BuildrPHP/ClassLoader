<?php namespace BuildR\ClassLoader\Tests;

use BuildR\ClassLoader\ClassLoaderInitializer;

class ClassLoaderInitializerTest extends \PHPUnit_Framework_TestCase {

    public function testInitializerShouldBeExtendable() {
        ClassLoaderInitializer::extend([
            str_replace('/', DIRECTORY_SEPARATOR, '../tests/Fixtures/AnotherDummyNamespace/AnotherDummyClass.php'),
        ]);
    }

    public function testFilesLoadedCorrectly() {
        ClassLoaderInitializer::load();
        $neededFiles = ClassLoaderInitializer::$files;
        $allLoadedFile = get_included_files();
        $foundFiles = [];

        foreach($allLoadedFile as $loadedFile) {
            foreach($neededFiles as $neededFile) {
                if(stripos($loadedFile, ltrim($neededFile, '.')) !== FALSE) {
                    $foundFiles[] = $neededFile;
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

        //Actual test
        ClassLoaderInitializer::load();

        restore_error_handler();
    }

    public function testItTriggersErrorWhenTryToExtendLoadedInitializer() {
        $self = $this;

        set_error_handler(function($errNo, $errStr) use (&$self) {
            $self->assertEquals(E_USER_NOTICE, $errNo);
            $self->assertEquals('The initializer is loaded, so you cannot extend a loaded initializer!', $errStr);
        });

        //Actual test
        ClassLoaderInitializer::extend([]);

        restore_error_handler();
    }

    public function testIsReturnTheFileStackCorrectly() {
        $allFiles = ClassLoaderInitializer::getLoadedFiles();

        $this->assertTrue(is_array($allFiles));
        $this->assertCount(count($allFiles), $allFiles);
    }

}
