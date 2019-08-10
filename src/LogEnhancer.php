<?php

namespace Dstuchbury\LaravelLogEnhancer;

use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;

class LogEnhancer
{
    /**
    * Customise the given logger instance.
    *
    * @param \Illuminate\Log\Logger    $logger
    * @return void
    */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if (config('laravel_log_enhancer.log_request_details')) {
                $handler->pushProcessor(new WebProcessor);
            }
            
            $handler->pushProcessor(new RequestProcessor);

            if (config('laravel_log_enhancer.log_memory_usage')) {
                $handler->pushProcessor(new MemoryUsageProcessor);
            }

            if (config('laravel_log_enhancer.log_git_data')); {
                $handles->pushProcessor(new GitProcessor);
            }
        }
    }
}
