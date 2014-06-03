<?php namespace Schickling\QueueChecker\ErrorHandlers;

use Log;

class LogErrorHandler implements ErrorHandlerInterface
{
    public function handle($errorCode, $message)
    {
        Log::error('Error Code: ' . $errorCode . '. Message: ' . $message);
    }

}