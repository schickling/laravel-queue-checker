<?php

use Schickling\QueueChecker\Commands\QueueCheckerResetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;

class QueueCheckerResetCommandTest extends TestCase
{
    private $tester;

    public function setUp()
    {
        parent::setUp();

        Cache::flush();

        Cache::put('queue-checker-job-value', 0, 60);
        Cache::put('queue-checker-command-value', 2, 60);

        $command = new QueueCheckerResetCommand();
        $this->tester = new CommandTester($command);
    }

    public function testResetQueueCheckValues()
    {
        $this->tester->execute(array());

        $this->assertEquals(0, Cache::get('queue-checker-command-value'));
        $this->assertEquals(0, Cache::get('queue-checker-job-value'));
    }



}