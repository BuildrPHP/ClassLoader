# Documentation

## Initializing

You can use the factory method to create a new instance.

```php
$classLoader = ClassLoader::create();
```

Or, if you want a to use a custom `ModuleLoader` you can create a new instance like this.

```php
$classLoader = new ClassLoader((new MyAwesomeModuleLoader()));
```

## Using Modules

Because modules not need any auto-load mechanism, you needs 2 parameter to load a module. The first is the module
file absolute location, and the second is the module fully qualified class name.

All modules have default priority, but you can optionally pass a third parameter that overrides the
module default priority.

```php
$psr4Module = $classLoader->loadModule(string $moduleAbsolute, string $moduleFqcn, int $priorityOverride);
```

When the registration is complete the module `onRegister()` method will be called. After the registration
the `ClassLoader` returns a new instance of the given module.

The returned module instance is used to configure the module.

## Retrieving modules

Modules retrieved any time using the `ClassLoader::getModuleByName(string $name, int $priority)`.

Because the same module can registered multiple times, you must pass the registered priority to the second parameter.

## Un-registering modules

Un registering modules is easy, use the `ClassLoader::removeModuleByName(string $name, int $priority)`.

Because the same module can registered multiple times, you must pass the registered priority to the second parameter.

