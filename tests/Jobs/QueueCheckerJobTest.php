<?php

use Schickling\QueueChecker\Jobs\QueueCheckerJob;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class QueueCheckerJobTest extends TestCase
{
    private $queueCheckerJob;
    private $taskMock;

    public function setUp()
    {
        parent::setUp();

        Cache::put('queue-checker-job-value', 0, 0);

        $this->queueCheckerJob = new QueueCheckerJob();

        $this->taskMock = m::mock();
        $this->taskMock->shouldReceive('delete');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testJobIncreaseValue()
    {
        $jobValueBeforeExecution = Cache::get('queue-checker-job-value');

        $this->queueCheckerJob->fire($this->taskMock, $jobValueBeforeExecution);

        $jobValueAfterExecution = Cache::get('queue-checker-job-value');
        $expectedJobValueAfterExecution = $jobValueBeforeExecution + 1;

        $this->assertEquals($expectedJobValueAfterExecution, $jobValueAfterExecution);
    }


}