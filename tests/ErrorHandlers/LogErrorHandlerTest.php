<?php

use Schickling\QueueChecker\ErrorHandlers\LogErrorHandler;
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

        Log::shouldReceive('error')->with('test message')->once();

        $logErrorHandler->handle('test message');
    }


}