# PHPUnit Json Result 

The tools can communicate with API using JSON.

This package requires PHPUnit 10+ and PHP 8.2+.

## Install

```bash
composer require --dev saleen0x0f/phpunit-json-result
```

## Usage

Register extension in your `phpunit.xml` file:

```xml
<extensions>
    <bootstrap class="PHPUnitJsonResult\PHPUnitJsonResultExtension" />
</extensions>
```
