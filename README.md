laravel-queue-checker [![Build Status](https://travis-ci.org/schickling/laravel-queue-checker.png?branch=master)](https://travis-ci.org/schickling/laravel-queue-checker) [![Coverage Status](https://coveralls.io/repos/schickling/laravel-queue-checker/badge.png)](https://coveralls.io/r/schickling/laravel-queue-checker) [![Total Downloads](https://poser.pugx.org/schickling/queue-checker/downloads.png)](https://packagist.org/packages/schickling/queue-checker)
=====================

Command to check the queue health status. Can be used with hosted monitoring systems.

## Installation

1. Add the following to your composer.json and run `composer update`

    ```json
    {
        "require": {
            "schickling/queue-checker": "dev-master"
        }
    }
    ```

2. Add `Schickling\QueueChecker\QueueCheckerServiceProvider` to your config/app.php

## Usage

### Use a cronjob
Run the following command as cronjob (for example each minute). If the queue isn't connected or does not work, the binded `ErrorHandler` will be notified. The default `ErrorHandler` will log the incident.

```sh
$ php artisan queue:check
```

### Implement your own `ErrorHandler`
You can for example write an `ErrorHandler` that sends a message to your system monitoring platform such as NewRelic. Simply create a class that implements the `Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface` and bind your `ErrorHandler` with the following code:

```php
App::bind('Schickling\QueueChecker\ErrorHandlers\ErrorHandlerInterface', 'App\MyCustomErrorHandler');
```