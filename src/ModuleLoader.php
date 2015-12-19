<?php namespace BuildR\ClassLoader;

use BuildR\ClassLoader\Exception\ModuleException;
use BuildR\ClassLoader\Modules\ClassLoaderModuleInterface;

/**
 * This class is responsible for pre-loading module components
 * and creating the module instance.
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
class ModuleLoader {

    /**
     * Runs the given class loader module pre-loading (load dependencies) and
     * perform an initial check on module (Interface implementation).
     * If this success return a new instance from the module, otherwise
     * a ModuleException will be thrown.
     *
     * @param string $moduleClassName
     *
     * @return \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface
     *
     * @throws \BuildR\ClassLoader\Exception\ModuleException
     */
    public function preLoad($moduleClassName) {
        $interfaces = class_implements($moduleClassName);

        if(!in_array(ClassLoaderModuleInterface::class, $interfaces)) {
            throw ModuleException::invalid($moduleClassName);
        }

        $preloadedFiles = (array) call_user_func([$moduleClassName, 'getAdditionalModuleFiles']);

        foreach($preloadedFiles as $file) {
            include_once $file;
        }

        $module = new $moduleClassName;

        return $module;
    }

}
