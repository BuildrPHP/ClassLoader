<?php namespace BuildR\ClassLoader\Modules\Transformable;

use \Exception;

/**
 * Transformable class loader module exceptions
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules\Transformable
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 *
 * @codeCoverageIgnore
 */
class TransformableModuleException extends Exception {

    const MESSAGE_NAME_OCCUPIED = "Transformer is already registered with name: %s";

    /**
     * Used when try to register transformer with same name
     *
     * @param string $name The transformer name
     *
     * @return static
     */
    public static function nameOccupied($name) {
        return new static(sprintf(static::MESSAGE_NAME_OCCUPIED, $name));
    }

    /**
     * @inheritDoc
     */
    public function __construct($message = "", $code = 0, Exception $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
