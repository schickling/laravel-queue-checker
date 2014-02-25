<?php namespace Schickling\QueueChecker;

use Illuminate\Support\ServiceProvider;

class QueueCheckerServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface', 'Schickling\QueueChecker\ErrorHandlers\LogErrorHandler');


        $this->app['queue.check'] = $this->app->share(function($app)
        {
            return new Commands\QueueCheckerCommand();
        });

        $this->commands(
            'queue.check'
            );
    }

}