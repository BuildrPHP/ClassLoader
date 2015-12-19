<?php namespace BuildR\ClassLoader\Modules\PSR4;

use \Exception;

/**
 * PSR-4 Class loader module exceptions
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules\PSR4
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 *
 * @codeCoverageIgnore
 */
class PSR4ModuleException extends Exception {

    const MESSAGE_NAMESPACE_OCCUPIED = 'This namespace (%s) is already registered!';

    /**
     * Throws a new namespace occupied exception
     *
     * @param string $namespace The occupied namespace name
     *
     * @return \BuildR\ClassLoader\Modules\PSR4\PSR4ModuleException
     */
    public static function namespaceOccupied($namespace) {
        return new static(sprintf(static::MESSAGE_NAMESPACE_OCCUPIED, $namespace));
    }

    public function __construct($message = "", $code = 0, Exception $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
