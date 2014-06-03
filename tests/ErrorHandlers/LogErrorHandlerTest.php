<?php

use Schickling\QueueChecker\ErrorHandlers\LogErrorHandler;
use Schickling\QueueChecker\ErrorHandlers\Errors;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class LogErrorHandlerTest extends TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testJobIncreaseValue()
    {
        $logErrorHandler = new LogErrorHandler();

        Log::shouldReceive('error')->with('Error Code: 0. Message: test message')->once();

        $logErrorHandler->handle(Errors::NOT_WORKING, 'test message');
    }


}