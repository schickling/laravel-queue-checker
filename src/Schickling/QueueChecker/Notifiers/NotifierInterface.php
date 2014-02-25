<?php namespace Schickling\QueueChecker\Notifiers;

interface NotifierInterface
{
    public function notify($message);
}