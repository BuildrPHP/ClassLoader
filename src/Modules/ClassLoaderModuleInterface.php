<?php namespace BuildR\ClassLoader\Modules;

/**
 * Common interface for class loader modules. This modules represents
 * specific auto-loading (naming) standards (e.g. PSR-0, PEAR, etc...)
 * or special auto-loading methods (like class maps).
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 */
interface ClassLoaderModuleInterface {

    /**
     * Returns a unique name for the laoder module
     *
     * @return string
     */
    public static function getName();

    /**
     * Set the module priority in loader stack. If the number is higher
     * the loader has lower priority.
     *
     * @return int
     */
    public function getPriority();

    /**
     * Called when the default priority is already reserved. The module loader
     * automatically try to increase the priority to find a new, free spot.
     *
     * @param int $priority The new priority value
     */
    public function setPriority($priority);

    /**
     * Listening method, called when the module loader finished with
     * the registration process.
     */
    public function onRegistered();

    /**
     * Try to load the given class. Return TRUE when its success or FALSE
     * when the class cannot be loaded.
     *
     * @param string $className The class that needs to be laoded
     *
     * @return bool
     */
    public function load($className);

}
