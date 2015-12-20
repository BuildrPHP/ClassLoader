<?php namespace BuildR\ClassLoader\Modules\Map;

use BuildR\ClassLoader\Modules\AbstractClassLoaderModule;
use BuildR\ClassLoader\Modules\Map\MapModuleException;

/**
 * Class map class loader module
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
class MapClassLoaderModule extends AbstractClassLoaderModule {

    /**
     * @type array
     */
    private $registeredMaps = [];

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getAdditionalModuleFiles() {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . 'MapModuleException.php',
        ];
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getName() {
        return 'MapClassLoaderModule';
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getPriority() {
        return 5;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function onRegistered() {}

    /**
     * @inheritDoc
     */
    public function load($className) {
        if(count($this->registeredMaps) < 1) {
            return FALSE;
        }

        foreach($this->registeredMaps as $mapName => $map) {
            if(isset($map[$className])) {
                $classFile = $map[$className];

                if(file_exists($classFile)) {
                    include_once $classFile;

                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    /**
     * Register a new map in the module
     *
     * @param string $mapName The map unique name
     * @param array $map The actual class map
     *
     * @throws \BuildR\ClassLoader\Modules\Map\MapModuleException
     */
    public function registerMap($mapName, $map) {
        if($this->mapIsRegistered($mapName)) {
            throw MapModuleException::mapNameOccupied($mapName);
        }

        $this->registeredMaps[$mapName] = (array) $map;
    }

    /**
     * Remove the given map from the loader
     *
     * @param string $mapName The map name
     *
     * @return bool
     */
    public function removeMap($mapName) {
        if(!$this->mapIsRegistered($mapName)) {
            return FALSE;
        }

        unset($this->registeredMaps[$mapName]);

        return TRUE;
    }

    /**
     * Determines that the given map is registered or not
     *
     * @param string $map The map name
     *
     * @return bool
     */
    public function mapIsRegistered($map) {
        return isset($this->registeredMaps[$map]);
    }

}
