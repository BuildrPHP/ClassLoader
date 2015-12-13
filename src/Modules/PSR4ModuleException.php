<?php namespace BuildR\ClassLoader\Modules;

use \Exception;

class PSR4ModuleException extends Exception {

    const MESSAGE_NAMESPACE_OCCUPIED = 'This namespace (%s) is already registered!';

    public static function namespaceOccupied($namespace) {
        return new self(sprintf(self::MESSAGE_NAMESPACE_OCCUPIED, $namespace));
    }

    public function __construct($message = "", $code = 0, \Exception $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
