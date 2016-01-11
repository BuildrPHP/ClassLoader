---
currentMenu: PSR0
---

# PSR-0 Module

## More Details

If you want to know more about PSR-0 naming convention please refer the PHP-FIG official guide:

[PSR-0](http://www.php-fig.org/psr/psr-0/#mandatory)

## Module API

### Registering namespaces

```php
$psr0Module->registerNamespace(string $namespacePrefix, string $basePath);
```

### Remove registered namespaces

```php
$psr0Module->unRegisterNamespace(string $namespacePrefix);
```

### Checking status

Checks that the given namespace is registered in this module.

```php
$psr0Module->namespaceIsRegistered(string $namespacePrefix);
```
