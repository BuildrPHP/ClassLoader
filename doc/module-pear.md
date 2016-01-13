---
currentMenu: pear
baseUrl: ..
---

# PEAR Module

## More Details

The PEAR autoloading standard is very old, but dome package and tools are still using it. PEAR use prefixes and
namespace like separation in class names. Because I not found any good documentation or example
here is a small example:

```
The class name: Test_Module_PSR0_PSR0ClassLoaderModule

Register with:
    Prefix: Test_
    Root: /var/www/domain.tld/

The prefix is trimmed from the class name and replaced with the root.
From the remaining part (Module_PSR0_PSR0ClassLoaderModule) will be exploded on '_'
and the last part is used as the file name (PSR0ClassLoaderModule.php) and the remaining
part (Module_PSR0) '_' characters will be replaced with PHP's DIRECTORY_SEPARATOR constant.

So, the absolute path to the file is: /var/www/domain.tld/Module/PSR0/PSR0ClassLoaderModule.php
```

`NOTE: PEAR style autoloding can achieved via PSR0 Module. please refer PSR0 guide for this feature.`

## Module API

### Registering prefixes

```php
$pearModule->registerPrefix(string $prefix, string $basePath);
```

### Remove prefix

```php
$pearModule->unRegisterPrefix(string $prefix);
```

### Checking status

Checks that the given prefix is registered in this module.

```php
$pearModule->prefixIsRegistered(string $prefix);
```
