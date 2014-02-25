<?php namespace Schickling\QueueChecker\ErrorHandlers;

use Log;

class LogErrorHandler implements ErrorHandlerInterface
{
    public function handle($message)
    {
        Log::error($message);
    }
}