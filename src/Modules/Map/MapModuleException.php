<?php namespace BuildR\ClassLoader\Modules\Map;

use \Exception;

/**
 * Common exceptions for Class Map class loader module
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules\Map
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 */
class MapModuleException extends Exception {

    const MESSAGE_MAP_NAME_OCCUPIED = "The following map name is already occupied: %s";

    /**
     * Used when try to register map with name that already registered in the loader
     *
     * @param string $mapName The map name
     *
     * @return static
     */
    public static function mapNameOccupied($mapName) {
        return new static(sprintf(static::MESSAGE_MAP_NAME_OCCUPIED, $mapName));
    }

    /**
     * @inheritDoc
     */
    public function __construct($message = "", $code = 0, Exception $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
