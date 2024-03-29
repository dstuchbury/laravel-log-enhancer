<?php

namespace Dstuchbury\LaravelLogEnhancer\Test;

use Illuminate\Log\Logger;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Dstuchbury\LaravelLogEnhancer\RequestDataProcessor;

class LogEnhancerTest extends TestCase
{
    /** @test */
    public function it_adds_request_details_to_logs()
    {
        $logger = $this->app[Logger::class];

        $handlers = $logger->getHandlers();
        foreach ($handlers as $handler) {
            if (config('laravel_log_enhancer.log_git_data')) {
                $this->assertInstanceOf(GitProcessor::class, $handler->popProcessor());
            }

            if (config('laravel_log_enhancer.log_memory_usage')) {
                $this->assertInstanceOf(MemoryUsageProcessor::class, $handler->popProcessor());
            }

            $this->assertInstanceOf(RequestDataProcessor::class, $handler->popProcessor());

            if (config('laravel_log_enhancer.log_request_details')) {
                $this->assertInstanceOf(WebProcessor::class, $handler->popProcessor());
            }
        }
    }

    /** @test */
    public function it_skips_input_details_as_per_the_configuration()
    {
        $record = [];

        config(['laravel_log_enhancer.log_input_data' => false]);

        $requestDataProcessor = new RequestDataProcessor;
        $record = $requestDataProcessor($record);

        $this->assertArrayNotHasKey('inputs', $record['extra']);
    }

    /** @test */
    public function it_adds_other_details_as_per_the_configuration()
    {
        $record = [];

        config(['laravel_log_enhancer.log_request_headers' => rand(0, 1)]);
        config(['laravel_log_enhancer.log_session_data' => rand(0, 1)]);

        $requestDataProcessor = new RequestDataProcessor;
        $record = $requestDataProcessor($record);

        if (config('laravel_log_enhancer.log_request_headers')) {
            $this->assertArrayHasKey('headers', $record['extra']);
        } else {
            $this->assertArrayNotHasKey('headers', $record['extra']);
        }

        if (config('laravel_log_enhancer.log_session_data')) {
            $this->assertArrayHasKey('session', $record['extra']);
        } else {
            $this->assertArrayNotHasKey('session', $record['extra']);
        }
    }

    /** @test */
    public function it_has_result_element()
    {
        $record = [];
        $requestDataProcessor = new RequestDataProcessor();
        $record = $requestDataProcessor($record);

        $this->assertArrayHasKey('result', $record['extra']);
    }
}