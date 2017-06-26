<?php

namespace Ms48\LaravelConsoleProgressBar\Facades;

use Illuminate\Support\Facades\Facade;

class ConsoleProgressBar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'consoleProgressBar';
    }
}
