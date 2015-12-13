<?php namespace BuildR\ClassLoader\Modules;

class PSR4ClassLoaderModule extends AbstractClassLoaderModule{

    private $registeredNamespaces = [];

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getAdditionalModuleFiles() {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . 'PSR4ModuleException.php',
        ];
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getName() {
        return 'PSR4ClassLoaderModule';
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getPriority() {
        return 10;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function onRegistered() {

    }

    /**
     * @inheritDoc
     */
    public function load($className) {
        foreach ($this->registeredNamespaces as $singleNamespace) {
            $prefix = $singleNamespace[0];
            $basePath = $singleNamespace[1];

            $prefixLength = strlen($prefix);
            if(strncmp($prefix, $className, $prefixLength) !== 0) {
                continue;
            }

            $relativeClassName = substr($className, $prefixLength);
            $fileLocation = $basePath . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassName) . '.php';

            var_dump(($fileLocation));
            if(file_exists($fileLocation)) {
                include_once $fileLocation;

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Register a new PSR-4 compatible namespace to the module
     *
     * @param string $namespace The namespace name
     * @param string $basePath Tha namespace base path
     *
     * @throws \BuildR\ClassLoader\Modules\PSR4ModuleException
     */
    public function registerNamespace($namespace, $basePath) {
        if($this->namespaceIsRegistered($namespace)) {
            throw PSR4ModuleException::namespaceOccupied($namespace);
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
