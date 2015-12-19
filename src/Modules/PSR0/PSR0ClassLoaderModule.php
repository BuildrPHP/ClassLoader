<?php namespace BuildR\ClassLoader\Modules\PSR0;

use BuildR\ClassLoader\Modules\AbstractClassLoaderModule;
use BuildR\ClassLoader\Modules\PSR0\PSR0ModuleException;

/**
 * PSR-0 compatible class loader module
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules\PSR0
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 */
class PSR0ClassLoaderModule extends AbstractClassLoaderModule {

    /**
     * @type array
     */
    protected $registeredNamespaces = [];

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function onRegistered() {}

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getAdditionalModuleFiles() {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . '../PSR4/PSR4ModuleException.php',
            __DIR__ . DIRECTORY_SEPARATOR . 'PSR0ModuleException.php',
        ];
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getName() {
        return 'PSR0ClassLoaderModule';
    }

    /**
     * @inheritDoc
     */
    public function load($className) {
        if(count($this->registeredNamespaces) < 1) {
            return FALSE;
        }

        //Detect namespace and class name from the FQCN
        $pos = strrpos($className, '\\');
        $namespace = substr($className, 0, $pos);
        $className = substr($className, $pos + 1);

        //Create the normalized class name
        $normalizedClass = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        $normalizedClass .= str_replace('_', DIRECTORY_SEPARATOR, $className);

        //Loop through registered namespaces
        foreach($this->registeredNamespaces as $singleNamespace) {
            $prefix = str_replace('\\', DIRECTORY_SEPARATOR, $singleNamespace[0]);
            $basePath = $singleNamespace[1];

            $pos = stripos($normalizedClass, $prefix);
            if($pos === FALSE || $pos > 1) {
                continue;
            }

            // build the full path
            $file = rtrim($basePath, '/') . DIRECTORY_SEPARATOR . ltrim($normalizedClass, '/') . '.php';

            if(file_exists($file)) {
                include_once $file;

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * @inheritDoc
     *
     * @throws \BuildR\ClassLoader\Modules\PSR0\PSR0ModuleException
     */
    public function registerNamespace($namespace, $basePath) {
        if($this->namespaceIsRegistered($namespace)) {
            throw PSR0ModuleException::namespaceOccupied($namespace);
        }

        $this->registeredNamespaces[] = [
            $namespace,
            realpath($basePath)
        ];
    }

    /**
     * Remove a registered namespace from the module
     *
     * @param string $namespace The namespace name
     */
    public function unRegisterNamespace($namespace) {
        foreach($this->registeredNamespaces as $key => $registeredNamespace) {
            if($registeredNamespace[0] == $namespace) {
                unset($this->registeredNamespaces[$key]);
            }
        }
    }

    /**
     * Determines that the given namespace nem is registered in this module
     *
     * @param string $namespace The namespace name
     *
     * @return bool
     */
    public function namespaceIsRegistered($namespace) {
        foreach($this->registeredNamespaces as $key => $registeredNamespace) {
            if($registeredNamespace[0] == $namespace) {
                return TRUE;
            }
        }

        return FALSE;
    }

}
