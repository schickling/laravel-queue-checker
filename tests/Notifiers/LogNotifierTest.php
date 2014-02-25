<?php

use Schickling\QueueChecker\Notifiers\LogNotifier;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class LogNotifierTest extends TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testJobIncreaseValue()
    {
        $notifier = new LogNotifier();

        Log::shouldReceive('error')->with('test message')->once();

        $notifier->notify('test message');
    }


}