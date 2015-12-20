<?php namespace BuildR\ClassLoader\Modules\PEAR;

use \Exception;

/**
 * Exception class that used by PEAR class loader module
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules\PEAR
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 *
 * @codeCoverageIgnore
 */
class PEARModuleException extends Exception {

    const MESSAGE_PREFIX_OCCUPIED = 'This prefix (%s) is already occupied!';

    /**
     * Creates a new instance of this exception when is thrown when trying to register
     * the same prefix twice into loader
     *
     * @param string $prefix The registered prefix
     *
     * @return static
     */
    public static function prefixOccupied($prefix) {
        return new static(sprintf(static::MESSAGE_PREFIX_OCCUPIED, $prefix));
    }

    /**
     * @inheritDoc
     */
    public function __construct($message = "", $code = 0, Exception $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
