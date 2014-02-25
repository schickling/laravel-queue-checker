<?php

use Schickling\QueueChecker\Commands\QueueCheckerCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class QueueCheckerCommandTest extends TestCase
{
    private $tester;

    public function setUp()
    {
        parent::setUp();
        Cache::flush();

        $command = new QueueCheckerCommand();
        $this->tester = new CommandTester($command);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testJobPushedToQueue()
    {
        Queue::shouldReceive('push')->with('Schickling\QueueChecker\Jobs\QueueCheckerJob', ['jobValue' => 1])->once();

        $this->tester->execute(array());
    }

    public function testCacheGetsInitialized()
    {
        Queue::shouldReceive('push')->with('Schickling\QueueChecker\Jobs\QueueCheckerJob', ['jobValue' => 1])->once();

        $this->tester->execute(array());

        $this->assertEquals(1, Cache::get('queue-checker-command-value'));
        $this->assertEquals(0, Cache::get('queue-checker-job-value'));
    }

    public function testQueueIncreaseValue()
    {
        Cache::put('queue-checker-command-value', 2, 0);
        Cache::put('queue-checker-job-value', 2, 0);
        Queue::shouldReceive('push')->with('Schickling\QueueChecker\Jobs\QueueCheckerJob', ['jobValue' => 3])->once();

        $this->tester->execute(array());

        $this->assertEquals(3, Cache::get('queue-checker-command-value'));
        $this->assertEquals(2, Cache::get('queue-checker-job-value'));
    }

    public function testErrorHandlingWhenJobValueBiggerAsCommandValue()
    {
        $errorHandlerMock = m::mock('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface');
        $errorHandlerMock->shouldReceive('handle');
        $this->app->instance('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface', $errorHandlerMock);

        Cache::put('queue-checker-command-value', 2, 0);
        Cache::put('queue-checker-job-value', 3, 0);

        $this->tester->execute(array());
    }

    public function testErrorHandlingWhenCommandValueBiggerAsJobValue()
    {
        $errorHandlerMock = m::mock('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface');
        $errorHandlerMock->shouldReceive('handle');
        $this->app->instance('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface', $errorHandlerMock);

        Cache::put('queue-checker-command-value', 3, 0);
        Cache::put('queue-checker-job-value', 2, 0);

        $this->tester->execute(array());
    }

}