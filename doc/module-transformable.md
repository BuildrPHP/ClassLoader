---
currentMenu: transformable
---

# Transformable Module

## More Details

The Transformable module is rather unique. Since all other modules has a built-in definition how-to
transform a FQCN (Fully qualified class name) into a valid file name.

This module can take a `callable` type as argument. The closure takes the FQCN as argument.

In example:

```php
$classTransformer = function($className) {
    ...

    return (string) $fileLocation;
}
```

This function define how-to transform a class name into a valid file location.

## Module API

### Registering transformer

```php
$transformableModule->registerTransformer(string $name, callable $transformer);
```

### Remove transformer

```php
$transformableModule->removeTransformer(string $name);
```

### Checking status

Checks that the given transformer is registered in this module.

```php
$transformableModule->transformerIsRegistered(string $name);
```
