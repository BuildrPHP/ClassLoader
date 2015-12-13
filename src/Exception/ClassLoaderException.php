<?php namespace BuildR\ClassLoader\Exception;

use \Exception;

/**
 * Collection of exceptions when thrown in ClassLoaderRegistry.
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
class ClassLoaderException extends Exception {

    const MESSAGE_PRIORITY_INCREASE_OVERLOAD = "The priority cannot be increased more!";

    public static function priorityIncreaseLimit() {
        return new self(self::MESSAGE_PRIORITY_INCREASE_OVERLOAD);
    }

    public function __construct($message = "", $code = 0, \Exception $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
