# BuildR - Class Loader
### Highly experimental PHP framework

## Installation

### With Composer

```
composer require buildr/classloader
```

### Manual Loading

This package is suitable without Composer or other auto-loading mechanism. Use the internal loader to load all class that this
package needs to be functioning.

```php
//Include the loader initializer
include_once './src/ClassLoaderInitializer.php';

//Load all classes
\BuildR\ClassLoader\ClassLoaderInitializer::load();
```

## Main concept

The main concept behind this package that able to handle multiple auto-loading standards using class loader modules. These modules are representing auto-loading standards, like PSR-0, PSR-4, PEAR, etc... 

Registered modules are sorted by its priority, and this really speed up `spl_autoload_call()` calls, and this way only one class loader needs to be registered to `spl_autoload` queue.

### Priority system

Modules have default priorities that determines the order when its called. Lower number, means higher priority. When you loading a module, and the module default priority is occupied in the registry, te registry will automatically try to lower (increase) the priority of the module, and its will be retried fot 5 times, to find an empty spot.

## Usage

### Getting the registry

The `Registry` is the main component of this package, this used to register modules, and getting registered modules to work with.

Use the `ClassLoaderRegistry::create()` method to create an instance from the class loader and register the created instance in `spl_autoload` queue, and returns the created instance.
```php
$classLoader = \BuildR\ClassLoader\ClassLoaderRegistry::create();
```

### Using Modules

Because this module not need any auto-load mechanism, you need 2 parameter to load a module, the first is the module file absolute location. And the second is the module fully qualified class name.

All modules have default priority, but you can optionally pass a third parameter that overrides the module default priority.

```php
$psr4Module = $loaderRegistry->loadModule(
    __DIR__ . '/src/Modules/PSR4ClassLoaderModule.php', 
    \BuildR\ClassLoader\Modules\PSR4ClassLoaderModule::class,
    20
);
```
When the registration is complete the modules `ClassLoaderModuleInterface::onRegistered()` method will be called and a new instance from the module is returned. 

This instance is used to configureing the module, e.g. registering namespaces, class maps etc...

### Retrieving modules

Modules will be retrieved any time by calling the registry `getModuleByName($moduleName)` method. Each module have a unique, specific name. The modules have a static method (`ClassLoaderModuleInterface::getName()`) to retrieve the module name.

### Un-registering modules

Modules can be removed from the loader stack by calling the registry (`ClassLoaderRegistry::removeModuleByName($moduleName)`) method.

When removing a module the stack will be re-sorted.

## ToDo

 - [ ] Add common modules
 - [ ] Refactor Modules to own sub-namespace
 - [ ] Improve documentation

## Contribution

For contribution please refer our [Contribution Guide](https://raw.githubusercontent.com/Zolli/BuildR/master/LICENSE.md) Repository.

## License

BuildR and its components are licensed under GPL v3 ([Read](https://raw.githubusercontent.com/Zolli/BuildR/master/LICENSE.md))
[![License image](http://gplv3.fsf.org/gplv3-88x31.png)]()

## Thanks

Huge thanks all the package and tool author.
