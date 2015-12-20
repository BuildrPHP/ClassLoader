<?php namespace BuildR\ClassLoader;

/**
 * The class loader component initializer. Because the class loader not have
 * a loader, this class will help to load all files that needs to be loaded
 * before start using the class loader.
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 */
class ClassLoaderInitializer {

    /**
     * @type bool
     */
    protected static $isLoaded = FALSE;

    /**
     * The files that loaded during the initialization phase
     *
     * @type array
     */
    public static $files = [
        'Modules' . DIRECTORY_SEPARATOR . 'ClassLoaderModuleInterface.php',
        'Modules' . DIRECTORY_SEPARATOR . 'AbstractClassLoaderModule.php',
        'Exception' . DIRECTORY_SEPARATOR . 'ModuleException.php',
        'Exception' . DIRECTORY_SEPARATOR . 'ClassLoaderException.php',
        'ModuleLoader.php',
        'ClassLoader.php',
    ];

    /**
     * Extends the initializer internal file registry before initialize
     *
     * @param $additionalFile
     */
    public static function extend($additionalFile) {
        if(self::$isLoaded === TRUE) {
            trigger_error('The initializer is loaded, so you cannot extend a loaded initializer!', E_USER_NOTICE);
        }

        self::$files = array_merge(self::$files, (array) $additionalFile);
    }

    /**
     * Returns all files loaded by the initializer
     *
     * @return array
     */
    public static function getLoadedFiles() {
        return self::$files;
    }

    /**
     * Load all files that needs to use the class loader
     *
     * @return void
     */
    public static function load() {
        if(self::$isLoaded === TRUE) {
            trigger_error("Unable to load ClassLoader because its already loaded!", E_USER_NOTICE);
        }

        $rootDir = __DIR__ . DIRECTORY_SEPARATOR;
        ksort(self::$files);

        foreach(self::$files as $priority => $file) {
            $fileAbsolute = $rootDir . $file;

            include_once $fileAbsolute;
        }

        self::$isLoaded = TRUE;
    }

}
