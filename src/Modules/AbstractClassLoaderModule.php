<?php namespace BuildR\ClassLoader\Modules;

/**
 * Abstract class loader module, basically fore the priority changing mechanism.
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
abstract class AbstractClassLoaderModule implements ClassLoaderModuleInterface {

    /**
     * @type int
     */
    public $priority = 10;

    /**
     * @inheritDoc
     */
    public function setPriority($priority) {
        $this->priority = (int) $priority;
    }

}
