<?php namespace BuildR\ClassLoader;

use BuildR\ClassLoader\Exception\ClassLoaderException;
use BuildR\ClassLoader\Exception\ModuleException;
use BuildR\ClassLoader\Modules\ClassLoaderModuleInterface;

/**
 * The class loader registry. This class has multiple purpose, first its handle
 * the module registration, secondly this is responsible for loading classes.
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 */
class ClassLoaderRegistry {

    /**
     * How many times try to increase the priority of a given module, before
     * throws an exception
     */
    const PRIORITY_INCREASE_RETRY_COUNT = 5;

    /**
     * How many times we tried to increase the priority in one registration session
     *
     * @type int
     */
    private $priorityIncreaseCounter = 0;

    /**
     * The module stack
     *
     * @type \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface[]
     */
    private $modules = [];

    /**
     * The module preloader class
     *
     * @type \BuildR\ClassLoader\ModuleLoader
     */
    private $moduleLoader;

    /**
     * Creates a new instance from the loader registry.
     *
     * @return \BuildR\ClassLoader\ClassLoaderRegistry
     */
    public static function create() {
        $moduleLoader = new ModuleLoader();

        return new self($moduleLoader);
    }

    /**
     * ClassLoaderRegistry constructor.
     *
     * @param \BuildR\ClassLoader\ModuleLoader $moduleLoader
     */
    protected function __construct(ModuleLoader $moduleLoader) {
        $this->moduleLoader = $moduleLoader;
        $this->registerLoader();
    }

    /**
     * Attempts to load the given class loader module. When the module is not found
     * throws an exception.
     *
     * @param string $moduleFile Absolute location to the module
     * @param string $moduleClassName The module class FQCN
     * @param int|NULL $priorityOverride Overrides the module default priority
     *
     * @return \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface
     *
     * @throws \BuildR\ClassLoader\Exception\ModuleException
     * @throws \BuildR\ClassLoader\Exception\ClassLoaderException
     */
    public function loadModule($moduleFile, $moduleClassName, $priorityOverride = NULL) {
        include_once $moduleFile;

        /** @type \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface $module */
        $module = $this->moduleLoader->preLoad($moduleClassName);

        if($priorityOverride !== NULL) {
            $module->setPriority($priorityOverride);
        }

        return $this->registerModule($module);
    }

    /**
     * Get a registered loader module from the stack by its name.
     * To determine a module name use the modules getName() method.
     *
     * @param string $moduleName The module name
     * @param int $priority The target module priority
     *
     * @return \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface
     *
     * @throws \BuildR\ClassLoader\Exception\ModuleException;
     */
    public function getModule($moduleName, $priority) {
        foreach ($this->modules as $modulePriority => $module) {
            if(call_user_func([$module, 'getName']) === $moduleName && $modulePriority == $priority) {
                return $module;
            }
        }

        throw ModuleException::notFound('Name: ' . $moduleName);
    }

    /**
     * Remove a registered loader module from the stack by its name.
     * To determine a module name use the modules getName() method.
     *
     * @param string $moduleName The module name
     * @param inst $priority The priority
     *
     * @return bool
     *
     * @throws \BuildR\ClassLoader\Exception\ModuleException
     */
    public function removeModule($moduleName, $priority) {
        foreach ($this->modules as $modulePriority => $module) {
            if(call_user_func([$module, 'getName']) === $moduleName && $modulePriority == $priority) {
                unset($this->modules[$priority]);
                ksort($this->modules);

                return TRUE;
            }
        }

        throw ModuleException::notFound('Name: ' . $moduleName . ' Priority: ' . $priority);
    }

    /**
     * Returns the registry entire module stack.
     *
     * @return \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface[]
     */
    public function getModuleStack() {
        return $this->modules;
    }

    /**
     * Register a new module in registry. and
     * returns the module when the registration is success.
     *
     * @param \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface $module
     *
     * @return \BuildR\ClassLoader\Modules\ClassLoaderModuleInterface
     *
     * @throws \BuildR\ClassLoader\Exception\ClassLoaderException
     */
    protected function registerModule(ClassLoaderModuleInterface $module) {
        $priority = $module->getPriority();

        if(!isset($this->modules[$priority])) {
            $this->modules[$priority] = $module;
            $this->priorityIncreaseCounter = 0;
            ksort($this->modules);
            $module->onRegistered();

            return $module;
        }

        if($this->priorityIncreaseCounter >= self::PRIORITY_INCREASE_RETRY_COUNT) {
            $this->priorityIncreaseCounter = 0;
            throw ClassLoaderException::priorityIncreaseLimit();
        }

        $module->setPriority($priority + 1);
        $this->priorityIncreaseCounter++;
        $this->registerModule($module);

        $errorMessage = "Another class Loader module is registered with priority {$priority}! ";
        $errorMessage .= "Increasing priority by one, to find a new spot.";
        trigger_error($errorMessage, E_USER_NOTICE);
    }

    /**
     * This function is registered as auto-load method with spl_autoload_register()
     * The method try to load the given FQCN with all registered class loader module,
     * modules are sorted in registration phase, if a module able to load the class properly
     * the remaining loaders will not be queried for loading the class.
     *
     * @param string $className The FQCN.
     *
     * @return bool
     */
    public function loadClass($className) {
        foreach($this->modules as $loader) {
            if($loader->load($className) === TRUE) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Register the loader as valid SPL auto-loader
     *
     * @param bool $prepend Prepend the loader to the queue instead of appending it.
     */
    public function registerLoader($prepend = FALSE) {
        spl_autoload_register([$this, 'loadClass'], TRUE, $prepend);
    }

    /**
     * Try to remove this class from the spl_autoload queue.
     */
    public function unRegisterLoader() {
        spl_autoload_unregister([$this, 'loadClass']);
    }

}
