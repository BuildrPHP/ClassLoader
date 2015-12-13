<?php namespace Buildr\ClassLoader\Tests;

use BuildR\ClassLoader\ClassLoaderInitializer;

class ClassLoaderInitializerTest extends \PHPUnit_Framework_TestCase {

    public function testFilesLoadedCorrectly() {
        ClassLoaderInitializer::load();
        $neededFiles = ClassLoaderInitializer::$files;
        $allLoadedFile = get_included_files();
        $foundFiles = [];

        foreach($allLoadedFile as $loadedFile) {
            foreach($neededFiles as $neededFile) {
                if(stripos($loadedFile, $neededFile) !== FALSE) {
                    $foundFiles[] = $neededFile;
                }
            }
        }

        $this->assertCount(count($neededFiles), $foundFiles);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Notice
     */
    public function testIsTriggerNoticeWhenItsAlreadyLoaded() {
        ClassLoaderInitializer::load();
    }

}
