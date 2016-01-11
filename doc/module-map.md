---
currentMenu: map
---

# ClassMap Module

## More Details

The ClassMap module is created to provide a (very basic) caching functionality. Transformed modules (PSR0, PSR4, PEAR)
are great, but is really slow compared to class maps.

A `map` actually an associative PHP array, that contains the FQCN (Fully qualified class name) as key
and absolute path as value.

The module can store multiple class map.

## Map example

```php
$map = [
    'BuildR\\ClassLoader\\ClassLoader' => '/var/www/domain.tld/vendor/src/ClassLoader.php',
    'BuildR\\ClassLoader\\ModuleLoader' => '/var/www/domain.tld/vendor/src/ModuleLoader.php',
];
```

## Module API

### Registering maps

```php
$classMapModule->registerMap(string $mapName, array $classMap);
```

The `mapName` parameter is only a helper, this name helps you to identify the registered maps later.

### Remove maps

```php
$classMapModule->removeMap(string $mapName);
```

### Checking status

Checks that the given namespace is registered in this module.

```php
$classMapModule->mapIsRegistered(string $mapName);
```
