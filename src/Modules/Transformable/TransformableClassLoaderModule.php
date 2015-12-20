<?php namespace BuildR\ClassLoader\Modules\Transformable;

use BuildR\ClassLoader\Modules\AbstractClassLoaderModule;
use BuildR\ClassLoader\Modules\Transformable\TransformableModuleException;

/**
 * Transformable class laoder module
 *
 * BuildR PHP Framework
 *
 * @author Zoltán Borsos <zolli07@gmail.com>
 * @package ClassLoader
 * @subpackage Modules\Transformable
 *
 * @copyright    Copyright 2015, Zoltán Borsos.
 * @license      https://github.com/Zolli/BuildR/blob/master/LICENSE.md
 * @link         https://github.com/Zolli/BuildR
 */
class TransformableClassLoaderModule extends AbstractClassLoaderModule {

    /**
     * @type array
     */
    private $registeredTransformers = [];

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getAdditionalModuleFiles() {
        return [];
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getName() {
        return 'TransformableClassLoaderModule';
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getPriority() {
        return 25;
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
        if(count($this->registeredTransformers) < 1) {
            return FALSE;
        }

        foreach($this->registeredTransformers as $name => $transformerCallable) {
            $transformerResult = call_user_func_array($transformerCallable, [$className]);

            if($transformerResult !== FALSE && file_exists($transformerResult)) {
                include_once $transformerResult;

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Register a new transformer in this loader
     *
     * @param string $name Transformer name
     * @param callable $transformer
     *
     * @throws \BuildR\ClassLoader\Modules\Transformable\TransformableModuleException
     */
    public function registerTransformer($name, callable $transformer) {
        if($this->transformerIsRegistered($name)) {
            throw TransformableModuleException::nameOccupied($name);
        }

        $this->registeredTransformers[$name] = $transformer;
    }

    /**
     * Remove the given transformer from the module, if exist
     *
     * @param string $name The transformer name
     *
     * @return bool
     */
    public function removeTransformer($name) {
        if($this->transformerIsRegistered($name)) {
            unset($this->registeredTransformers[$name]);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Determines that transformer is registered with the given name, or not
     *
     * @param string $name The transformer name
     *
     * @return bool
     */
    public function transformerIsRegistered($name) {
        return isset($this->registeredTransformers[$name]);
    }

}
