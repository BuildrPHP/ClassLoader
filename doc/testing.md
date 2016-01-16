---
currentMenu: testing
baseUrl: ..
---

# API Documentation

All buildR component is highly covered with unit tests.
This project uses [PHPUnit](https://phpunit.de/) to run tests.

Packages provides 2 separate configuration file for various logging output.

 - `phpunit.xml` - Only generates console API documentation (No code coverage and crap)
 - `phpunit-ci.xml` - Generated CRAP and code coverage reports in xml format

`PHPUnit` is installed via composer if dev dependencies not disabled (with `--no-dev` switch).

Use this command to run test suite:

```bash
$ ./vendor/bin/phpunit --configuration phpunit.xml
```
