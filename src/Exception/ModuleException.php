<?php namespace BuildR\ClassLoader\Exception;

use \Exception;

/**
 * Collection of exceptions when thrown in various class.
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Exception
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 *
 * @codeCoverageIgnore
 */
class ModuleException extends Exception {

    const MESSAGE_MODULE_INVALID = "Invalid Class Loader Module!";

    const MESSAGE_MODULE_NOT_FOUND = "The given class loader not found!";

    /**
     * @type string
     */
    private $moduleClass;

    public static function invalid($moduleClass) {
        return new self(self::MESSAGE_MODULE_INVALID, $moduleClass);
    }

    public static function notFound($moduleClass) {
        return new self(self::MESSAGE_MODULE_INVALID, $moduleClass);
    }

    public function __construct($message = "", $className, $code = 0, Exception $previous = NULL) {
        $this->moduleClass = $className;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Return the module FQCN.
     *
     * @return string
     */
    public function getModuleClass() {
        return $this->moduleClass;
    }

}
