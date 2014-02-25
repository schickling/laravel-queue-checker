<?php namespace Schickling\QueueChecker\Jobs;

use Cache;

class QueueCheckerJob
{

	public function fire($task, $data)
    {
		Cache::put('queue-checker-job-value', $data['jobValue'], 60);

		$task->delete();
	}

}
