---
currentMenu: PSR4
baseUrl: ..
---

# PSR-4 Module

## More Details

If you want to know more about PSR-4 naming convention please refer the PHP-FIG official guide:

[PSR-0](http://www.php-fig.org/psr/psr-4/#mandatory)

## Module API

### Registering namespaces

```php
$psr4Module->registerNamespace(string $namespacePrefix, string $basePath);
```

### Remove registered namespaces

```php
$psr4Module->unRegisterNamespace(string $namespacePrefix);
```

### Checking status

Checks that the given namespace is registered in this module.

```php
$psr4Module->namespaceIsRegistered(string $namespacePrefix);
```
