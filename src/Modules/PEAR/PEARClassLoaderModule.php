<?php namespace BuildR\ClassLoader\Modules\PEAR;

use BuildR\ClassLoader\Modules\AbstractClassLoaderModule;
use BuildR\ClassLoader\Modules\PEAR\PEARModuleException;

/**
 * PEAR compatible class loader module.
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
 */
class PEARClassLoaderModule extends AbstractClassLoaderModule {

    /**
     * @type array
     */
    private $registeredPrefixes = [];

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getAdditionalModuleFiles() {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . 'PEARModuleException.php',
        ];
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getName() {
        return 'PEARClassLoaderModule';
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getPriority() {
        return 50;
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
        if(count($this->registeredPrefixes) < 1) {
            return FALSE;
        }

        foreach($this->registeredPrefixes as $singlePrefix) {
            $prefix = $singlePrefix[0];
            $basePath = $singlePrefix[1];

            $pos = strpos($className, $prefix);
            if($pos === FALSE || $pos > 0) {
                continue;
            }
            $pathNamespace = ltrim(substr($className, strlen($prefix)), '_');
            $file = $basePath . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $pathNamespace) . '.php';

            if(file_exists($file)) {
                include_once $file;

                return TRUE;
            }
        }

        return FALSE;
    }

    public function registerPrefix($prefix, $basePath) {
        if($this->prefixIsRegistered($prefix)) {
            throw PEARModuleException::prefixOccupied($prefix);
        }

        $this->registeredPrefixes[] = [
            $prefix,
            realpath($basePath)
        ];
    }

    /**
     * Remove a registered prefix from the module
     *
     * @param string $prefix The prefix name
     */
    public function unRegisterPrefix($prefix) {
        foreach($this->registeredPrefixes as $key => $registeredPrefix) {
            if($registeredPrefix[0] == $prefix) {
                unset($this->registeredPrefixes[$key]);
            }
        }
    }

    /**
     * Determines that the given prefix is registered in this module
     *
     * @param string $prefix The prefix name
     *
     * @return bool
     */
    public function prefixIsRegistered($prefix) {
        foreach($this->registeredPrefixes as $key => $registeredPrefix) {
            if($registeredPrefix[0] == $prefix) {
                return TRUE;
            }
        }

        return FALSE;
    }

}
