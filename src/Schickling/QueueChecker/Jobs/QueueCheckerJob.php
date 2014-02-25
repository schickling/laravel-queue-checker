<?php namespace Schickling\QueueChecker\Jobs;

use Cache;

class QueueCheckerJob
{

	public function fire($task, $data) {

		$jobValue = $data + 1;
		Cache::put('queue-checker-job-value', $jobValue, 0);

	}

}
