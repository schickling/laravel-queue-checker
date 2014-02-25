<?php namespace Schickling\QueueChecker\Commands;

use Illuminate\Console\Command;


class QueueCheckerCommand extends Command 
{


	protected $name = 'queue:check';

	protected $description = 'Check queue is running';

	public function fire()
	{
		$this->checkIfCacheWasInitialized();

		$jobValue = Cache::get('queue-checker-job-value');
		$queueValue = Cache::get('queue-checker-command-value');

		if ($jobValue == $queueValue) 
		{

			$jobData = array(
	            'valueToIncrease' => $jobValue
	            );
			
			Queue::push('Schickling\QueueChecker\Jobs\QueueCheckerJob', $jobData);

			Cache::put('queue-checker-command-value', $queueValue + 1, 0);

		} 
		else 
		{
			//error
		}

	}

	private function checkIfCacheWasInitialized() 
	{
		if (!Cache::has('queue-checker-job-value'))
		{
			Cache::put('queue-checker-job-value', 0, 0);
		}

		if (!Cache::has('queue-checker-command-value'))
		{
			Cache::put('queue-checker-command-value', 0, 0);
		}
	}

}