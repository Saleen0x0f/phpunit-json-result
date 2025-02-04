<?php

declare(strict_types=1);

namespace Saleen\PHPUnitJsonResult\Subscribers\TestRunner;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\TestRunner\Finished;
use PHPUnit\Event\TestRunner\FinishedSubscriber;
use PHPUnit\Metadata\DataProvider;
use PHPUnit\TestRunner\TestResult\Facade;
use PHPUnit\TestRunner\TestResult\TestResult;

final class TestRunnerFinishedSubscriber implements FinishedSubscriber
{
    public function __construct() {
    }

    public function notify(Finished $event): void
    {
        $testResult = Facade::result();
        $resultJsonData = [
            'counts' => [
                'tests' => $testResult->numberOfTestsRun(),
                'failed' => $testResult->numberOfTestFailedEvents(),
                'assertions' => $testResult->numberOfAssertions(),
                'errors' => $testResult->numberOfTestErroredEvents(),
                'warnings' => $testResult->numberOfWarnings(),
                'deprecations' => $testResult->numberOfDeprecations(),
                'notices' => $testResult->numberOfNotices(),
                'success' => $testResult->numberOfTestsRun() - $testResult->numberOfTestErroredEvents(),
                'incomplete' => $testResult->numberOfTestMarkedIncompleteEvents(),
                'risky' => $testResult->numberOfTestsWithTestConsideredRiskyEvents(),
                'skipped' => $testResult->numberOfTestSuiteSkippedEvents() + $testResult->numberOfTestSkippedEvents(),
            ],
        ];

        $resultJsonData['failed'] = $this->createFailedEventDatas($testResult);
        $resultJson = json_encode($resultJsonData, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        $this->reportToFile($resultJson);
        
    }

    private function reportToFile(string $resultJson): string
    {
        $dir = "/mnt/c/Users/Maxim/docs/tester/tests/";
        $file = "php-json.json";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($dir . 'php-json.json')) {
            touch($dir . $file);
        }

        $file = fopen($dir . $file,'w+');
        fwrite($file, $resultJson);
        fclose($file);

        return "OK";
    }    

    /**
     * @return array<string, mixed>
     */
    private function createDataProviderData(TestMethod $testMethod): array
    {
        $dataFromDataProvider = $testMethod->testData()->dataFromDataProvider();

        $dataProviderData = [
            'key' => $dataFromDataProvider->dataSetName(),
            'data' => $dataFromDataProvider->data(),
        ];

        foreach ($testMethod->metadata() as $metadata) {
            if ($metadata instanceof DataProvider) {
                $dataProviderData['provider_method'] = $metadata->methodName();
            }
        }

        return $dataProviderData;
    }

    private function resolveLineNumber(string $stackTrace): int
    {
        preg_match('#:(?<line>\d+)$#', $stackTrace, $matches);

        if (! isset($matches['line'])) {
            return 0;
        }

        return (int) $matches['line'];
    }

    /**
     * @return array<array<string, mixed>>
     */
    private function createFailedEventDatas(TestResult $testResult): array
    {
        $failedEventDatas = [];

        foreach ($testResult->testFailedEvents() as $testFailedEvent) {
            /** @var Failed $testFailedEvent */
            $testMethod = $testFailedEvent->test();

            /** @var TestMethod $testMethod */
            $failedEventData = [
                'test_file_path' => $testMethod->file(),
                'test_class' => $testMethod->className(),
                'test_method' => $testMethod->methodName(),
                'message' => $testFailedEvent->throwable()->message(),
                'exception_class' => $testFailedEvent->throwable()->className(),
                'line' => $this->resolveLineNumber($testFailedEvent->throwable()->stackTrace()),
            ];

            if ($testMethod->testData()->hasDataFromDataProvider()) {
                $failedEventData['data_provider'] = $this->createDataProviderData($testMethod);
            }

            $failedEventDatas[] = $failedEventData;
        }

        return $failedEventDatas;
    }
}
