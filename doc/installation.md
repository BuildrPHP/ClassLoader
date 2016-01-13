---
currentMenu: installation
baseUrl: ..
---

# Installation

## With composer

Simply add this project to your `composer.json` and you good to go.

```json
    {
      "require": {
        "buildr/classloader": "1.1.*"
      }
    }
```

## Self Loading

This package comes with a class that self-load all files that this package needs to be functioning.
After the self-loading you can able to use the `ClassLoader` and you can register this component namespace.

```php
//Include the loader initializer
include_once './src/ClassLoaderInitializer.php';

//Load all classes
(new \BuildR\ClassLoader\ClassLoaderInitializer())->load();

//Create a new class loader
$loader = ClassLoader::create();

/** @type \BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule $module */
$PSR4Module = $loader->loadModule(
    __DIR__ . DIRECTORY_SEPARATOR . '../../src/Modules/PSR4/PSR4ClassLoaderModule.php',
    \BuildR\ClassLoader\Modules\PSR4\PSR4ClassLoaderModule::class
);

//Registering this module namespace
$PSR4Module->registerNamespace('BuildR\\ClassLoader\\', __DIR__ . '/src');
```
