<?php namespace Schickling\QueueChecker\Notifiers;

use Log;

class LogNotifier implements NotifierInterface
{
    public function notify($message)
    {
        Log::error($message);
    }
}