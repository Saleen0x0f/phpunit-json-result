<?php

declare(strict_types=1);

namespace Sallen\PHPUnitJsonResult;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

/**
 * Registered in phpunit.xml
 */
final class PHPUnitJsonResultExtension implements Extension
{
   
    public function __construct()
    {
        
    }

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        
    }
}
