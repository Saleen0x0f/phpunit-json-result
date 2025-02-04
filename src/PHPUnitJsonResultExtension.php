<?php

declare(strict_types=1);

namespace Saleen\PHPUnitJsonResult;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Saleen\PHPUnitJsonResult\Subscribers\TestRunner\TestRunnerFinishedSubscriber;

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
        if ($configuration->noOutput()) {
            return;
        }

        // very important to replace output with ours
        $facade->replaceOutput();

        $facade->registerSubscribers(
            new TestRunnerFinishedSubscriber(),
        );
    }
}
