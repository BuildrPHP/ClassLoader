<?php namespace BuildR\ClassLoader\Tests\Fixtures\Modules;

use BuildR\ClassLoader\Modules\AbstractClassLoaderModule;

class DummyModule extends AbstractClassLoaderModule {

    public $priority = 100;

    /**
     * @inheritDoc
     */
    public static function getAdditionalModuleFiles() {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . 'preLoadTestFile.php',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getName() {
        return 'DummyModule';
    }

    /**
     * @inheritDoc
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * @inheritDoc
     */
    public function onRegistered() {
        if(!defined('DUMMY_MODULE_LOADED')) {
            define('DUMMY_MODULE_LOADED', 'TRUE');
        }
    }

    /**
     * @inheritDoc
     */
    public function load($className) {
        define('DUMMY_MODULE_LAST_LOADED_CLASS_' . strtoupper($className), $className);

        return TRUE;
    }

}
