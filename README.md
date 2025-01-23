# PHPUnit Json Result 

Other PHP CLI tool can communicate with API using JSON. Why not PHPUnit?

This package requires PHPUnit 10+ and PHP 8.2+.

## Install

```bash
composer require --dev sallen/phpunit-json-result
```

## Usage

Register extension in your `phpunit.xml` file:

```xml
<extensions>
    <bootstrap class="PHPUnitJsonResult\PHPUnitJsonResultExtension" />
</extensions>
```
