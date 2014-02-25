<?php namespace Schickling\QueueChecker\Commands;

use Illuminate\Console\Command;
use Cache;
use Queue;
use App;

class QueueCheckerCommand extends Command
{

    protected $name = 'queue:check';

    protected $description = 'Check queue is running';

    public function fire()
    {
        // TODO remove quick fix
        Queue::connection();

        if (Queue::connected())
        {
            $this->checkIfCacheWasInitialized();

            $jobValue = Cache::get('queue-checker-job-value');
            $queueValue = Cache::get('queue-checker-command-value');

            if ($jobValue == $queueValue)
            {
                $jobValue++;
                $jobValue %= 1000000;
                Queue::push('Schickling\QueueChecker\Jobs\QueueCheckerJob', ['jobValue' => $jobValue]);
                Cache::put('queue-checker-command-value', $jobValue, 60);
            }
            else
            {
                $errorHandler = App::make('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface');
                $errorHandler->handle('Queue does not seem to be working.');
            }
        }
        else
        {
            $errorHandler = App::make('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface');
            $errorHandler->handle('Queue is not connected.');
        }

    }

    private function checkIfCacheWasInitialized()
    {
        if ( ! Cache::has('queue-checker-job-value'))
        {
            Cache::put('queue-checker-job-value', 0, 60);
        }

        if ( ! Cache::has('queue-checker-command-value'))
        {
            Cache::put('queue-checker-command-value', 0, 60);
        }
    }

}