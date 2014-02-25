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

        Cache::put('queue-checker-job-value', 0, 0);
        Cache::put('queue-checker-command-value', 0, 0);

        $command = new QueueCheckerCommand();
        $this->tester = new CommandTester($command);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testSameCacheValues()
    {
        $jobValue = Cache::get('queue-checker-job-value');
        $queueValue = Cache::get('queue-checker-command-value');

        $this->assertEquals($jobValue, $queueValue);
    }

    public function testJobPushedToQueue()
    {
        $this->fillQueue();
        
        $this->tester->execute(array());

    }

    public function testQueueIncreaseValue()
    {
        $queueValueBeforeExecution = Cache::get('queue-checker-command-value');

        $this->fillQueue();

        $this->tester->execute(array());

        $queueValueAfterExecution = Cache::get('queue-checker-command-value');
        $expectedQueueValueAfterExecution = $queueValueBeforeExecution + 1;

        $this->assertEquals($expectedQueueValueAfterExecution, $queueValueAfterExecution);
    }

    public function testErrorHandling() 
    {

        $errorHandlerMock = m::mock('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface');
        $errorHandlerMock->shouldReceive('handle');

        $this->app->instance('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface', $errorHandlerMock);

        $queueValue = Cache::get('queue-checker-command-value');
        Cache::put('queue-checker-command-value', $queueValue + 1, 0);

        $this->tester->execute(array());
    }

    private function fillQueue() 
    {
        $jobValue = Cache::get('queue-checker-job-value');

        $jobData = array(
            'valueToIncrease' => $jobValue
            );

        Queue::shouldReceive('push')->with('Schickling\QueueChecker\Jobs\QueueCheckerJob', $jobData)->once();
    }

}